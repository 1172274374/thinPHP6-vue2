<?php 
/*
 module:		基本配置控制器
 create_time:	2022-07-19 12:48:45
 author:		your name
 contact:		your email address
*/

namespace app\admin\model\Admin;
use think\Model;

class Config extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'config_id';

 	protected $name = 'admin_config';




}

