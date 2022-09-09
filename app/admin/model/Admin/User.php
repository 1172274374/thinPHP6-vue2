<?php 
/*
 module:		用户管理控制器
 create_time:	2022-06-21 14:10:19
 author:		your name
 contact:		your email address
*/

namespace app\admin\model\Admin;
use think\Model;

class User extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'user_id';

 	protected $name = 'admin_user';


	function adminrole(){
		return $this->hasOne(\app\admin\model\Admin\Role::class,'role_id','role_id');
	}

	function admindept(){
		return $this->hasOne(\app\admin\model\Admin\Dept::class,'dept_id','dept_id');
	}



}

