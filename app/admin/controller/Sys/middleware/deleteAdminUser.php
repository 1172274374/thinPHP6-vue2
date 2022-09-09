<?php
namespace app\admin\controller\Sys\middleware;
use app\admin\controller\Admin;
use think\exception\ValidateException;

class deleteAdminUser extends Admin
{
	
    public function handle($request, \Closure $next)
    {	
		$data = $request->param();

        if($data['user_id'] == 1 || $data['user_id'] == 2){
            throw new ValidateException ('内置用户不可删除');
        }
		
		return $next($request);
    }
}