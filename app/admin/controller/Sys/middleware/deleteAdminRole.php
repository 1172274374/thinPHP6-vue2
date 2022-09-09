<?php
namespace app\admin\controller\Sys\middleware;
use app\admin\controller\Admin;
use think\exception\ValidateException;

class deleteAdminRole extends Admin
{
	
    public function handle($request, \Closure $next)
    {	
		$data = $request->param();

        if($data['role_id'] == 1 || $data['role_id'] == 2){
            throw new ValidateException ('内置角色不可删除');
        }
		
		return $next($request);
    }
}