<?php


namespace app\admin\controller\Sys\service;

use app\admin\controller\Sys\Config;
use app\admin\controller\Sys\model\Menu;
use app\admin\controller\Sys\validate\Field as FieldValidate;
use think\exception\ValidateException;
use think\facade\Db;

class Field extends \app\admin\controller\Admin
{
    /**
     * 创建字段
     * @param $data
     */
    public function create($data){
        // 新增的列应该在那个列的后面
        $afterField = $this->getAtferFieldName($data['menu_id']);
        try{
            if($data['create_table_field']){
                $menuInfo = Menu::find($data['menu_id']);
                $connect = $menuInfo['connect'] ? $menuInfo['connect'] : config('database.default');
                $prefix = config('database.connections.'.$connect.'.prefix');

                if($menuInfo['page_type'] != 2){
                    if((!empty($data['default_value']))){
                        if($data['type'] == 13){
                            $data['default_value'] = '0';
                        }
                        $default = "DEFAULT '".$data['default_value']."'";
                    }else{
                        $default = 'DEFAULT NULL';
                    }

                    if(in_array($data['datatype'],['datetime','longtext'])){
                        $data['length'] = ' null';
                    }else{
                        $data['length'] = "({$data['length']})";
                    }

                    $sql = "ALTER TABLE ";
                    $sql .= $prefix;
                    $sql .= "{$menuInfo['table_name']} ADD {$data['field']} {$data['datatype']}{$data['length']} COMMENT '{$data['title']}'  {$default} AFTER `{$afterField}`";

                    \think\facade\Log::info("---------------------添加列----------------------");
                    \think\facade\Log::info($sql);

                    Db::connect($connect)->execute($sql);

                    if(!empty($data['indexdata'])){
                        Db::connect($connect)->execute("ALTER TABLE ".$prefix."{$menuInfo['table_name']} ADD ".$data['indexdata']." (  `".$data['field']."` )");
                    }
                }
            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
        return true;
    }

    public function update($data){
        if($data['create_table_field']){
            $menuInfo = Menu::find($data['menu_id']);
            $connect = $menuInfo['connect'] ? $menuInfo['connect'] : config('database.default');
            $prefix = config('database.connections.'.$connect.'.prefix');

            if($menuInfo['page_type'] != 2){
                if(!empty($data['default_value'])){
                    if($data['type'] == 13){
                        $data['default_value'] = '0';
                    }
                    $default = "DEFAULT '".$data['default_value']."'";
                }else{
                    $default = 'DEFAULT NULL';
                }

                $fieldInfo = \app\admin\controller\Sys\model\Field::find($data['id']);
                $pk_id = Db::connect($connect)->name($menuInfo['table_name'])->getPk();

                if($fieldInfo['field'] == $pk_id){
                    $auto = 'AUTO_INCREMENT';
                    $maxlen = !empty($data['length']) ? $data['length'] : 11;
                    $datatype = !empty($data['datatype']) ? $data['datatype'] : 'int';
                    $default = 'NOT NULL';
                    $primary_key = 'PRIMARY KEY';

                    Menu::field('pk')->where('menu_id',$fieldInfo['menu_id'])->update(['pk'=>$data['field']]);

                }else{
                    $auto = '';
                }

                if(in_array($data['datatype'],['datetime','longtext'])){
                    $data['length'] = ' null';
                }else{
                    $data['length'] = "({$data['length']})";
                }

                $fields = self::getFields($prefix.$menuInfo['table_name'],$connect);

                if(in_array($fieldInfo['field'],$fields)){
                    $sql="ALTER TABLE ".$prefix."{$menuInfo['table_name']} CHANGE {$fieldInfo['field']} {$data['field']} {$data['datatype']}{$data['length']} COMMENT '{$data['title']}' {$default} {$auto}";
                }else{
                    $sql="ALTER TABLE ".$prefix."{$menuInfo['table_name']} ADD {$data['field']} {$data['datatype']}{$data['length']} COMMENT '{$data['title']}' {$default} {$auto} {$primary_key}";
                }

                Db::connect($connect)->execute($sql);

                //判断是否存在索引  不存在则创建
                if($data['indexdata']){
                    if(!self::getTableIndex($prefix.$menuInfo['table_name'],$data['field'],$connect)){
                        Db::connect($connect)->execute("ALTER TABLE ".$prefix."{$menuInfo['table_name']} ADD ".$data['indexdata']." (  `".$data['field']."` )");
                    }
                }
            }elseif($menuInfo['page_type'] == 2){
                $fieldInfo = Field::find($data['id']);
                Db::connect($connect)->name($menuInfo['table_name'])->where('name',$fieldInfo['field'])->update(['name'=>$data['field']]);
            }
        }
        return true;
    }

    public function delete($data){
        $menuInfo = Menu::find($data['menu_id']);
        $fieldList = \app\admin\controller\Sys\model\Field::field('create_table_field,field')->where($data)->select()->toArray();
        $connect = $menuInfo['connect'] ? $menuInfo['connect'] : config('database.default');

        if($menuInfo['page_type'] != 2){
            $prefix = config('database.connections.'.$connect.'.prefix');
            $fields = self::getFieldList($prefix.$menuInfo['table_name'],$connect);
            $drop = '';
            $pk = Db::connect($connect)->name($menuInfo['table_name'])->getPk();

            foreach($fieldList as $val){
                if($val['create_table_field'] == 1 && in_array($val['field'],$fields) && $val['field'] <> $pk){
                    $drop .= ' DROP '.$val['field'].' ,';
                }
            }

            $sql = 'ALTER TABLE '.$prefix.$menuInfo['table_name'].rtrim($drop,',');

            Db::connect($connect)->execute($sql);
        }else{
            foreach($fieldList as $val){
                Db::connect($connect)->name($menuInfo['table_name'])->where('name',$val['field'])->delete();
            }
        }
        return true;
    }

    //判断数据表字段是否存在
    public static function getFields($tablename,$connect){
        $list = Db::connect($connect)->query('show columns from '.$tablename);
        $arr = [];
        foreach($list as $v){
            $arr[] = $v['Field'];
        }
        return $arr;
    }


    //查看索引是否存在
    public static function getTableIndex($tablename,$indexName,$connect){
        $list = Db::connect($connect)->query('show index from '.$tablename);
        foreach($list as $k=>$v){
            if($v['Column_name'] == $indexName){
                $status = true;
            }
        }
        return $status;
    }

    //获取表的所有字段
    public static function getFieldList($tablename,$connect){
        $list = Db::connect($connect)->query('show columns from '.$tablename);
        foreach($list as $v){
            $arr[] = $v['Field'];
        }
        return $arr;
    }
}
