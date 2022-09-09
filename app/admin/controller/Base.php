<?php

//公共类

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Files as FileModel;
use app\admin\model\Adminuser as AdminuserModel;
use think\facade\Db;
use think\response\Json;

class Base extends Admin {


    /**
     * 图片管理列表
     * @return Json
     */
	function fileList(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');
		$where = [];
		$field = 'id,filepath,hash,create_time';
		try {
            $res = FileModel::where(formatWhere($where))->field($field)->order('id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();
            $data['status'] = 200;
            $data['data'] = $res;
            return json($data);
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
	}



    /**
     * 删除图片
     * @return Json
     */
	function deleteFile(){
		$filepath =  $this->request->post('filepath', '', 'serach_in');
		if(!$filepath) $this->error('请选择图片');

		FileModel::where('filepath','in',$filepath)->delete();

		return json(['status'=>200,'msg'=>'操作成功']);
	}


    /**
     * 重置密码
     * @return Json
     */
	public function resetPwd(){
		$password = $this->request->post('password');

		if(empty($password)) $this->error('密码不能为空');

		$data['user_id'] = $this->userInfo['user_id'];
		$data['pwd'] = md5($password.config('rds.password_secrect'));

		$res = AdminuserModel::update($data);

		return json(['status'=>200,'msg'=>'操作成功']);
	}


    /**
     * 清除缓存
     * @return Json
     */
	public function clearCache(){
		$appname = app('http')->getName();
		try{
			deldir(app()->getRootPath().'runtime/cache');
		}catch(\Exception $e){
			abort(config('rds.error_log_code'),$e->getMessage());
		}
		return json(['status'=>200,'msg'=>'操作成功']);
	}


    /**
     * 角色菜单
     * @return Json
     */
	public function getRoleMenus(){
		$menu = $this->getNodeMenus(1,0);

		$order_array = array_column($menu, 'sortid');
		array_multisort($order_array,SORT_ASC,$menu );

		return json(['status'=>200,'menus'=>$menu]);
	}


    /**
     * 权限系统获取菜单
     * @param $app_id
     * @param $pid
     * @return array
     */
	private function getNodeMenus($app_id,$pid){
	    try{
            $field = 'menu_id,title,controller_name,status,icon,sortid';
            $list = Db::name('sys_menu')->field($field)->where(['app_id'=>$app_id,'pid'=>$pid])->order('sortid asc')->select()->toArray();
            if($list){
                foreach($list as $key=>$val){
                    $menus[$key]['sortid'] = $val['sortid'];
                    $menus[$key]['access'] = $val['controller_name'];
                    $menus[$key]['title'] = $val['title'].' '.'(/admin/'.$val['controller_name'].')';
                    $sublist = Db::name('sys_menu')
                        ->field($field)
                        ->where(['app_id'=>$app_id,'pid'=>$val['menu_id']])
                        ->order('sortid asc')
                        ->select()
                        ->toArray();
                    if($sublist){
                        if($this->getFuns($val)){
                            $menus[$key]['children'] = array_merge($this->getFuns($val),$this->getNodeMenus($app_id,$val['menu_id']));
                        }else{
                            $menus[$key]['children'] = $this->getNodeMenus($app_id,$val['menu_id']);
                        }
                    }else{
                        $funs = $this->getFuns($val);
                        $funs && $menus[$key]['children'] = $funs;
                    }
                }
                return array_values($menus);
            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
	}

    /**
     * 获取菜单方法
     * @param $info
     * @return array
     */
	private function getFuns($info){
	    try {
            $list = Db::name('sys_action')
                ->field('name,action_name')
                ->where('menu_id',$info['menu_id'])
                ->order('sortid asc')
                ->select()
                ->toArray();
            if($list){
                foreach($list as $key=>$val){
                    $funs[$key]['title'] = $val['name'].' '.(string)url('admin/'.$info['controller_name'].'/'.$val['action_name']);
                    $funs[$key]['access'] = (string)url('admin/'.$info['controller_name'].'/'.$val['action_name']);
                }
                return array_values($funs);
            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
	}

    /**
     * 保存或者更新 客户端状态数据
     * @return Json
     */
    public function saveSetting(){
        $data = $this->request->post('data');
        $user_id = $this->userInfo['user_id'];
        try{
            $count = Db::name('sys_setting')->where(['user_id'=>$user_id])->count();
            if($count == 0){
                Db::name('sys_setting')->insertGetId(['user_id'=>$user_id,'data'=>$data]);
            } else {
                Db::name('sys_setting')->where(['user_id'=>$user_id])->update(['data'=>$data]);
            }
        } catch(\Exception $e){
            throw new ValidateException($e->getMessage());
        }
        return json(['status'=>200,'msg'=>'操作成功']);
    }

    /**
     * 查询客户端状态数据
     * @return Json
     */
    public function getSetting(){
        $user_id = $this->userInfo['user_id'];
        try{
            $data = Db::name('sys_setting')->where(['user_id'=>$user_id])->find();
        } catch(\Exception $e){
            throw new ValidateException($e->getMessage());
        }
        return json(['status'=>200,'data'=>$data['data']]);
    }

    /**
     * 删除客户端状态数据
     * @return Json
     */
    public function deleteSetting(){
        $user_id = $this->userInfo['user_id'];
        try{
            Db::name('sys_setting')->where(['user_id'=>$user_id])->delete(true);
        } catch(\Exception $e){
            throw new ValidateException($e->getMessage());
        }
        return json(['status'=>200,'msg'=>'操作成功']);
    }

}

