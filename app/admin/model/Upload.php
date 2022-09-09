<?php 
/*
 module:		上传组件模型
 create_time:	2021-04-20 16:20:51
 author:		
 contact:		
*/

namespace app\admin\model;
use think\Model;

class Upload extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'upload_id';

 	protected $name = 'admin_upload';
 

}

