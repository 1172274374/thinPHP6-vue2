<?php

//控制器基类

namespace app\admin\controller;
use think\App;
use think\exception\FuncNotFoundException;
use think\exception\ValidateException;
use app\BaseController;
use think\facade\Db;
use app\admin\model\AdminToken as AdminTokenModel;
use utils\JwtTools;

class Admin extends BaseController
{

    // token解码的用户信息
	protected $userInfo = [];
	// jwt配置
    protected $config;


    /**
     * 初始化
     */
    protected function initialize(){
        try{
            // 构造当前URL
            $controller = $this->request->controller();
            $action = $this->request->action();
            $app = app('http')->getName();
            $url =  "{$app}/{$controller}/{$action}";

            // 载入基本配置
            $list = Db::name('admin_config')->select()->column('data','name');
            config($list,'admin_config');

            // 初始化jwt配置
            $this->config = [
                'jwt_issued_by'    => config('rds.admin.jwt_issued_by') ? config('rds.admin.jwt_issued_by') : 'rds.server',
                'jwt_permitted_for'=> config('rds.admin.jwt_permitted_for') ? config('rds.admin.jwt_permitted_for') : 'rds.client',
                'jwt_secrect'      => config('rds.admin.jwt_secrect') ? config('rds.admin.jwt_secrect') : 'aHR0cDovL3Jkcy5yYWlzZWluZm8uY24=',
                'jwt_expires'      => config('rds.admin.jwt_expires') ? config('rds.admin.jwt_expires') : '+5 minute',
            ];

            // 如果当前URL不是匿名访问的，执行Token检查
            if(!in_array($url,config('rds.nocheck'))){
                $this->checkToken();
            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
	}


    /**
     * 生成Token
     * @param $data
     * @return string
     */
	protected function setToken($data){
        try {
            // 生成Token
            $jwt = new JwtTools($this->config);
            $token = $jwt->getToken($data);
            //登录的时候把token写入数据表
            $tokenInfo = AdminTokenModel::where('token',$token)->find();
            if(!$tokenInfo){
                // 如果没有写入数据库
                if(!config('rds.multiple_login')){
                    // 不是多用户登录，则更新状态
                    AdminTokenModel::field('dev_status')->where('username',$data['username'])->update(['dev_status'=>0]);
                }
                // 记录此用户的登录数据
                AdminTokenModel::create(['token'=>$token,'username'=>$data['username']]);
            }
            return $token;
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
	}

    /**
     * 检查Token
     */
	protected function checkToken(){
	    try {
            //检测ip白名单
            $ips = config('admin_config.ip_white');
            if($ips){
                if(!in_array($this->request->ip(),preg_split('/\n/',$ips))){
                    throw new ValidateException('请求ip来源错误');
                }
            }

            $token = $this->request->header('Authorization');
            if(!$token){
                abort(101,'token不能为空');
            }
            if(count(explode('.',$token)) <> 3){
                abort(101,'token格式错误');
            }

            $tokenInfo = AdminTokenModel::where('token',$token)->find();

            if(!$tokenInfo['status']){
                abort(101,'登录状态已过期，请重新登录');
            }

            if(!$tokenInfo['dev_status']){
                abort(101,'你已下线,账户在其它设备登录!');
            }

            if(!$tokenInfo){
                abort(101,'token不存在');
            }

            $jwt = new JwtTools($this->config);
            $verifyResult = $jwt->verify($token);
            if($verifyResult['status'] == 0){
                $content = $jwt->getTokenContent($token);
                $this->userInfo = json_decode($content['payload'],true);
            } else {
                abort(101,$verifyResult['msg']);
            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
	}


    /**
     * 验证器 并且抛出异常
     * @param array $data
     * @param array|string $validate
     * @return bool
     */
    protected function validate($data,$validate){
        try{
            validate($validate)->scene($this->request->action())->check($data);
        }catch(ValidateException $e){
            throw new ValidateException ($e->getError());
        }
        return true;
    }


    /**
     * 格式化sql字段查询 转化为  label => value 结构
     * @param $sql
     * @return array|mixed
     */
    protected function query($sql){
        preg_match_all('/select(.*)from/iUs',$sql,$all);
        $array = [];
        $sqlvalue = explode(',',trim($all[1][0]));
        // 键值对 和 带有别名的查询
        if(count($sqlvalue) == 1) {
            $sql = str_replace('pre_',config('database.connections.mysql.prefix'),$sql);
            $list = Db::query($sql);
            $array = json_decode($list[0]['data'],true);
        } else {
            $sql = str_replace('pre_',config('database.connections.mysql.prefix'),$sql);
            $list = Db::query($sql);

            $alisa = array_keys($list[0]);
            foreach($list as $k=>$v){
                $array[$k]['label'] = $v[$alisa[1]];
                $array[$k]['value'] = $v[$alisa[0]];
                if($alisa[2]){
                    $array[$k]['pid'] = $v[$alisa[2]];
                }
            }
        }
        return $array;
    }


    /**
     * 获取所有菜单
     * @return array
     */
	protected function getTotalMenus(){
		$menu =  $this->getBaseMenus();
		$order_array = array_column($menu, 'sortid');
		array_multisort($order_array,SORT_ASC,$menu );
		return $menu;
	}


    /**
     * 返回当前应用的菜单列表
     * @return array
     */
	protected function getBaseMenus(){
	    try {
            $field = 'menu_id,pid,title,controller_name,status,icon,sortid,component_path';
            $list = db("sys_menu")->field($field)->where(['status'=>1,'app_id'=>1])->order('sortid asc')->select()->toArray();
            if($list){
                foreach($list as $key=>$val){
                    $menus[$key]['name'] = $val['controller_name'];
                    $menus[$key]['pid'] = $val['pid'];
                    $menus[$key]['menu_id'] = $val['menu_id'];
                    $menus[$key]['title'] = $val['title'];
                    $menus[$key]['sortid'] = $val['sortid'];
                    $menus[$key]['icon'] = $val['icon'] ? $val['icon'] : 'el-icon-menu';
                    $menus[$key]['path'] = (string)url('admin/'.str_replace('/','.',$val['controller_name']).'/index');
                    $menus[$key]['component_path'] = $val['component_path'];
                }
                return _generateListTree($menus,0,['menu_id','pid']);
            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
	}

    /**
     * 获取要加载的组件
     * @param $menu
     * @return array
     */
	protected function getComponents($menu){
		$components = [];
		foreach($menu as $v){
			$components[] = [
				'name'=>$v['name'],
				'path'=>$v['path'],
                 'meta'=>[
                     'title'=>$v['title'],
                 ],
				'component_path'=>$v['component_path']
			];
			if($v['children']){
				$components = array_merge($components,$this->getComponents($v['children']));
			}
		}
		return $components;
	}


    /**
     * 获取隐藏了菜单但是也要加载的组件
     * @param $roleInfo
     * @return array
     */
	protected function getHiddenComponents($roleInfo){
	    try {
            $list = db("sys_menu")
                ->field('controller_name,title,component_path')
                ->where('app_id',1)
                ->where('status',0)
                ->where('component_path','<>','')
                ->select()
                ->toArray();
            $components = [];
            foreach($list as $v){
                if($roleInfo['role_id'] == 1 || in_array($v['controller_name'],explode(',',$roleInfo['access']))){
                    $components[] = [
                        'name'=>$v['controller_name'],
                        'path'=>(string)url('admin/'.str_replace('/','.',$v['controller_name']).'/index'),
                        'meta'=>['title'=>$v['title']],
                        'component_path'=>$v['component_path']
                    ];
                }
            }
            return $components;
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
	}


    /**
     * 格式化查询条件,实现数据隔离
     * @param $where
     * @param string $alias
     * @return array
     */
    protected function whereFormat($where,$alias = ''){
        $userInfo = $this->userInfo;
        if($alias == ''){
            $key = 'creater_id';
        } else {
            $key = $alias . '.' . 'creater_id';
        }
        $temp_where = [];
        // 无论前端传入任何关于创建者的信息，都必须删除，然后根据权限设置，决定是否创建过滤创建者的条件
        unset($where['creater_id']);
        // 根据用户权限处理
        switch ($userInfo['permission']){
            case 1:
                //全部数据权限 不创建关于创建者的查询条件
                $temp_where[$key] = null;
                break;
            case 2:
                // 本部门及以下数据权限 获得此部门及以下的所有用户的user_id数据
                $user_id_array = $this->getAllSubDeptUser();
                $user_id_string = implode(',',$user_id_array);
                $temp_where[$key] = ['exp','in (' . $user_id_string . ')'];
                break;
            case 3:
                //本部门数据权限 获得此部门的所有用户的user_id数据
                $user_id_array = $this->getDeptUser();
                $user_id_string = implode(',',$user_id_array);
                $temp_where[$key] = ['exp','in (' . $user_id_string . ')'];
                break;
            case 4:
                //本人数据权限 查询本人的user_id数据
                $temp_where[$key] = $userInfo['user_id'];
                break;
            default:
                //无数据权限
                $temp_where[$key] = -1;
                break;
        }
        // 根据权限确定的查询创建者的新条件
        $where[$key] = $temp_where[$key];
        $formatWhere = formatWhere($where);
        return $formatWhere;
    }

    /**
     * 查询用户所在部门及子部门的所有用户id
     * @return array
     */
    private function getAllSubDeptUser(){
        $dept_id = $this->userInfo['dept_id'];

        // 查询本员工所在部门的所有子部门的部门ID
        $sql = '(SELECT dept_id FROM ';
        $sql .= '(SELECT t1.dept_id,t1.NAME,IF(FIND_IN_SET(pid, @pids) > 0,@pids := CONCAT(@pids, ",", dept_id),0) AS ischild ';
        $sql .= "FROM (SELECT dept_id,pid,NAME FROM cd_admin_dept t ORDER BY pid,dept_id) t1,(SELECT @pids := " . $dept_id . ") t2) t3 ";
        $sql .= 'WHERE ischild != 0)';
        $dept_id_array = \think\facade\Db::table([$sql => 'a'])->column('dept_id');

        // 追加员工自己的部门id
        if(count($dept_id_array)>0){
            $dept_ids =  implode(',',$dept_id_array) . ',' . $dept_id;
        } else {
            $dept_ids = $dept_id;
        }
        // 查询这些部门内的所有员工id
        $sql2 = '(SELECT user_id FROM cd_admin_user WHERE dept_id IN (' . $dept_ids . '))';

        $user_id_array = \think\facade\Db::table([$sql2 => 'a'])->column('user_id');

        return $user_id_array;
    }

    /**
     * 查询本部门的用户
     * @return array
     */
    private function getDeptUser(){
        $user_id_array = \think\facade\Db::table('cd_admin_user')->where(['dept_id'=>$this->userInfo['dept_id']])->column('user_id');
        return $user_id_array;
    }


    /**
     * 根据菜单ID获取sort_id之前的字段名
     * @param $menu_id
     * @return mixed|string
     */
    public function getAtferFieldName($menu_id){
        try {
            $fields = \think\facade\Db::table('cd_sys_field')
                ->where(['menu_id'=>$menu_id,'create_table_field'=>1])
                ->order('sortid','asc')
                ->select()->toArray();
            $afterField = '';
            foreach ($fields as $key => $value){
                if($value['field'] == 'sort_id'){
                    $afterField = $fields[$key -1]['field'];
                }
            }

            if(empty($afterField)){
                return $fields[count($fields)-1]['field'];
            } else {
                return $afterField;
            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
    }

    public function __call($method, $args){
        throw new FuncNotFoundException('方法不存在',$method);
    }

}
