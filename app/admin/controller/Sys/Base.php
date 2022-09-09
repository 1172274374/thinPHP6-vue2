<?php
namespace app\admin\controller\Sys;

use think\App;
use think\View;
use app\admin\controller\Sys\service\Table as TableService;
use think\exception\ValidateException;
use app\admin\controller\Sys\build;
use app\admin\controller\Sys\model\Application;
use app\admin\controller\Sys\model\Field;
use app\admin\controller\Sys\service\Field as FieldService;
use app\admin\controller\Sys\model\Menu;
use app\admin\controller\Sys\model\Action;
use app\admin\controller\Admin;
use think\facade\Db;
use app\admin\controller\Sys\validate\Field as FieldValidate;
use app\admin\controller\Sys\validate\Action as ActionValidate;

class Base extends Admin{


    private $url = 'http://build.raiseinfo.cn';
    private $createMenu = '/code/Index/index';
    private $createApp  = '/code/Index/createApp';

    private $table;
    private $field;

    public function __construct(App $app, View $view, TableService $table,  FieldService $field)
    {
        parent::__construct($app, $view);
        $this->table = $table;
        $this->field = $field;
    }

    // 返回所有的页面地址
    public function getUrl(){
        $componment = \think\facade\Db::table('cd_sys_menu')
            ->field('menu_id,title,component_path')
            ->where('component_path','not null')
            ->select()->toArray();
        $data = [];
        foreach ($componment as $k => $v){
            $url = explode('/',$v['component_path']);
            if(count($url)>3){
                $temp = strtolower($url[0] . '/' . $url[1] . '.' . $url[2] . '/' . explode('.',$url[3])[0] . '.html');
            } else {
                $temp = strtolower($url[0] . '/' . $url[1] . '/' . explode('.',$url[2])[0] . '.html');
            }

            if(strlen($v['component_path'])>0){
                array_push($data,[
                    'menu_id'           =>  $v['menu_id'],
                    'title'             =>  $v['title'],
                    'component_path'    =>  $temp
                ]);
            }
        }

        return json(['status'=>200,'data'=>$data]);
    }

    //应用列表
    public function applicationList(){
        $limit  = $this->request->post('limit', 20, 'intval');
        $page   = $this->request->post('page', 1, 'intval');

        $res = Application::paginate(['list_rows'=>$limit,'page'=>$page]);
        $data['data'] = $res;
        $data['status'] = 200;
        return json($data);
    }

