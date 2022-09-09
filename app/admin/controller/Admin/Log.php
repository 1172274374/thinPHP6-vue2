<?php 
/*
 module:		日志管理控制器
 create_time:	2022-06-21 15:39:20
 author:		your name
 contact:		your email address
*/

namespace app\admin\controller\Admin;
use think\exception\ValidateException;
use app\admin\model\Admin\Log as LogModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Log extends Admin {


	/*
	* @Description  数据列表
	*/
	function index(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$where['log.id'] = $this->request->post('id', '', 'serach_in');
		$where['username'] = $this->request->post('username', '', 'serach_in');
		$where['type'] = $this->request->post('type', '', 'serach_in');
		$where['status'] = $this->request->post('status', '', 'serach_in');

		$field = 'id,application_name,username,url,ip,type,create_time,update_time';

		$res = LogModel::where($this->whereFormat($where))
			->field($field)
			->order('id desc')
			->paginate(['list_rows'=>$limit,'page'=>$page])
			->toArray();

		$data['status'] = 200;
		$data['data'] = $res;
		return json($data);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		LogModel::destroy(['id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'id,application_name,username,url,ip,useragent,content,errmsg,type,create_time';
		$res = LogModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  导出
 	*/
	function dumpdata(){
		// 默认利用vxe-table的前端导出功能，如后端导出，请修改此方法和前端配置
		$data = $this->request->post();
		// 初始化对象
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		// 获取活动工作簿
		$sheet = $spreadsheet->getActiveSheet();
		// 定义标题
		if($data['isHeader']){
			foreach ($data['columns'] as $k => $v){
				$sheet->setCellValueByColumnAndRow($k + 1, 1, $v['title']);
			}
		}
		// 根据模式查询数据
		if($data['mode'] == 'all'){
			$dataList = LogModel::where(['status'=>1])->select()->toArray();
		} else {
			$dataList = LogModel::where(['id'=>$data['data']])->select()->toArray();
		}
		foreach($dataList as $key=>$val){
			$dataList[$key]['type'] = getItemVal($val['type'],'[{"label":"登录日志","value":"1","label_color":"info"},{"label":"操作日志","value":"2","label_color":"warning"},{"label":"异常日志","value":"3","label_color":"danger"}]');
			$dataList[$key]['status'] = getItemVal($val['status'],'[{"label":"开启","value":"1","label_color":"primary"},{"label":"关闭","value":"0","label_color":"danger"}]');
			$dataList[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
			$dataList[$key]['update_time'] = date('Y-m-d H:i:s',$val['update_time']);
		}

		// 构造数据
		foreach ($dataList as $k => $v){
			foreach ($data['columns'] as $_k => $_v){
					$sheet->setCellValueByColumnAndRow($_k + 1, ($k + 2), $v[$_v['field']]);
			}
		}
		$filename =   $data['filename'] . '.' . $data['type'];
		header('Access-Control-Allow-Origin:*');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		$res_excel = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, ucfirst($data['type']));
		ob_start();
		$res_excel->save('php://output');
		$xlsData = ob_get_contents();
		ob_end_clean();
		$data = ['filename' => $filename, 'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)];
		return json(['status'=>200,'data'=>$data]);
	}


	/*
	* @Description  登录日志
	*/
	function loginLogs(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$where['log.id'] = $this->request->post('id', '', 'serach_in');
		$where['username'] = $this->request->post('username', '', 'serach_in');
		$where['type'] = $this->request->post('type', '1', 'serach_in');
		$where['status'] = $this->request->post('status', '', 'serach_in');
		$where['type'] = '1';

		$field = 'id,application_name,username,url,ip,type,create_time,update_time';

		$res = LogModel::where($this->whereFormat($where))
			->field($field)
			->order('id desc')
			->paginate(['list_rows'=>$limit,'page'=>$page])
			->toArray();

		$data['status'] = 200;
		$data['data'] = $res;
		return json($data);
	}


	/*
	* @Description  操作日志
	*/
	function actionLogs(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$where['log.id'] = $this->request->post('id', '', 'serach_in');
		$where['username'] = $this->request->post('username', '', 'serach_in');
		$where['type'] = $this->request->post('type', '2', 'serach_in');
		$where['status'] = $this->request->post('status', '', 'serach_in');
		$where['type'] = '2';

		$field = 'id,application_name,username,url,ip,type,create_time,update_time';

		$res = LogModel::where($this->whereFormat($where))
			->field($field)
			->order('id desc')
			->paginate(['list_rows'=>$limit,'page'=>$page])
			->toArray();

		$data['status'] = 200;
		$data['data'] = $res;
		return json($data);
	}


	/*
	* @Description  异常日志
	*/
	function exceptionLogs(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$where['log.id'] = $this->request->post('id', '', 'serach_in');
		$where['username'] = $this->request->post('username', '', 'serach_in');
		$where['type'] = $this->request->post('type', '3', 'serach_in');
		$where['status'] = $this->request->post('status', '', 'serach_in');
		$where['type'] = '3';

		$field = 'id,application_name,username,url,ip,type,create_time,update_time';

		$res = LogModel::where($this->whereFormat($where))
			->field($field)
			->order('id desc')
			->paginate(['list_rows'=>$limit,'page'=>$page])
			->toArray();

		$data['status'] = 200;
		$data['data'] = $res;
		return json($data);
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

