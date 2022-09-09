<?php


namespace app\admin\controller\Sys\service;


use app\admin\controller\Sys\Config;
use app\admin\controller\Sys\model\Application;
use app\admin\controller\Sys\model\Field;
use app\admin\controller\Sys\model\Menu;
use app\admin\controller\Sys\validate\Menu as MenuValidate;
use think\exception\ValidateException;
use think\facade\Db;

class Table extends \app\admin\controller\Admin
{
    public function create($data){
        try {
            if($data['create_table'] && $data['table_name'] && $data['pk']){
                $table_name = strtolower(trim($data['table_name']));
                $pk = strtolower(trim($data['pk']));

                $connect = $data['connect'] ? $data['connect'] : config('database.default');
                $prefix = config('database.connections.'.$connect.'.prefix');

                if(self::getTable($data['table_name'],$connect)){
                    throw new ValidateException('数据库中已经存在此表，请设置不创建表');
                }

                $sql=" CREATE TABLE IF NOT EXISTS `". $prefix .$table_name."` ( ";
                $sql .= '
				`'.$pk.'` int(11) NOT NULL AUTO_INCREMENT ,
				PRIMARY KEY (`'.$data['pk'].'`)
				) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
			';
                Db::connect($connect)->execute($sql);

                // 表单页面
                if($data['page_type'] == 2){
                    Db::connect($connect)->execute("ALTER TABLE `".$prefix.$data['table_name']."` ADD `name` VARCHAR( 50 ) NOT NULL ,ADD `data` TEXT NOT NULL AFTER `name`");
                }
            }
            // 添加默认字段
            self::defaultField($data);
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
        return true;
    }

