<?php 
/*
 module:		部门管理控制器
 create_time:	2022-06-21 14:36:59
 author:		your name
 contact:		your email address
*/

namespace app\admin\controller\Admin;
use think\exception\ValidateException;
use app\admin\model\Admin\Dept as DeptModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Dept extends Admin {


	/*
	* @Description  数据列表
	*/
	function index(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$where['dept.dept_id'] = $this->request->post('dept_id', '', 'serach_in');
		$where['name'] = $this->request->post('name', '', 'serach_in');
		$where['pid'] = $this->request->post('pid', '', 'serach_in');
		$where['status'] = $this->request->post('status', '', 'serach_in');

		$field = 'dept_id,name,note,status,sort_id,create_time,update_time,pid';

		$res = DeptModel::where($this->whereFormat($where))
			->field($field)
			->order('dept_id desc')
			->paginate(['list_rows'=>$limit,'page'=>$page])
			->toArray();

		$res['data'] = _generateListTree($res['data'],0,['dept_id','pid']);

		$data['status'] = 200;
		$data['data'] = $res;
		$data['sql_field_data'] = $this->getSqlField('pid');
		return json($data);
	}


	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$data = $this->request->post();
		if(!$data['dept_id']) throw new ValidateException ('参数错误');
		DeptModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'name,pid,note,status,sort_id,create_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Admin\Dept::class);
		$data['create_time'] = time();
		$data['creater_id'] = $this->userInfo['user_id'];

		try{
			$res = DeptModel::create($data);
			if($res->dept_id && empty($data['sort_id'])){
				 DeptModel::update(['sort_id'=>$res->dept_id,'dept_id'=>$res->dept_id]);
			}
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->dept_id]);
	}

    public  function sub(){
        $postFiled = "name,pid,note,status,sort_id,create_time";

        $data = $this->request->only(explode(',',$postFiled),"post",null);

        $this->validate($data,\app\admin\validate\Admin\Dept::class);
        $data['create_time'] = time();
        $data['create_id'] = $this->userInfo['user_id'];



    }


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'dept_id,name,pid,note,status,sort_id,update_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Admin\Dept::class);

		try{
			DeptModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200]);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('dept_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'dept_id,name,pid,note,status,sort_id,update_time';
		$res = DeptModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('dept_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		DeptModel::destroy(['dept_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('dept_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'dept_id,name,note,status,sort_id,create_time,update_time';
		$res = DeptModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	function getFieldList(){
		return json(['status'=>200,'data'=>$this->getSqlField('pid')]);
	}

	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	private function getSqlField($list){
		$data = [];
		if(in_array('pid',explode(',',$list))){
			$data['pids'] = _generateSelectTree($this->query('select dept_id,name,pid from cd_admin_dept where status = 1'));
		}
		return $data;
	}



}

