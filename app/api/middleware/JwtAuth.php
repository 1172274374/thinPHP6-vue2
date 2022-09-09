<?php

namespace app\api\middleware;
use think\exception\ValidateException;
use utils\Jwt;
use utils\JwtTools;


class JwtAuth
{

    public function handle($request, \Closure $next)
    {
		$token = $request->header('Authorization');
		if(!$token){
			throw new ValidateException('token不能为空');
		}
		if(count(explode('.',$token)) <> 3){
			throw new ValidateException('token格式错误');
		}
        // 初始化jwt配置
        $config = [
            'jwt_issued_by'    => config('rds.api.jwt_issued_by') ? config('rds.api.jwt_issued_by') : 'rds.server',
            'jwt_permitted_for'=> config('rds.api.jwt_permitted_for') ? config('rds.api.jwt_permitted_for') : 'rds.client',
            'jwt_secrect'      => config('rds.api.jwt_secrect') ? config('rds.api.jwt_secrect') : 'aHR0cDovL3Jkcy5yYWlzZWluZm8uY24=',
            'jwt_expires'      => config('rds.api.jwt_expires') ? config('rds.api.jwt_expires') : '+5 minute',
        ];
        $jwt = new JwtTools($config);
        $verifyResult = $jwt->verify($token);
        if($verifyResult['status'] == 0){
            $content = $jwt->getTokenContent($token);
            $request->userInfo = json_decode($content['payload'],true);
            return $next($request);
        } else {
            return json(['status'=>102,'msg'=>'token失效']);
        }
    }
}
