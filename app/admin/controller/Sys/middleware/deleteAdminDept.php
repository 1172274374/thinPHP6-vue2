<?php
namespace app\admin\controller\Sys\middleware;
use app\admin\controller\Admin;
use think\exception\ValidateException;

class deleteAdminDept extends Admin
{
	
    public function handle($request, \Closure $next)
    {	
		$data = $request->param();

        if($data['dept_id'] == 1){
            throw new ValidateException ('内置部门不可删除');
        }
		
		return $next($request);
    }
}