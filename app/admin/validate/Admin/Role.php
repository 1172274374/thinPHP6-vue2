<?php 
/*
 module:		角色管理控制器
 create_time:	2022-06-21 14:51:48
 author:		your name
 contact:		your email address
*/

namespace app\admin\validate\Admin;
use think\validate;

class Role extends validate {


	protected $rule = [
		'name'=>['require'],
	];

	protected $message = [
		'name.require'=>'角色名称不能为空',
	];

	protected $scene  = [
		'add'=>['name'],
		'update'=>['name'],
	];



}

