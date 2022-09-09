<?php
/*
 module:		用户管理控制器
 create_time:	2022-06-21 14:10:19
 author:		your name
 contact:		your email address
*/

namespace app\admin\controller\Admin;
use think\exception\ValidateException;
use app\admin\model\Admin\User as UserModel;
use app\admin\controller\Admin;
use think\facade\Db;

class User extends Admin {


	/*
	* @Description  数据列表
	*/
	function index(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$where['user.user_id'] = $this->request->post('user_id', '', 'serach_in');
		$where['user.name'] = ['like',$this->request->post('name', '', 'serach_in')];
		$where['user.user'] = ['like',$this->request->post('user', '', 'serach_in')];
		$where['user.email'] = ['like',$this->request->post('email', '', 'serach_in')];
		$where['user.mobile'] = ['like',$this->request->post('mobile', '', 'serach_in')];
		$where['adminrole.role_id'] = $this->request->post('role_id', '', 'serach_in');
		$where['admindept.dept_id'] = $this->request->post('dept_id', '', 'serach_in');
		$where['user.permission'] = $this->request->post('permission', '', 'serach_in');
		$where['user.status'] = $this->request->post('status', '', 'serach_in');

		$field = 'user_id,name,user,headimg,email,mobile,permission,note,status,sort_id,create_time,update_time';

		$withJoin = [
			'adminrole'=>explode(',','name'),
			'admindept'=>explode(',','name'),
		];

		$res = UserModel::where($this->whereFormat($where,'user'))
			->field($field)
			->withJoin($withJoin,'left')
			->order('user_id desc')
			->paginate(['list_rows'=>$limit,'page'=>$page])
			->toArray();

		$data['status'] = 200;
		$data['data'] = $res;
		$data['sql_field_data'] = $this->getSqlField('role_id,dept_id');
		return json($data);
	}


	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$data = $this->request->post();
		if(!$data['user_id']) throw new ValidateException ('参数错误');
		UserModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'name,user,headimg,email,mobile,pwd,role_id,dept_id,permission,note,status,sort_id,create_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Admin\User::class);
		$data['pwd'] = md5($data['pwd'].config('rds.password_secrect'));
		$data['create_time'] = time();
		$data['creater_id'] = $this->userInfo['user_id'];

		try{
			$res = UserModel::create($data);
			if($res->user_id && empty($data['sort_id'])){
				 UserModel::update(['sort_id'=>$res->user_id,'user_id'=>$res->user_id]);
			}
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->user_id]);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'user_id,name,user,headimg,email,mobile,role_id,dept_id,permission,note,status,sort_id,update_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Admin\User::class);

		try{
			UserModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200]);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('user_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'user_id,name,user,headimg,email,mobile,role_id,dept_id,permission,note,status,sort_id,update_time';
		$res = UserModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('user_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		UserModel::destroy(['user_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  重置密码
 	*/
	public function resetPwd(){
		$postField = 'user_id,pwd';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(empty($data['user_id'])) throw new ValidateException ('参数错误');
		if(empty($data['pwd'])) throw new ValidateException ('密码不能为空');

		$data['pwd'] = md5($data['pwd'].config('rds.password_secrect'));
		$res = UserModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	function getFieldList(){
		return json(['status'=>200,'data'=>$this->getSqlField('role_id,dept_id')]);
	}

	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	private function getSqlField($list){
		$data = [];
		if(in_array('role_id',explode(',',$list))){
			$data['role_ids'] = $this->query('select role_id,name from pre_admin_role where status = 1');
		}
		if(in_array('dept_id',explode(',',$list))){
			$data['dept_ids'] = _generateSelectTree($this->query('select dept_id,name,pid from cd_admin_dept where status = 1'));
		}
		return $data;
	}



}

