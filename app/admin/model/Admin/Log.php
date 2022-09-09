<?php 
/*
 module:		日志管理控制器
 create_time:	2022-06-21 15:39:20
 author:		your name
 contact:		your email address
*/

namespace app\admin\model\Admin;
use think\Model;

class Log extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'id';

 	protected $name = 'admin_log';




}

