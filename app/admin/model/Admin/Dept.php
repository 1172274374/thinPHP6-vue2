<?php 
/*
 module:		部门管理控制器
 create_time:	2022-06-21 14:36:59
 author:		your name
 contact:		your email address
*/

namespace app\admin\model\Admin;
use think\Model;

class Dept extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'dept_id';

 	protected $name = 'admin_dept';




}

