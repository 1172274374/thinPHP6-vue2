<?php
/*
 module:		基础路由
*/

use think\facade\Route;



config('rds.api_upload_auth') && Route::rule('Upload/Upload', 'Upload/Upload')->middleware(['JwtAuth']);	//图片上传;


