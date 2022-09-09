<?php 
/*
 module:		角色管理控制器
 create_time:	2022-06-21 14:51:48
 author:		your name
 contact:		your email address
*/

namespace app\admin\controller\Admin;
use think\exception\ValidateException;
use app\admin\model\Admin\Role as RoleModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Role extends Admin {


	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	function getFieldList(){
		return json(['status'=>200,'data'=>$this->getSqlField('')]);
	}

	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	private function getSqlField($list){
		$data = [];
		return $data;
	}

 /*start*/
	/*
 	* @Description  数据列表
 	*/
	function index(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$where['role_id'] = $this->request->post('role_id', '', 'serach_in');
        $where['name'] = ['like',$this->request->post('name', '', 'serach_in')];
		$where['status'] = $this->request->post('status', '', 'serach_in');

		$field = 'role_id,name,description,status';

        // change start
        if($this->userInfo['user_id'] != 1){
            $whereRaw = 'role_id != 1';
        } else {
            $whereRaw = '1 = 1';
        }
        // change end

		$res = RoleModel::where($this->whereFormat($where))
			->field($field)
            ->whereRaw($whereRaw) // change
			->order('role_id desc')
			->paginate(['list_rows'=>$limit,'page'=>$page])
			->toArray();

		$data['status'] = 200;
		$data['data'] = $res;
		return json($data);
	}


	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'role_id,status';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['role_id']) throw new ValidateException ('参数错误');
		RoleModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'name,description,status,access'; // change
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Admin\Role::class);

		// change start
        if(!in_array('Home',$data['access'])){
            array_push($data['access'],'Home');
        }

        $data['access'] = implode(',',$data['access']);
        // change end

		try{
			$res = RoleModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'role_id,name,description,status,access'; // change
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Admin\Role::class);

		// change start
        if(!in_array('Home',$data['access'])){
            array_push($data['access'],'Home');
        }

        $data['access'] = implode(',',$data['access']);
        // change end

		try{
			RoleModel::update($data);
			$roleInfo = RoleModel::where(['role_id'=>$data['role_id']])->find();
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$roleInfo]);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('role_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'role_id,name,description,status,access'; // change
		$res = RoleModel::field($field)->find($id);
        $res['access'] = explode(',',$res['access']); // change
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('role_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		RoleModel::destroy(['role_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


    /*end*/



}

