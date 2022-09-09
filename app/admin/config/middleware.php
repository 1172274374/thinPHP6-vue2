<?php

return [
	'alias' => [
        // 删除菜单文件
		'deleteApplication'     => app\admin\controller\Sys\middleware\deleteApplication::class,
        // 内置功能的中间件
        'deleteAdminUser'   => app\admin\controller\Sys\middleware\deleteAdminUser::class,
        'deleteAdminDept'   => app\admin\controller\Sys\middleware\deleteAdminDept::class,
        'deleteAdminRole'   => app\admin\controller\Sys\middleware\deleteAdminRole::class,
    ],
];