    //创建应用
    public function createApplication(){
        $data = $this->request->post();
        try{
            $applicationInfo = Application::create($data);
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200,'data'=>$applicationInfo]);
    }

    //修改应用
    public function updateApplication(){
        $data = $this->request->post();
        try{
            Application::update($data);
            $applicationInfo = Application::where(['app_id'=>$data['app_id']])->find();
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200,'data'=>$applicationInfo]);
    }

    //获取应用
    public function getApplicationInfo(){
        $data = $this->request->post('app_id');
        try{
            $res = Application::find($data);
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200,'data'=>$res]);
    }

    //生成应用
    public function buildApplication(){
        $data = $this->request->post('app_id');

        $info = Application::find($data);

        if(!$info['status']){
            throw new ValidateException('该应用禁止生成');
        }

        $rootPath = app()->getRootPath();

        $secrect = $this->getSecrect();

        if(empty($secrect['appid']) || empty($secrect['secrect'])){
            $this->error('请申请体验授权，或购买正式授权，并配置appid和秘钥');
        }

        $info['client_name'] = request()->server('HTTP_USER_AGENT');
        $info['OS'] = request()->server('HTTP_SEC_CH_UA_PLATFORM');
        $info['secrect'] = $secrect;
        $info['timestmp'] = time();

        $info['sign'] = md5(md5(json_encode($info,JSON_UNESCAPED_UNICODE).$secrect['secrect']));

        $res = $this->go_curl($this->url . $this->createApp,'post',json_encode($info));

        $res = json_decode($res,true);

        if($res['status'] == 411){
            throw new ValidateException($res['msg']);
        }

        foreach($res as $k=>$v){
            if(strpos($k,'index.html') > 0
                && file_get_contents($rootPath.$k)
                && file_get_contents($rootPath.$k) <> '欢迎使用xhadmin'){
                filePutContents(file_get_contents($rootPath.$k),$rootPath.$k,$type=2);
            }else{
                filePutContents($v,$rootPath.$k,2);
            }
        }

        return json(['status'=>200]);

    }

    //删除应用
    public function deleteApplication(){
        $data = $this->request->post();
        try{
            Application::destroy($data);
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200]);
    }

    //菜单列表
    function getMenuList(){
        $app_id = $this->request->post('app_id',1,'intval');
        foreach(config('database.connections') as $k =>$v){
            $connects[] = $k;
        }
        $data['status'] = 200;
        // 是否显示内置菜单
        $show_default_menu = config('admin_config.show_default_menu');
        $list = $this->getMenu($app_id,$show_default_menu);
        $data['list'] = $list;
        $data['listTree'] = _generateListTree($list,0,['menu_id','pid']);
        $data['defaultConnect'] = config('database.default');
        $data['connects'] = $connects;
        $data['tableList'] = $this->getTableList(config('database.default'));
        $data['app_list'] = Application::field('app_id,app_type,application_name')->select()->toArray();

        foreach($data['app_list'] as $k=>$v){
            $data['app_list'][$k]['url'] = (string)url('admin/Menu/index',['app_id'=>$v['app_id'],'app_type'=>$v['app_type']]);
        }

        $data['page_type_list'] = Config::page_type_list();
        return json($data);
    }

    //查看数据表是否存在
    public function getTable($tableName,$connect){
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

    //创建菜单
    public function createMenu(){
        $data = $this->request->post();

        try {
            \think\facade\Db::startTrans();
            $data['controller_name'] = $this->setControllerName($data['controller_name']);
            $connect = $data['connect'] ? $data['connect'] : config('database.default');
            if(self::getTable($data['table_name'],$connect)){
                if($data['create_table'] == 0){
                    // 如果存在重名表，则不创建表时可以创建菜单
                    $res = Menu::create($data);
                } else {
                    throw new ValidateException('数据库中已经存在此表，请设置不创建表');
                }
            } else {
                // 不存在重名表则，创建菜单
                $res = Menu::create($data);
            }

            if($res->menu_id && $data['table_name'] && $data['pk'] && $data['create_code']){
                $appInfo = Db::name('sys_application')->where('app_id',$data['app_id'])->find();
                foreach((Config::actionList()) as $key=>$val){
                    $val['menu_id'] = $res->menu_id;
                    if($appInfo['app_type'] == 1){
                        if($val['default_create']  && $data['page_type'] == 1  && !in_array($val['type'],[10,11])){
                            Action::create($val);
                        }
                        if($val['is_editable_table']  && $data['page_type'] == 3){
                            Action::create($val);
                        }
                    } else {
                        if($val['default_create'] && !in_array($val['type'],[10,11,12,61])){
                            Action::create($val);
                        }
                    }
                }
                // 将默认字段存储到field表中(原版本只考虑了主键字段，这里都考虑到，都添加到字段表)
                foreach((Config::defaultFields()) as $key=>$val){
                    $val['menu_id'] = $res->menu_id;
                    // 主键字段需要根据表的定义设置，如果是其他字段，则必须提供(所有者，系统自动添加)
                    $val['field'] = $val['field'] ? $val['field'] : $data['pk'];
                    if($val['primary'] && $data['page_type'] != 2){
                        Field::create($val);
                    }
                }
                if($data['page_type'] == 2){
                    Action::create(['name'=>$data['title'],'menu_id'=>$res->menu_id,'action_name'=>'index','type'=>14]);
                }
            }
            Menu::update(['menu_id'=>$res->menu_id,'sortid'=>$res->menu_id]);
            $menuInfo = Menu::where(['menu_id'=>$res->menu_id])->find();

            // 创建表
            if($data['create_table'] == 1){
                $this->table->create($menuInfo);
            }

            \think\facade\Db::commit();
        } catch (\Exception $e){
            \think\facade\Db::rollback();
            throw new ValidateException($e->getMessage());
        }
        return json(['status'=>200,'data'=>$menuInfo]);
    }

    //更新菜单
    public function updateMenu(){
        $data = $this->request->post();
        $data['controller_name'] = $this->setControllerName($data['controller_name']);

        try{
            \think\facade\Db::startTrans();
            $this->table->update($data);
            $res = Menu::update($data);
            if($res){
                // 如果页面是form表单则删除方法
                if($data['page_type'] == 2){
                    Action::where('type','<>',14)->where('menu_id',$data['menu_id'])->delete();
                    $configAction = Action::where('type',14)->where('menu_id',$data['menu_id'])->count();
                    if(!$configAction){
                        Action::create(['name'=>$data['title'],'menu_id'=>$data['menu_id'],'action_name'=>'index','type'=>14]);
                    }
                }
            }
            \think\facade\Db::commit();
        }catch(\Exception $e){
            \think\facade\Db::rollback();
            abort(501,$e->getMessage());
        }
        return json(['status'=>200]);
    }

    //方法列表直接修改操作
    public function updateMenuExt(){
        $data = $this->request->post();
        try{
            $res = Menu::update($data);
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200]);
    }


    //获取菜单信息
    public function getMenuInfo(){
        $data = $this->request->post('menu_id');
        try{
            $res = menu::find($data);
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200,'data'=>$res]);
    }

    //删除菜单
    public function deleteMenu(){
        $data = $this->request->post();

        // 检查此菜单是否存在子菜单项
        $count = \think\facade\Db::table('cd_sys_menu')
            ->where(['pid'=>$data['menu_id']])
            ->count();
        if($count > 0){
            throw new ValidateException('此菜单还拥有子菜单，请先删除子菜单，再试');
        }
        if($data['menu_id'] == 3){
            throw new ValidateException('首页菜单不可删除');
        }
        // 执行删除
        try{
            \think\facade\Db::startTrans();
            $this->table->delete($data);
            $res = Menu::destroy($data);
            if($res){
                Field::where($data)->delete();
                Action::where($data)->delete();
            }
            \think\facade\Db::commit();
        }catch(\Exception $e){
            \think\facade\Db::rollback();
            abort(501,$e->getMessage());
        }
        return json(['status'=>200]);
    }


    //复制菜单
    public function copyMenu(){
        $data = $this->request->post();
        if(empty($data['appid']) || empty($data['menu_id'])){
            $this->error('参数错误');
        }

        $menuInfo = Menu::where('menu_id',$data['menu_id'])->find()->toArray();

        $application =Application::find($data['appid']);

        $menuInfo['create_table'] = 0;
        if($application['app_type'] == 1){
            $menuInfo['component_path'] = $application['app_dir'].'/'.strtolower($menuInfo['controller_name']).'ext/index.vue';
            $menuInfo['controller_name'] = $menuInfo['controller_name'].'Ext';
        }
        $menuInfo['pid'] = 0;
        $menuInfo['app_id'] = $data['appid'];
        // 记录从哪儿复制过来的
        $menuInfo['copy_from'] = $data['menu_id'];
        unset($menuInfo['menu_id']);
        try{
            $res = Menu::create($menuInfo);
            $fieldList = Field::where(['menu_id'=>$data['menu_id']])->select()->toArray();
            if($fieldList){
                foreach($fieldList as $key=>$val){
                    $val['create_table_field'] = 0;
                    if(in_array($val['list_show'],[0,1]) && $application['app_type'] == 2){
                        $val['list_show'] = 0;
                    }
                    if(in_array($val['list_show'],[2,3,4]) && $application['app_type'] == 2){
                        $val['list_show'] = 1;
                    }
                    unset($val['id']);
                    $val['menu_id'] = $res->menu_id;
                    Field::create($val);
                }
            }

            $actionList = Action::where(['menu_id'=>$data['menu_id']])->select()->toArray();
            if($actionList){
                foreach($actionList as $key=>$val){
                    if(in_array($val['type'],[1,2,3,4,5,6,7,8,9,14])){
                        unset($val['id']);
                        $val['menu_id'] = $res->menu_id;
                        Action::create($val);
                    }
                }
            }
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }

        return json(['status'=>200]);

    }

    // 同步复制菜单
    public function synCopy(){
        $data = $this->request->post();
        if(empty($data['appid']) || empty($data['menu_id'])){
            $this->error('参数错误');
        }

        // 查询当前应用的信息
        $application =Application::find($data['appid']);

        // 当前菜单的信息
        $currentMenuInfo = Menu::where('menu_id',$data['menu_id'])->find()->toArray();
        $currentFieldArray = Field::where('menu_id',$data['menu_id'])->column('field');
        // 检查当前菜单是否是复制来的
        if(empty($currentMenuInfo['copy_from'])){
            throw new ValidateException('当前菜单不是复制而来的菜单');
        }
        // 被拷贝的菜单的信息
        $copyedMenuInfo = Menu::where('menu_id',$currentMenuInfo['copy_from'])->find()->toArray();
        $copyedFieldArray = Field::where('menu_id',$currentMenuInfo['copy_from'])->column('field');

        // 检查是否有新字段，如果有则复制过来
        $synCount = 0;
        foreach ($copyedFieldArray as $k => $v){
            // 拷贝的字段，不在当前菜单的字段中，则需要向当前菜单对应的字段添加字段定义
            if(!in_array($v,$currentFieldArray)){
                // 从拷贝表中查询此字段
                $newField = Field::where(['menu_id'=>$copyedMenuInfo['menu_id'],'field'=>$v])->find()->toArray();
                // 修改为不创建字段
                $newField['create_table_field'] = 0;
                // 应用类型为API应用时，字段为0，1，设置为0
                if(in_array($newField['list_show'],[0,1]) && $application['app_type'] == 2){
                    $newField['list_show'] = 0;
                }
                // 应用类型为API应用时，字段为2，3，4，设置为1
                if(in_array($newField['list_show'],[2,3,4]) && $application['app_type'] == 2){
                    $newField['list_show'] = 1;
                }
                // 字段改为当前菜单的编号
                $newField['menu_id'] = $currentMenuInfo['menu_id'];
                // 主键取消，自动生成
                unset($newField['id']);
                // 创建新菜单
                Field::create($newField);
                $synCount ++;
            }
        }
        if($synCount > 0){
            return json(['status'=>200,'msg'=>'同步了' . $synCount . '个字段']);
        } else {
            return json(['status'=>200,'msg'=>'执行了检查，没有可同步的字段']);
        }
    }

    //菜单字段列表
    public function fieldList(){
        $limit  = $this->request->post('limit', 20, 'intval');
        $page   = $this->request->post('page', 1, 'intval');
        $menu_id = $this->request->post('menu_id','','intval');

        $res = Field::where(['menu_id'=>$menu_id])->order('sortid asc')->paginate(['list_rows'=>$limit,'page'=>$page]);

        $data['status'] = 200;
        $data['data'] = $res;
        $data['typeField']  = Config::fieldList();
        $data['itemList']  = Config::itemList();
        $data['menu'] = Menu::where('menu_id',$menu_id)->find();
        return json($data);
    }

    // 导出菜单字段到Excel
    public function exportExcel(){
        $type_name = $this->request->post('type_name','','');
        $menu_id = $this->request->post('menu_id','','intval');

        if($type_name == 'field'){
            $fields = "title AS '字段标题',`field` AS '字段名称',b.`name` AS '字段类型',";
            $fields .= "IF(list_show = 1,'不显示',IF(list_show = 2,'隐藏','显示')) AS '显示状态',";
            $fields .= "IF(search_type = 0,'不搜索',IF(search_type = 1,'=','Like')) AS '搜索模式',";
            $fields .= "IF(create_table_field = 0,'否','是') AS '创建字段',";
            $fields .= "IF(post_status = 0,'否','是') AS '录入状态',`datatype` AS '数据类型',a.`item_config` AS '字段配置',`sql`";
            $res = \think\facade\Db::name('sys_field')
                ->alias('a')
                ->leftJoin(['cd_sys_field_type'=>'b'],'a.type = b.type')
                ->fieldRaw($fields)
                ->where(['menu_id'=>$menu_id])
                ->order('sortid asc')
                ->select()
                ->toArray();
        }
        if($type_name == 'action'){
            $res = Action::where(['menu_id'=>$menu_id])->order('sortid asc')->select()->toArray();
        }

        // 初始化对象
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // 获取活动工作簿
        $sheet = $spreadsheet->getActiveSheet();

        if($type_name == 'field'){
            // 设置表格头（即excel表格的第一行）
            $sheet->getCell('A1')->setValue('字段标题');
            $sheet->getCell('B1')->setValue('字段名称');
            $sheet->getCell('C1')->setValue('字段类型');
            $sheet->getCell('D1')->setValue('显示状态');
            $sheet->getCell('E1')->setValue('搜索模式');
            $sheet->getCell('F1')->setValue('创建字段');
            $sheet->getCell('G1')->setValue('录入状态');
            $sheet->getCell('H1')->setValue('数据类型');
            $sheet->getCell('I1')->setValue('字段配置');
            $sheet->getCell('J1')->setValue('sql');
            foreach ($res as $k => $v){
                $sheet->setCellValueByColumnAndRow(1, ($k + 2), $v['字段标题']);
                $sheet->setCellValueByColumnAndRow(2, ($k + 2), $v['字段名称']);
                $sheet->setCellValueByColumnAndRow(3, ($k + 2), $v['字段类型']);
                $sheet->setCellValueByColumnAndRow(4, ($k + 2), $v['显示状态']);
                $sheet->setCellValueByColumnAndRow(5, ($k + 2), $v['搜索模式']);
                $sheet->setCellValueByColumnAndRow(6, ($k + 2), $v['创建字段']);
                $sheet->setCellValueByColumnAndRow(7, ($k + 2), $v['录入状态']);
                $sheet->setCellValueByColumnAndRow(8, ($k + 2), $v['数据类型']);
                $sheet->setCellValueByColumnAndRow(9, ($k + 2), $v['字段配置']);
                $sheet->setCellValueByColumnAndRow(10, ($k + 2), $v['sql']);
            }
        }
        if($type_name == 'action'){
            // 设置表格头（即excel表格的第一行）
            $sheet->setCellValue('A1','操作名称');
            $sheet->setCellValue('B1','方法名称');
            $sheet->setCellValue('C1','按钮样式');
            $sheet->setCellValue('D1','按钮图标');
            foreach ($res as $k => $v){
                $sheet->setCellValueByColumnAndRow(1, ($k + 2), $v['name']);
                $sheet->setCellValueByColumnAndRow(2, ($k + 2), $v['action_name']);
                $sheet->setCellValueByColumnAndRow(3, ($k + 2), $v['button_color']);
                $sheet->setCellValueByColumnAndRow(4, ($k + 2), $v['icon']);
            }
        }

        $filename = 'Field' . date('Ymd_His', time()) . '.xls';

        header('Access-Control-Allow-Origin:*');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $res_excel = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        ob_start();
        $res_excel->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        $data = ['filename' => $filename, 'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)];
        return json(['status'=>200,'data'=>$data]);
    }

    //创建字段
    public function createField(){
        $data = $this->request->post();

        $this->validate($data,FieldValidate::class);

        $data['item_config'] = getItemData($data['item_config']);
        $data['filter_condition'] = getItemData($data['filter_condition']);
        $data['other_config'] = json_encode($data['other_config']);
        $data['validate'] = implode(',',$data['validate']);

        foreach(Config::fieldList() as $v){
            if($v['type'] == $data['type'] && empty($data['belong_table'])){
                $search_status = $v['search'];
            }
        }

        $data['search_type'] = $search_status;
        try{
            \think\facade\Db::startTrans();
            $this->field->create($data);
            $res = Field::create($data);
            if($res->id){
                Field::update(['id'=>$res->id,'sortid'=>$res->id]);
            }
            $fieldInfo = Field::where(['id'=>$res->id])->find();
            \think\facade\Db::commit();
        }catch(\Exception $e){
            \think\facade\Db::rollback();
            abort(501,$e->getMessage());
        }
        return json(['status'=>200,'data'=>$fieldInfo]);
    }

    //更新字段
    public function updateField(){
        $data = $this->request->post();

        $this->validate($data,FieldValidate::class);

        $data['item_config'] = !empty($data['item_config']) ? getItemData($data['item_config']) : null;
        $data['filter_condition'] = !empty($data['filter_condition']) ? getItemData($data['filter_condition']) : null;
        $data['other_config'] = json_encode($data['other_config']);
        $data['validate'] = implode(',',$data['validate']);

        foreach(Config::fieldList() as $v){
            if($v['type'] == $data['type'] && empty($data['belong_table'])){
                $search_status = $v['search'];
            }
        }

        // 如果设置的值和默认值相同，则用默认值，如果设置值和默认值不同，则使用设置值
        $data['search_type'] = $data['search_type'] == $search_status ? $search_status : $data['search_type'];

        $menuInfo = Menu::where(['menu_id'=>$data['menu_id']])->find();

        try{
            \think\facade\Db::startTrans();
            if($menuInfo['page_type'] != 2){
                $this->field->update($data);
            }
            Field::update($data);
            $fieldInfo = Field::where(['id'=>$data['id']])->find();
            \think\facade\Db::commit();
        }catch(\Exception $e){
            \think\facade\Db::rollback();
            abort(501,$e->getMessage());
        }
        return json(['status'=>200,'data'=>$fieldInfo]);
    }

    //方法列表直接修改操作
    public function updateFieldExt(){
        $data = $this->request->post();
        $menuInfo = Menu::where(['menu_id'=>$data['menu_id']])->find();
        try{
            \think\facade\Db::startTrans();
            if($menuInfo['page_type'] != 2){
                $this->field->update($data);
            }
            $res = Field::update($data);
            \think\facade\Db::commit();
        }catch(\Exception $e){
            \think\facade\Db::rollback();
            abort(501,$e->getMessage());
        }
        return json(['status'=>200]);
    }

    //获取字段信息
    public function getFieldInfo(){
        $data = $this->request->post();
        try{
            $res = Field::where($data)->find();
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        $res['validate'] = explode(',',$res['validate']);
        $res['item_config'] = json_decode($res['item_config'],true);
        $res['filter_condition'] = json_decode($res['filter_condition'],true);
        return json(['status'=>200,'data'=>$res]);
    }

    //删除字段
    public function deleteField(){
        $data = $this->request->post();
        $pk = Db::name(Menu::where('menu_id',$data['menu_id'])->value('table_name'))->getPk();
        $fieldList = Field::field('id,field')->where($data)->select()->toArray();
        $ids = [];
        foreach($fieldList as $v){
            if($pk <> $v['field']){
                array_push($ids,$v['id']);
            }else{
                $pk_status = true;
            }
        }
        try{
            \think\facade\Db::startTrans();
            $this->field->delete($data);
            Field::where('id','in',$ids)->delete();
            \think\facade\Db::commit();
        }catch(\Exception $e){
            \think\facade\Db::rollback();
            abort(501,$e->getMessage());
        }
        return json(['status'=>200,'pk_status'=>$pk_status]);
    }

    //方法列表
    public function actionList(){
        $limit  = $this->request->post('limit', 20, 'intval');
        $page   = $this->request->post('page', 1, 'intval');
        $menu_id = $this->request->post('menu_id','','intval');

        $res = Action::where(['menu_id'=>$menu_id])->order('sortid asc')->paginate(['list_rows'=>$limit,'page'=>$page]);
        $data['data'] = $res;
        $data['status'] = 200;
        $data['actionList'] = Config::actionList();
        $data['menu'] = Menu::where('menu_id',$menu_id)->find();
        return json($data);
    }

    //获取提交字段
    public function getPostField(){
        $menu_id = $this->request->post('menu_id');

        $menuInfo = Menu::find($menu_id);

        $list = Field::field('type,field,title')
            ->where('menu_id',$menu_id)
            ->where('post_status',1)
            ->order('sortid asc')
            ->select()
            ->toArray();

        $pk = Db::connect($menuInfo['connect'])->name($menuInfo['table_name'])->getPk();

        $model_fields = array_merge([['field'=>$pk,'title'=>'编号']],$list);

        $tableList = Menu::where('table_name','<>','')
            ->where('app_id',$menuInfo['app_id'])
            ->field('controller_name')
            ->select()
            ->toArray();

        $with_join = [];
        $actionList = Action::where('menu_id',$menu_id)->select();
        foreach($actionList as $v){
            if($v['with_join'] && in_array($v['type'],[2,3])){
                foreach(json_decode($v['with_join'],true) as $n){
                    $n['fields'] = $this->getExtendFields($n);
                    foreach($n['fields'] as $m){
                        array_push($with_join, $m);
                    }
                }
            }
        }

        $newWith = [];

        foreach ($with_join as $key=>$v) {
            if(isset($newWith[$v['field']]) == false){
                $newWith[$v['field']] = $v;
            }
        }

        foreach($newWith as $k=>$v){
            unset($newWith[$k]['belong_table']);
            unset($newWith[$k]['table_name']);
        }

        $tab_fields = array_merge($list,$newWith);
        return json([
            'status'=>200,
            'data'=>$list,
            'model_fields'=>$model_fields,
            'tab_fields'=>$tab_fields,
            'tableList'=>$tableList,
            'sms_list'=>Config::sms_list()]);
    }

    //创建方法
    public function createAction(){
        $data = $this->request->post();

        $this->validate($data,ActionValidate::class);

        if($data['with_join']){
            foreach($data['with_join'] as $k=>$v){
                $menuInfo = Menu::field('connect,table_name')->where('controller_name',$v['relative_table'])->find();
                $data['with_join'][$k]['table_name'] = $menuInfo['table_name'];
                $data['with_join'][$k]['connect'] = $menuInfo['connect'];
            }
        }

        $data['list_filter'] = getItemData($data['list_filter']);
        $data['tab_config'] = getItemData($data['tab_config']);
        $data['with_join'] = getItemData($data['with_join']);
        $data['other_config'] = json_encode($data['other_config']);
        $data['sql'] = sql_replace($data['sql']);

        $data['fields'] = implode(',',$data['fields']);


        if(in_array($data['type'],[2,3,4,5,6,7,8,9,10,11,15,16,17,18])){
            $data['group_button_status'] = 1;
        }

        if(in_array($data['type'],[2,3])){
            $data['dialog_size'] = '600px';
        }

        if(in_array($data['type'],[3,4])){
            $data['list_button_status'] = 1;
        }

        try{
            $count = Action::where('menu_id',$data['menu_id'])->where('action_name',$data['action_name'])->count();
            if($count >0){
                throw new ValidateException ('方法名已经存在');
            }
            $res = Action::create($data);
            if($res->id){
                Action::update(['id'=>$res->id,'sortid'=>$res->id]);
            }
            $actionInfo = Action::where(['id'=>$res->id])->find();
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200,'data'=>$actionInfo]);
    }

    //快速创建方法
    public function quckCreateAction(){
        $data = $this->request->post('actions');
        $menu_id = $this->request->post('menu_id');
        foreach($data as $key=>$val){
            foreach((Config::actionList()) as $k=>$v){
                if($val == $v['type']){
                    $v['menu_id'] = $menu_id;
                    if(!in_array($v['action_name'],Action::where('menu_id',$menu_id)->column('action_name'))){
                        Action::create($v);
                    }else{
                        $exits_status = true;
                    }
                }
            }
        }
        return json(['status'=>200,'exits_status'=>true]);
    }

    //更新方法
    public function updateAction(){
        $data = $this->request->post();

        $this->validate($data,ActionValidate::class);

        if($data['with_join']){
            foreach($data['with_join'] as $k=>$v){
                $menuInfo = Menu::field('connect,table_name')->where('controller_name',$v['relative_table'])->find();
                $data['with_join'][$k]['table_name'] = $menuInfo['table_name'];
                $data['with_join'][$k]['connect'] = $menuInfo['connect'];
            }
        }

        $data['list_filter'] = getItemData($data['list_filter']);
        $data['tab_config'] = getItemData($data['tab_config']);
        $data['with_join'] = getItemData($data['with_join']);
        $data['fields'] = $data['fields'] ? implode(',',$data['fields']) : '';
        $data['other_config'] = json_encode($data['other_config']);
        $data['sql'] = sql_replace($data['sql']);

        // 如果更新的此方法为添加方法，则查询是否有更新方法，如果有，则把tab_config的配置复制过去。
        // 如果更新的此方法为更新方法，则忽略此操作
        if($data['type'] == 2){
            $update_action = Action::where(formatWhere(['menu_id'=>$data['menu_id'],'type'=>3]))->select()->toArray();
            if(count($update_action)>0){
                foreach ($update_action as $key => $value){
                    Action::update(['id' => $value['id'],'tab_config' => $data['tab_config']]);
                }
            }
        }

        try{
            $res = Action::update($data);
            $actionInfo = Action::where(['id'=>$data['id']])->find();
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200,'data'=>$actionInfo]);
    }

    //方法列表直接修改操作
    public function updateActionExt(){
        $data = $this->request->post();
        try{
            $res = Action::update($data);
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200]);
    }

    //获取方法信息
    public function getActionInfo(){
        $data = $this->request->post();
        try{
            $res = Action::where($data)->find();
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        if($res['list_filter']){
            $res['list_filter'] = json_decode($res['list_filter'],true);
        }
        if($res['tab_config']){
            $res['tab_config'] = json_decode($res['tab_config'],true);
        }

        if($res['with_join']){
            $res['with_join'] = json_decode($res['with_join'],true);
        }

        $list = Field::where('menu_id',$data['menu_id'])->where('post_status',1)->column('field');

        $fields = explode(',',$res['fields']);
        foreach($fields as $key=>$val){
            if(!in_array($val,$list)){
                unset($fields[$key]);
            }
        }
        $res['fields'] = array_values($fields);

        return json(['status'=>200,'data'=>$res]);
    }

    //删除方法
    public function deleteAction(){
        $data = $this->request->post();

        $list = Action::where($data)->field('action_name')->select()->toArray();

        $rootPath = app()->getRootPath();

        $menu = Menu::find($data['menu_id']);
        $application = Application::find($menu['app_id']);

        foreach($list as $v){
            if($menu['controller_name'] && $v['action_name']){
                @unlink($rootPath.'/ui/src/views/'.$application['app_dir'].'/'.strtolower($menu['controller_name']).'/'.$v['action_name'].'.vue');
            }
        }

        try{
            $res = Action::where($data)->delete();
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200]);
    }

    //拖动排序
    public function updateFieldSort(){
        $postField = 'currentId,preId,nextId,menu_id';
        $data = $this->request->only(explode(',',$postField),'post',null);

        if(!empty($data['preId'])){
            $pre = Field::where('id',$data['preId'])->where('menu_id',$data['menu_id'])->value('sortid');
        }
        if(!empty($data['nextId'])){
            $next = Field::where('id',$data['nextId'])->where('menu_id',$data['menu_id'])->value('sortid');
        }

        $current = Field::where('id',$data['currentId'])->where('menu_id',$data['menu_id'])->value('sortid');

        if($current > $pre){
            $sortid = $next;
        }else{
            $sortid = $pre;
        }

        if(empty($pre)){
            $pre = $next - 1;
            $sortid = $next;
        }
        if(empty($next)){
            $next = $pre + 1;
            $sortid = $pre;
        }
        try{
            if($current > $pre){
                Field::field('sortid')->where('sortid','between',[$pre+1,$current-1])->where('menu_id',$data['menu_id'])->inc('sortid',1)->update();
            }
            if($current < $pre){
                Field::field('sortid')->where('sortid','between',[$current+1,$next-1])->where('menu_id',$data['menu_id'])->dec('sortid',1)->update();
            }
            Field::field('sortid')->where('id',$data['currentId'])->where('menu_id',$data['menu_id'])->update(['sortid'=>$sortid]);
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }

        return json(['status'=>200,'pre'=>$pre]);
    }

    //拖动排序
    public function updateActionSort(){
        $postField = 'currentId,preId,nextId,menu_id';
        $data = $this->request->only(explode(',',$postField),'post',null);

        if(!empty($data['preId'])){
            $pre = Action::where('id',$data['preId'])->where('menu_id',$data['menu_id'])->value('sortid');
        }
        if(!empty($data['nextId'])){
            $next = Action::where('id',$data['nextId'])->where('menu_id',$data['menu_id'])->value('sortid');
        }

        $current = Action::where('id',$data['currentId'])->where('menu_id',$data['menu_id'])->value('sortid');

        if($current > $pre){
            $sortid = $next;
        }else{
            $sortid = $pre;
        }

        if(empty($pre)){
            $pre = $next - 1;
            $sortid = $next;
        }
        if(empty($next)){
            $next = $pre + 1;
            $sortid = $pre;
        }
        try{
            if($current > $pre){
                Action::where('sortid','between',[$pre+1,$current-1])->where('menu_id',$data['menu_id'])->inc('sortid',1)->update();
            }
            if($current < $pre){
                Action::field('sortid')->where('sortid','between',[$current+1,$next-1])->where('menu_id',$data['menu_id'])->dec('sortid',1)->update();
            }
            Action::field('sortid')->where('id',$data['currentId'])->where('menu_id',$data['menu_id'])->update(['sortid'=>$sortid]);
        }catch(\Exception $e){
            abort(501,$e->getMessage());
        }
        return json(['status'=>200,'pre'=>$pre]);
    }

    //字段选项配置，验证规则配置
    public function configList(){
        $ruleList = Config::ruleList();
        $propertyField = Config::propertyField();
        return json(['status'=>200,'ruleList'=>$ruleList,'propertyField'=>$propertyField]);
    }


    //数据库table列表
    public function getTables(){
        $connect = $this->request->post('connect',config('database.default'),'strval');
        return json(['status'=>200,'data'=>$this->getTableList($connect)]);
    }

    //用过菜单id获取所有数据表
    public function getTablesByMenuId(){
        $menu_id = $this->request->post('menu_id');
        if(!$menu_id){
            $this->error('菜单ID不能为空');
        }
        $app_id = Menu::where('menu_id',$menu_id)->value('app_id');
        $tableList = Menu::where('app_id',$app_id)->where('table_name','<>','')->field('table_name,title')->select();
        return json(['status'=>200,'data'=>$tableList]);
    }

    //数据库table列表
    private function getTableList($connect){
        $list = Db::connect($connect)->query('show tables');
        $column = strtolower(config('database.connections.'.$connect.'.database'));
        $prefix = config('database.connections.'.$connect.'.prefix');
        foreach($list as $k=>$v){
            $tableList[] = str_replace($prefix,'',$v['Tables_in_'.$column]);
        }
        $no_show_table = [
            'cd_admin_config',
            'cd_admin_dept',
            'cd_admin_file',
            'cd_admin_log',
            'cd_admin_role',
            'cd_admin_token',
            'cd_admin_upload_config',
            'cd_admin_user',
            'cd_sys_action',
            'cd_sys_action_type',
            'cd_sys_application',
            'cd_sys_field',
            'cd_sys_field_property',
            'cd_sys_field_type',
            'cd_sys_menu',
        ];
        foreach($tableList as $key=>$val){
            if(in_array($val,$no_show_table)){
                unset($tableList[$key]);
            }
        }
        return array_values($tableList);
    }

    //根据表名获取字段列表
    public function getTableFields(){
        $controller_name = $this->request->post('controller_name');
        if(!$controller_name){
            $this->error('数据表不能为空');
        }

        $menuInfo = Menu::where('controller_name',$controller_name)->find();

        $list = Db::connect($menuInfo['connect'])->query('show full columns from '.config('database.connections.'.$menuInfo['connect'].'.prefix').$menuInfo['table_name']);

        return json(['status'=>200,'filedList'=>$list]);
    }

    //获取菜单列表
    private function getMenu($app_id,$show_default_menu){
        $field = 'menu_id,pid,title,controller_name,create_code,create_table,table_name,status,sortid,page_type,home_show,home_sort,copy_from';
        if($show_default_menu == 1){
            // 显示内置菜单
            $list = Menu::field($field)
                ->where(['app_id'=>$app_id])
                ->order('sortid asc')
                ->select()->toArray();
        } else {
            // 不显示内置菜单
            $list = Menu::field($field)
                ->where(['app_id'=>$app_id])
                ->whereRaw('menu_id not in (1,3,4) and pid not in (1,2,4)')
                ->order('sortid asc')
                ->select()->toArray();
        }
        return $list;
    }

    //获取上传配置列表
    public function getUploadList(){
        $appid = $this->request->post('app_id');
        $app_type = Application::where('app_id',$appid)->value('app_type');
        $list =	Db::name('admin_upload_config')->field('id,title')->select()->toArray();
        return json(['status'=>200,'data'=>$list,'app_type'=>$app_type]);
    }

    //生成
    public function create(){
        $menu_id = $this->request->post('menu_id');

        if($this->createCode($menu_id)){
            return json(['status'=>200]);
        }
    }

    //生成
    private function createCode($menu_id){
        $menuInfo = Menu::find($menu_id)->toArray();

        if(!$menuInfo['create_code']){
            $this->error('该菜单禁止生成');
        }

        $fieldList = Field::where('menu_id',$menu_id)->order('sortid asc')->select()->toArray();
        $actionList = Action::where('menu_id',$menu_id)->order('sortid asc')->select()->toArray();

        $application = Application::where('app_id',$menuInfo['app_id'])->find()->toArray();

        $pk = Db::connect($menuInfo['connect'])->name($menuInfo['table_name'])->getPk();

        $data['fieldList'] = $fieldList;
        $data['actionList'] = $actionList;
        $data['application'] = $application;
        $data['pk'] = $pk;
        $data['menuInfo'] = $menuInfo;
        $data['actions'] = Config::actionList();
        $data['extend'] = $this->getExtend($actionList);
        $data['comment'] = config('rds.comment');

        $secrect = $this->getSecrect();

        if(empty($secrect['appid']) || empty($secrect['secrect'])){
            $this->error('请申请体验授权，或购买正式授权，并配置appid和秘钥');
        }
        $data['client_name'] = request()->server('HTTP_USER_AGENT');
        $data['OS'] = request()->server('HTTP_SEC_CH_UA_PLATFORM');
        $data['secrect'] = $secrect;
        $data['timestmp'] = time();

        $data['sign'] = md5(md5(json_encode($data,JSON_UNESCAPED_UNICODE).$secrect['secrect']));

        $res = $this->go_curl($this->url . $this->createMenu,'post',json_encode($data));

        $ret = $res;

        $res = json_decode($res,true);

        if($res['status'] == 411){
            throw new ValidateException($res['msg']);
        }

        if(!is_array($res['model'])){
            halt($ret);
        }

        $rootPath = app()->getRootPath();

        try{
            foreach($res as $key=>$val){
                if($key == 'view'){
                    foreach($val as $v){
                        filePutContents($v['content'],$rootPath.'/ui/src/views/'.$v['path'],2);
                    }
                }else if($key == 'jsapi'){
                    filePutContents($val['content'],$rootPath.'/ui/src/api/'.$val['path'],2);
                }else if($key == 'route'){
                    filePutContents($val['content'],$rootPath.'/'.$val['path'],3);
                }else{
                    filePutContents($val['content'],$rootPath.'/'.$val['path'],1);
                }
            }
        }catch(\Exception $e){
            throw new ValidateException($e->getMessage());
        }

        return true;
    }

    //根据表生成
    public function createByTable(){
        $data = $this->request->post();
        $connect = $data['connect'];
        $prefix = config('database.connections.'.$connect.'.prefix');
        // 主键
        $pk = Db::connect($connect)->name($data['table_name'])->getPk();
        // 字段信息列表
        $list = Db::connect($connect)->query('show full columns from '.$prefix.$data['table_name']);
        // 字段名称列表
        $fieldList = [];
        foreach ($list as $k => $v){
            if($v['Field'] != $pk){
                $fieldList[] = $v['Field'];
            }
        }
        // 表注释作为菜单名称
        $dll = Db::connect($connect)->query('SHOW CREATE TABLE '.$prefix.$data['table_name']);
        $tableComment = str_replace("'","",explode('COMMENT=',$dll[0]['Create Table']));
        if(count($tableComment)>1){
            $menu_title = $tableComment[1];
        } else {
            $menu_title = '新建菜单';
        }

        if($pk){
            $menuInfo = [
                'controller_name' => $this->setControllerNameByTableName($data['table_name']),
                'title' => $menu_title,
                'pk'=>$pk,
                'table_name'=>$data['table_name'],
                'create_code'=>1,
                'status'=>1,
                'create_table'=>0,
                'component_path'=>'admin/'.str_replace('_','/',strtolower($data['table_name'])).'/index.vue',
                'app_id'=>$data['app_id'],
                'connect'=>$connect,
                'page_type'=>$data['page_type']
            ];

            try{
                Db::startTrans();

                $res = Menu::create($menuInfo);

                Menu::update(['menu_id'=>$res->menu_id,'sortid'=>$res->menu_id]);
                // 默认字段
                foreach((Config::defaultFields()) as $key=>$val){
                    $val['menu_id'] = $res->menu_id;
                    // 主键字段需要根据表的定义设置，如果是其他字段，则必须提供(所有者，系统自动添加)
                    $val['field'] = $val['field'] ? $val['field'] : $pk;
                    if($val['primary'] && $data['page_type'] != 2){
                        if(!in_array($val['field'],$fieldList)){
                            Field::create($val);
                        }
                    }
                }
                $this->table->create($res);

                $actionInfo = Config::actionList();
                foreach($actionInfo as $key=>$val){
                    if($val['default_create'] && !in_array($val['type'],[10,11])){
                        $actionInfo[$key]['menu_id'] = $res->menu_id;
                        $actionInfo[$key]['sortid'] = $key+1;
                    }else{
                        unset($actionInfo[$key]);
                    }
                    if($data['app_type'] == 2 && $val['type'] == 12){
                        unset($actionInfo[$key]);
                    }
                }

                (new Action)->saveAll($actionInfo);

                $fieldInfo = [];
                foreach($list as $k=>$v){
                    if($v['Field'] != $pk){
                        $fieldInfo[$k]['menu_id'] = $res->menu_id;
                        $fieldInfo[$k]['title'] = $v['Comment'] ? $v['Comment'] : $v['Field'];
                        $fieldInfo[$k]['field'] = $v['Field'];
                        $fieldInfo[$k]['type'] = 1;
                        $fieldInfo[$k]['list_type'] = 1;
                        $fieldInfo[$k]['list_show'] = 2;
                        $fieldInfo[$k]['search_type'] = 0;
                        $fieldInfo[$k]['post_status'] = 1;
                        $fieldInfo[$k]['create_table_field'] = 0;
                        $fieldInfo[$k]['sortid'] = $k+1;
                        $fieldInfo[$k]['datatype'] = preg_split("/\(.*\)+/", $v['Type'])[0];
                        preg_match_all("/\((.*)\)/", $v['Type'],$all);
                        $fieldInfo[$k]['length'] = $all[1][0];
                    }
                }

                (new Field)->saveAll($fieldInfo);

                Db::commit();
            }catch(\Exception $e){
                Db::rollback();
                throw new ValidateException ($e->getMessage());
            }
        }else{
            throw new ValidateException ('数据表主键不能为空');
        }
        return json(['status'=>200]);
    }

    //获取关联表信息
    private function getExtend($actionList){
        $with_join = [];
        foreach($actionList as $v){
            if($v['with_join'] && in_array($v['type'],[2,3,5,11])){
                foreach(json_decode($v['with_join'],true) as $n){
                    $n['action_type'] = $v['type'];
                    $n['fields'] = $this->getExtendFields($n);
                    array_push($with_join, $n);
                }
            }
        }

        return $with_join;
    }


    private function getExtendFields($val){

        $menuInfo = Menu::field('menu_id,table_name')->where('controller_name',$val['relative_table'])->find();
        $fieldList = Field::where('menu_id',$menuInfo['menu_id'])->order('sortid asc')->select()->toArray();

        foreach($fieldList as $k=>$v){
            $fieldList[$k]['belong_table'] = $val['relative_table'];
            $fieldList[$k]['table_name'] = $menuInfo['table_name'];
            if(!in_array($v['field'],$val['fields'])){
                unset($fieldList[$k]);
            }
        }
        return $fieldList;
    }

    //获取控制器名称
    public function setControllerName($controller_name){
        if(strpos($controller_name,'/') > 0){
            $arr = explode('/',$controller_name);
            $controller_name = ucfirst($arr[0]).'/'.ucfirst($arr[1]);
        }else{
            $controller_name = ucfirst($controller_name);
        }
        return str_replace('_','',$controller_name);
    }

    // 根据表名获得控制名称
    public function setControllerNameByTableName($table_name){
        if(strpos($table_name,'_') > 0){
            $arr = explode('_',$table_name);
            $controller_name = ucfirst($arr[0]).'/'.ucfirst($arr[1]);
        }else{
            $controller_name = ucfirst($table_name);
        }
        return $controller_name;
    }

    //获取应用名 以及数据表名称
    public function getAppInfo(){
        $controller_name = $this->request->post('controller_name');
        $data['table_name'] = $this->getTableName($controller_name);
        $data['pk'] =  $data['table_name'] ? $data['table_name'].'_id' : '';
        $data['app_name'] = app('http')->getName();
        $data['status'] = 200;
        return json($data);
    }


    //获取应用名 以及数据表名称
    public function getAppType(){
        $appid = $this->request->post('app_id');
        $data['status'] = 200;
        $data['data'] = Application::where('app_id',$appid)->value('app_type');
        return json($data);
    }

    private function getTableName($controller_name){
        if($controller_name && strpos($controller_name,'/') > 0){
            $controller_name = explode('/',$controller_name)[1];
        }
        return $controller_name;
    }


    //获取秘钥信息
    private function getSecrect(){
        $info['appid']      = config('rds.appid');
        $info['secrect']    = config('rds.secrect');
        return $info;
    }


    // 生成API文档
    public function getDoc(){
        $app_id = $this->request->post('app_id');
        $appInfo = \think\facade\Db::name('sys_application')->where(['app_id'=>$app_id])->find();
        $rootPath = app()->getRootPath();
        $i = $rootPath . 'app\\' . $appInfo['app_dir'];
        $o = $rootPath . 'public\\' . $appInfo['app_dir'] . '\\doc';
        $cmd = 'apidoc -i ' . $i . ' -o ' . $o;
        exec($cmd, $output, $return);
        if(count($output)>0){
            $msg = json_decode($output[0],true);
            return json(['status'=>201,'msg'=>$msg['message']]);
        } else {
            $domain = $this->request->domain();
            $address = $domain . '/' . $appInfo['app_dir'] . '/doc/';
            $msg = '操作成功，访问地址为：' . $address;
            return json(['status'=>200,'msg'=>$msg]);
        }
    }

    // 获取应用及文档地址
    public function getAppDocList(){
        $app_list = Application::field('app_id,app_type,app_dir,application_name')
            ->where(['app_type'=>2])
            ->select()
            ->toArray();
        $domain = $this->request->domain();
        foreach ($app_list as $k=>$v){
            $app_list[$k]['doc'] = $domain . '/' . $v['app_dir'] . '/doc/';
        }
        return json(['status'=>200,'data'=>$app_list]);
    }

    // 获取apidoc帮助信息
    public function getAppDocHelp(){
        try {
            $data = config('admin_config.apidoc_desc');
            if(!empty($data)){
                return json(['status'=>200,'data'=>$data]);
            } else {
                throw new ValidateException ('未配置帮助信息');
            }
        } catch (\Exception $e){
            throw new ValidateException ($e->getMessage());
        }
    }


    //curl请求方法
    private function go_curl($url, $type, $data = false, &$err_msg = null, $timeout = 20, $cert_info = array()){
        $type = strtoupper($type);
        if ($type == 'GET' && is_array($data)) {
            $data = http_build_query($data);
        }

        $option = array();
        if ( $type == 'POST' ) {
            $option[CURLOPT_POST] = 1;
        }
        if ($data) {
            if ($type == 'POST') {
                $option[CURLOPT_POSTFIELDS] = $data;
            } elseif ($type == 'GET') {
                $url = strpos($url, '?') !== false ? $url.'&'.$data :  $url.'?'.$data;
            }
        }
        $option[CURLOPT_URL]            = $url;
        $option[CURLOPT_FOLLOWLOCATION] = TRUE;
        $option[CURLOPT_MAXREDIRS]      = 4;
        $option[CURLOPT_RETURNTRANSFER] = TRUE;
        $option[CURLOPT_TIMEOUT]        = $timeout;
        //设置证书信息
        if(!empty($cert_info) && !empty($cert_info['cert_file'])) {
            $option[CURLOPT_SSLCERT]       = $cert_info['cert_file'];
            $option[CURLOPT_SSLCERTPASSWD] = $cert_info['cert_pass'];
            $option[CURLOPT_SSLCERTTYPE]   = $cert_info['cert_type'];
        }
        //设置CA
        if(!empty($cert_info['ca_file'])) {
            // 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
            $option[CURLOPT_SSL_VERIFYPEER] = 1;
            $option[CURLOPT_CAINFO] = $cert_info['ca_file'];
        } else {
            // 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
            $option[CURLOPT_SSL_VERIFYPEER] = 0;
        }

        $ch = curl_init();
        curl_setopt_array($ch, $option);
        $response = curl_exec($ch);
        $curl_no  = curl_errno($ch);
        $curl_err = curl_error($ch);
        curl_close($ch);
        // error_log
        if($curl_no > 0) {
            if($err_msg !== null) {
                $err_msg = '('.$curl_no.')'.$curl_err;
            }
        }
        return $response;
    }


    /**
     * 菜单迁移
     * 导出一个菜单生成所需要的所有数据库数据
     * 1. cd_sys_menu 中的数据行
     * 2. cd_sys_field 中的数据行
     * 3. cd_sys_action 中的数据行
     * 4. cd_sys_menu 行对应的表的定义和数据
     */
    function exportMenuSQL($menu_id){
        // 下面是配置sqldump文件的路径，注意不同环境的地址（Windows/Linux）
        $mysqldump = config('rds.mysqldump');

        if(empty($mysqldump)){
            new ValidateException('未配置mysqldump路径');
        }

        $username = env('database.username', '');
        $password = env('database.password', '');
        $hostname = env('database.hostname', '');
        $hostport = env('database.hostport', '');
        $database = env('database.database', '');
        $prefix = env('database.prefix', '');

        $system = php_uname('s');
        $end = $system == 'Linux' ? ' 2>&1' : '';
        $string = '';
        $output = '';

        // 菜单定义，字段定义，方法定义导出
        $tableList = ['cd_sys_menu','cd_sys_field','cd_sys_action'];
        // 取出Insert 语句
        foreach ($tableList as $key => $value){
            $cmd = $mysqldump .
                ' -u' . $username . ' -p' . $password . ' -h' . $hostname . ' -P' . $hostport . ' ' . $database . ' ' . $value .
                '  --no-tablespaces --no-create-info --complete-insert --where="menu_id=' . $menu_id . '"' . $end;
            // 执行命令行
            $output = '';
            exec($cmd, $output, $return);
            // 清除注释
            if($return == 0){
                $insertSQL = $this->clearAnnotationSQL($output);
                if($insertSQL){
                    $string .= $insertSQL;
                }
            } else {
                new ValidateException($cmd . ': 命令执行错误');
            }
        }

        // 查询菜单信息，菜单对应的表的定义
        $menuInfo = \think\facade\Db::table('cd_sys_menu')
            ->where(['menu_id'=>$menu_id])
            ->find();
        // 若建立表
        if(!empty($menuInfo) && $menuInfo['create_table'] == 1){
            $cmd = $mysqldump . ' -u' . $username . ' -p' . $password . ' -h' . $hostname . ' -P' . $hostport .
                ' ' . $database . ' ' . $prefix . $menuInfo['table_name'] . ' --no-tablespaces --complete-insert ' . $end;
            // 执行命令行
            $output = '';
            exec($cmd, $output, $return);
            // 清除注释
            if($return == 0){
                $insertSQL = $this->clearAnnotationSQL($output);
                if($insertSQL){
                    $string .= $insertSQL;
                }
            }  else {
                new ValidateException($cmd . ': 命令执行错误');
            }
        }

        $exportFilename = $menuInfo['menu_id'] . '.sql';
        $data = ['filename' => $exportFilename, 'file' => "data:application/text;base64," . base64_encode($string)];
        return json(['status'=>200,'data'=>$data]);
    }

    /**
     * 清除注释
     * @param $file
     * @return string
     */
    private function clearAnnotationSQL($array){
        $insertSQL = '';
        foreach ($array as $k => $v){
            if( substr($v,0 ,2) != '--' && substr($v,0 ,3) != '/*!' && !empty($v) && substr($v,0,9) != 'mysqldump'){
                $insertSQL .= $v . "\r\n";
            }
        }
        return $insertSQL;
    }

}

