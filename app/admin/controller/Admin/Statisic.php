<?php 
/*
 module:		统计设置控制器
 create_time:	2022-06-21 14:36:49
 author:		your name
 contact:		your email address
*/

namespace app\admin\controller\Admin;
use think\exception\ValidateException;
use app\admin\model\Admin\Statisic as StatisicModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Statisic extends Admin {


	/*
	* @Description  数据列表
	*/
	function index(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$where['statisic_id'] = $this->request->post('statisic_id', '', 'serach_in');

		$field = 'statisic_id,unit,unit_color,current_name,current_type,current_value,current_sql,total_name,total_type,total_value,total_sql,sort_id,status,create_time,update_time';

		$res = StatisicModel::where($this->whereFormat($where))
			->field($field)
			->order('statisic_id desc')
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
		$data = $this->request->post();
		if(!$data['statisic_id']) throw new ValidateException ('参数错误');
		StatisicModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'unit,unit_color,current_name,current_type,current_value,current_sql,total_name,total_type,total_value,total_sql,sort_id,status,create_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Admin\Statisic::class);
		$data['create_time'] = time();
		$data['creater_id'] = $this->userInfo['user_id'];

		try{
			$res = StatisicModel::create($data);
			if($res->statisic_id && empty($data['sort_id'])){
				 StatisicModel::update(['sort_id'=>$res->statisic_id,'statisic_id'=>$res->statisic_id]);
			}
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->statisic_id]);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'statisic_id,unit,unit_color,current_name,current_type,current_value,current_sql,total_name,total_type,total_value,total_sql,sort_id,status,update_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Admin\Statisic::class);
		$data['update_time'] = time();

		try{
			StatisicModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200]);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('statisic_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'statisic_id,unit,unit_color,current_name,current_type,current_value,current_sql,total_name,total_type,total_value,total_sql,sort_id,status,update_time';
		$res = StatisicModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('statisic_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		StatisicModel::destroy(['statisic_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


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



}

