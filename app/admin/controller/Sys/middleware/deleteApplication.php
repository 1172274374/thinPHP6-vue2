<?php
namespace app\admin\controller\Sys\middleware;
use app\admin\controller\Sys\model\Menu;
use app\admin\controller\Sys\model\Application;
use app\admin\controller\Sys\model\Field;
use app\admin\controller\Sys\model\Action;
use think\exception\ValidateException;
use app\admin\controller\Admin;
use think\facade\Db;


class deleteApplication extends Admin
{

    public function handle($request, \Closure $next)
    {
		$data = $request->param();

		$applicationInfo = Application::find($data['app_id']);

		$rootPath = app()->getRootPath();

        if($applicationInfo['app_dir']){
            deldir($rootPath.'/app/'.$applicationInfo['app_dir']); //删除应用
        }
        $where['menu_id'] = Menu::where(['app_id'=>$data['app_id']])->column('menu_id');

        Menu::where(['app_id'=>$data['app_id']])->delete();
        Field::where($where)->delete();
        Action::where($where)->delete();

		return $next($request);


    }
}
