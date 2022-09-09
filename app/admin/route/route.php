<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

//删除应用
Route::post('Sys.Base/deleteApplication', 'Sys.Base/deleteApplication')->middleware('deleteApplication');

// 删除用户的中间件
Route::post('Admin.User/delete', 'Admin.User/delete')->middleware('deleteAdminUser');
Route::post('Admin.Dept/delete', 'Admin.Dept/delete')->middleware('deleteAdminDept');
Route::post('Admin.Role/delete', 'Admin.Role/delete')->middleware('deleteAdminRole');
