<?php

//设置菜单
namespace utils\wechat;
use EasyWeChat\Factory;
use think\exception\ValidateException;
use think\facade\Log;

class MenuService
{



	public static function getMenu(){
		$app = Factory::officialAccount(config('rds.official_accounts'));
		$list = $app->menu->list();
		return $list;
	}

	public static function setMenu($menu){
        $app = Factory::officialAccount(config('rds.official_accounts'));
        try {
            $res = $app->menu->create($menu);
            if($res['errcode'] != 0){
                throw new ValidateException($res['errcode'] . ':' . $res['errmsg']);
            }
        }catch(\Exception $e){
            throw new ValidateException($e->getMessage());
        }
	}


}
