<?php 
/*
 module:		基本配置控制器
 create_time:	2022-07-19 12:48:45
 author:		your name
 contact:		your email address
*/

namespace app\admin\controller\Admin;
use think\exception\ValidateException;
use app\admin\model\Admin\Config as ConfigModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Config extends Admin {


	/*
 	* @Description  基本配置
 	*/
	public function index(){
		$data = $this->request->post();
		$this->validate($data,\app\admin\validate\Admin\Config::class);
		$data['hobby'] = getItemData($data['hobby']);
		$data['keyword'] = implode(',',$data['keyword']);

		$info = ConfigModel::column('data','name');
		foreach ($data as $key => $value) {
			if(array_key_exists($key,$info)){
				ConfigModel::field('data')->where(['name'=>$key])->update(['data'=>$value]);
			}else{
				ConfigModel::create(['name'=>$key,'data'=>$value]);
			}
		}
		return json(['status'=>200]);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getInfo(){
		$res = ConfigModel::column('data','name');
		$res['hobby'] = json_decode($res['hobby'],true);
		$res['keyword'] = explode(',',$res['keyword']);
		$res['water_alpha'] = (int)$res['water_alpha'];
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

