<?php 
/*
 module:		token模型
 create_time:	2021-04-20 11:23:59
 author:		
 contact:		
*/

namespace app\admin\model;
use think\Model;

class AdminToken extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'id';

 	protected $name = 'admin_token';
 

}

