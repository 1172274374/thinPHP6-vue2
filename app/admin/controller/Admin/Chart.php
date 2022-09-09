<?php 
/*
 module:		图表设置控制器
 create_time:	2022-06-21 14:36:51
 author:		your name
 contact:		your email address
*/

namespace app\admin\controller\Admin;
use think\exception\ValidateException;
use app\admin\model\Admin\Chart as ChartModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Chart extends Admin {


	/*
	* @Description  数据列表
	*/
	function index(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$where['chart_id'] = $this->request->post('chart_id', '', 'serach_in');
		$where['name'] = $this->request->post('name', '', 'serach_in');
		$where['type'] = $this->request->post('type', '', 'serach_in');
		$where['dimensions'] = $this->request->post('dimensions', '', 'serach_in');
		$where['source'] = $this->request->post('source', '', 'serach_in');
		$where['source_data'] = $this->request->post('source_data', '', 'serach_in');
		$where['source_sql'] = $this->request->post('source_sql', '', 'serach_in');
		$where['status'] = $this->request->post('status', '', 'serach_in');

		$field = 'chart_id,name,type,dimensions,source,source_data,source_sql,sort_id,status,create_time,update_time';

		$res = ChartModel::where($this->whereFormat($where))
			->field($field)
			->order('chart_id desc')
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
		if(!$data['chart_id']) throw new ValidateException ('参数错误');
		ChartModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'name,type,dimensions,source,source_data,source_sql,sort_id,status,create_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Admin\Chart::class);
		$data['create_time'] = time();
		$data['creater_id'] = $this->userInfo['user_id'];

		try{
			$res = ChartModel::create($data);
			if($res->chart_id && empty($data['sort_id'])){
				 ChartModel::update(['sort_id'=>$res->chart_id,'chart_id'=>$res->chart_id]);
			}
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->chart_id]);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'chart_id,name,type,dimensions,source,source_data,source_sql,sort_id,status,update_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Admin\Chart::class);
		$data['update_time'] = time();

		try{
			ChartModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200]);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('chart_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'chart_id,name,type,dimensions,source,source_data,source_sql,sort_id,status,update_time';
		$res = ChartModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('chart_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		ChartModel::destroy(['chart_id'=>explode(',',$idx)],true);
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