    public function update($data){

        $this->validate($data,MenuValidate::class);

        if($data['create_table'] && $data['table_name'] && $data['pk']){
            // 表名
            $table_name = strtolower(trim($data['table_name']));
            // 主键
            $pk = strtolower(trim($data['pk']));
            // 菜单原来的信息
            $menuInfo = Menu::find($data['menu_id']);
            // 数据库链接
            $connect = $menuInfo['connect'] ? $menuInfo['connect'] : config('database.default');
            // 表名前缀
            $prefix = config('database.connections.'.$connect.'.prefix');
            // 如果此表存在，则改主键，重命名表
            if(self::getTable($menuInfo['table_name'],$connect)){
                $sqlTable = "ALTER TABLE ".$prefix."".$menuInfo['table_name']." CHANGE ".$menuInfo['pk']." ".$pk." INT( 11 ) COMMENT '编号' NOT NULL AUTO_INCREMENT;";
                $sqlField = "ALTER TABLE ".$prefix."".$menuInfo['table_name']." RENAME TO ".$prefix."".$table_name;
                // 获取表的主键
                $pk_id = Db::connect($connect)->name($menuInfo['table_name'])->getPk();
                // 更新字段表中的主键列的字段名
                if($pk_id <> $data['pk']){
                    Field::field('field')->where('menu_id',$data['menu_id'])->where('field',$pk_id)->update(['field'=>$data['pk']]);
                }

                Db::connect($connect)->execute($sqlTable);
                Db::connect($connect)->execute($sqlField);
                // 页面类型为2，表单页面时
                if($data['page_type'] == 2){
                    $fields = self::getFieldList($prefix.$menuInfo['table_name'],$connect);
                    if(!in_array('name',$fields)){
                        Db::connect($connect)->execute("ALTER TABLE `".$prefix.$data['table_name']."` ADD `name` VARCHAR( 50 ) NOT NULL ");
                    }

                    if(!in_array('data',$fields)){
                        Db::connect($connect)->execute("ALTER TABLE `".$prefix.$data['table_name']."` ADD `data` TEXT NOT NULL AFTER `name`");
                    }
                }
            }else{
                // 如果表不存在，则重新创建新表
                $sql=" CREATE TABLE IF NOT EXISTS `".$prefix."".$table_name."` ( ";
                $sql .= '
					`'.$pk.'` int(10) NOT NULL AUTO_INCREMENT ,
					PRIMARY KEY (`'.$pk.'`)
					) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
				';
                Db::connect($connect)->execute($sql);
            }

        }
        return true;
    }

    public function delete($data){
        $menuInfo = Menu::find($data['menu_id']);
        $applicationInfo = Application::find($menuInfo['app_id']);

        $connect = $menuInfo['connect'] ? $menuInfo['connect'] : config('database.default');
        $prefix = config('database.connections.'.$connect.'.prefix');

        // 检查此菜单是否存在子菜单项，即阻止删除相关文件
        $count = \think\facade\Db::table('cd_sys_menu')
            ->where(['pid'=>$data['menu_id']])
            ->count();
        if($count > 0){
            throw new ValidateException('此菜单还拥有子菜单，请先删除子菜单，再试');
        }
        if($data['menu_id'] == 3){
            throw new ValidateException('首页菜单不可删除');
        }

        if($menuInfo['table_name'] && $menuInfo['create_table']){
            Db::connect($connect)->execute('DROP TABLE if exists '.$prefix.$menuInfo['table_name']);
        }

        try{
            //开始删除相关文件
            if(!empty($menuInfo['controller_name'])){
                $rootPath = app()->getRootPath();
                @unlink($rootPath.'/app/'.$applicationInfo['app_dir'].'/controller/'.$menuInfo['controller_name'].'.php');
                if($this->getSubFiles($rootPath.'/app/'.$applicationInfo['app_dir'].'/controller/'.explode('/',$menuInfo['controller_name'])[0])){
                    deldir($rootPath.'/app/'.$applicationInfo['app_dir'].'/controller/'.explode('/',$menuInfo['controller_name'])[0]);
                }

                @unlink($rootPath.'/app/'.$applicationInfo['app_dir'].'/model/'.$menuInfo['controller_name'].'.php');
                if($this->getSubFiles($rootPath.'/app/'.$applicationInfo['app_dir'].'/model/'.explode('/',$menuInfo['controller_name'])[0])){
                    deldir($rootPath.'/app/'.$applicationInfo['app_dir'].'/model/'.explode('/',$menuInfo['controller_name'])[0]);
                }

                @unlink($rootPath.'/app/'.$applicationInfo['app_dir'].'/validate/'.$menuInfo['controller_name'].'.php');
                if($this->getSubFiles($rootPath.'/app/'.$applicationInfo['app_dir'].'/validate/'.explode('/',$menuInfo['controller_name'])[0])){
                    deldir($rootPath.'/app/'.$applicationInfo['app_dir'].'/validate/'.explode('/',$menuInfo['controller_name'])[0]);
                }

                @unlink($rootPath.'/app/'.$applicationInfo['app_dir'].'/route/'.$this->getControllerName(strtolower($menuInfo['controller_name'])).'.php');

                //开始删除vue文件
                @unlink($rootPath.'/ui/src/api/'.$applicationInfo['app_dir'].'/'.strtolower($menuInfo['controller_name']).'.js');
                if($this->getSubFiles($rootPath.'/ui/src/api/'.$applicationInfo['app_dir'].'/'.explode('/',$menuInfo['controller_name'])[0])){
                    deldir($rootPath.'/ui/src/api/'.$applicationInfo['app_dir'].'/'.explode('/',$menuInfo['controller_name'])[0]);
                }

                deldir($rootPath.'/ui/src/views/'.$applicationInfo['app_dir'].'/'.strtolower($menuInfo['controller_name']));
            }
        }catch(\Exception $e){
            throw new ValidateException($e->getMessage());
        }

        return true;
    }

    //查看数据表是否存在
    public static function getTable($tableName,$connect){
        $list = Db::connect($connect)->query('show tables');
        $column = 'Tables_in_' . strtolower(config('database.connections.'.$connect.'.database'));
        foreach($list as $k=>$v){
            $array[] = $v[$column];
        }
        // 构建表名
        if(in_array(config('database.connections.'.$connect.'.prefix').$tableName,$array)){
            return true;
        }
    }

    //获取表的所有字段
    public static function getFieldList($tablename,$connect){
        $list = Db::connect($connect)->query('show columns from '.$tablename);
        foreach($list as $v){
            $arr[] = $v['Field'];
        }
        return $arr;
    }

    //判断当前目录有没有其他文件
    private function getSubFiles($filepath){
        $res = scandir($filepath);
        if(count($res) == 2){
            return true;
        }
    }

    //多级控制器 获取控制其名称
    private function getControllerName($controller_name){
        if($controller_name && strpos($controller_name,'/') > 0){
            $controller_name = explode('/',$controller_name)[1];
        }
        return $controller_name;
    }

    /**
     * 创建默认字段
     * @param $data
     * @return bool
     */
    private static function defaultField($data){
        $defaultList = Config::defaultFields();
        try{
            // 表实际已经存在的字段
            $menuInfo = Menu::find($data['menu_id']);
            $connect = $menuInfo['connect'] ? $menuInfo['connect'] : config('database.default');
            $prefix = config('database.connections.'.$connect.'.prefix');
            $fields = self::getFields($prefix.$menuInfo['table_name'],$connect);

            // 表单页面，系统表，不添加默认字段
            $not_add_tables = ['admin_user','admin_role','admin_log','admin_dept','admin_upload_config'];
            if($menuInfo['page_type'] != 2 && !in_array($menuInfo['table_name'],$not_add_tables)){
                // 字段表中重新检查一次，没有的给加上
                foreach ($defaultList as $k => $v){
                    if($v['primary'] && $v['field'] && $v['field'] != 'creater_id'){
                        $count = \think\facade\Db::table('cd_sys_field')->where(['menu_id'=>$data['menu_id'],'field'=>$v['field']])->count();
                        // 创建者字段不进行管理
                        if($count == 0){
                            $v['menu_id'] = $data['menu_id'];
                            // 只有原表中没有此字段才创建
                            if(!in_array($v['field'],$fields)){
                                \app\admin\controller\Sys\model\Field::create($v);
                            }
                        }
                    }
                }
                // 分别创建字段
                foreach ($defaultList as $key => $value){
                    if($value['field'] && !in_array($value['field'],$fields)){
                        if($value['create_table_field']){
                            $menuInfo = Menu::find($data['menu_id']);
                            if($menuInfo['page_type'] != 2){
                                if(!empty($value['default_value'])){
                                    if($value['type'] == 13){
                                        $value['default_value'] = '0';
                                    }
                                    $default = "DEFAULT '".$value['default_value']."'";
                                }else{
                                    $default = 'DEFAULT NULL';
                                }

                                if(in_array($value['datatype'],['datetime','longtext'])){
                                    $value['length'] = ' null';
                                }else{
                                    $value['length'] = "({$value['length']})";
                                }

                                $fields = self::getFields($prefix.$menuInfo['table_name'],$connect);
                                $sql="ALTER TABLE ".$prefix."{$menuInfo['table_name']} ADD {$value['field']} {$value['datatype']}{$value['length']} COMMENT '{$value['title']}' {$default} ";

                                Db::connect($connect)->execute($sql);

                                //判断是否存在索引  不存在则创建
                                if($value['indexdata']){
                                    if(!self::existsTableIndex($prefix.$menuInfo['table_name'],$value['field'],$connect)){
                                        Db::connect($connect)->execute("ALTER TABLE ".$prefix."{$menuInfo['table_name']} ADD ".$value['indexdata']." (  `".$value['field']."` )");
                                    }
                                }
                            }
                        }
                    }
                }

            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
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
    public static function existsTableIndex($tablename,$indexName,$connect){
        $list = Db::connect($connect)->query('show index from '.$tablename);
        foreach($list as $k=>$v){
            if($v['Column_name'] == $indexName){
                $status = true;
            }
        }
        return $status;
    }
}
