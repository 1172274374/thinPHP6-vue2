<?php 
/*
 module:		缩略图配置控制器
 create_time:	2022-06-21 15:07:20
 author:		your name
 contact:		your email address
*/

//上传配置

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Uploadconfig as UploadconfigModel;
use think\facade\Db;

class Uploadconfig extends Admin {


	/*
	* @Description  数据列表
	*/
	function index(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$where['uploadconfig.id'] = $this->request->post('id', '', 'serach_in');

		$field = 'id,title,upload_replace,thumb_status,thumb_width,thumb_height,thumb_type';

		$res = UploadconfigModel::where($this->whereFormat($where))
			->field($field)
			->order('id')
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
		if(!$data['id']) throw new ValidateException ('参数错误');
		UploadconfigModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'title,upload_replace,thumb_status,thumb_width,thumb_height,thumb_type';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Uploadconfig::class);
		$data['creater_id'] = $this->userInfo['user_id'];

		try{
			$res = UploadconfigModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->id]);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'id,title,upload_replace,thumb_status,thumb_width,thumb_height,thumb_type';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Uploadconfig::class);

		try{
			UploadconfigModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200]);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'id,title,upload_replace,thumb_status,thumb_width,thumb_height,thumb_type';
		$res = UploadconfigModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		UploadconfigModel::destroy(['id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'id,title,upload_replace,thumb_status,thumb_width,thumb_height,thumb_type';
		$res = UploadconfigModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
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

