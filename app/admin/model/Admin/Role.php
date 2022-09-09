<?php 
/*
 module:		角色管理控制器
 create_time:	2022-06-21 14:51:48
 author:		your name
 contact:		your email address
*/

namespace app\admin\model\Admin;
use think\Model;

class Role extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'role_id';

 	protected $name = 'admin_role';




}

