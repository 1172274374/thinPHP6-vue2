<?php 
/*
 module:		用户管理控制器
 create_time:	2022-06-21 14:10:19
 author:		your name
 contact:		your email address
*/

namespace app\admin\validate\Admin;
use think\validate;

class User extends validate {


	protected $rule = [
		'email'=>['regex'=>'/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/'],
		'mobile'=>['regex'=>'/^1[3456789]\d{9}$/'],
	];

	protected $message = [
		'email.regex'=>'邮箱格式错误',
		'mobile.regex'=>'手机号格式错误',
	];

	protected $scene  = [
		'add'=>['email','mobile'],
		'update'=>['email','mobile'],
	];



}

