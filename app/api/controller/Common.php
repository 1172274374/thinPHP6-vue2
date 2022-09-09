<?php

namespace app\api\controller;
use think\App;
use think\facade\Log;
use think\exception\FuncNotFoundException;
use think\facade\Db;
use utils\JwtTools;

class Common
{

	protected $request;
    protected $app;

	protected $_data;

	protected $userInfo;
    protected $config;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;
		$this->_data = $this->request->param();

		//判断是否是json请求
		if(!$this->request->isJson()){
			$this->_data = $this->request->param();
		}else{
			$this->_data = json_decode(file_get_contents('php://input'),true);
		}
		$this->_data['timestamp'] = date('Y-m-d H:i:s', time());
		$this->userInfo = json_decode($this->request->userInfo,true);
        // 初始化jwt配置
        $this->config = [
            'jwt_issued_by'    => config('rds.api.jwt_issued_by') ? config('rds.api.jwt_issued_by') : 'rds.server',
            'jwt_permitted_for'=> config('rds.api.jwt_permitted_for') ? config('rds.api.jwt_permitted_for') : 'rds.client',
            'jwt_secrect'      => config('rds.api.jwt_secrect') ? config('rds.api.jwt_secrect') : 'aHR0cDovL3Jkcy5yYWlzZWluZm8uY24=',
            'jwt_expires'      => config('rds.api.jwt_expires') ? config('rds.api.jwt_expires') : '+5 minute',
        ];
        // 载入基本配置
        // ->cache(true,60)
		$list = Db::name('admin_config')->select()->column('data','name');
		config($list,'admin_config');
        // 记录API请求日志
		if(config('rds.api_input_log')){
			Log::info('接口地址：'.request()->pathinfo().',接口输入：'.print_r($this->_data,true));
		}
    }

    /**
     * 验证器 并且抛出异常
     * @param $data
     * @param $validate
     * @return bool
     */
	protected function validate($data,$validate){
		try{
			validate($validate)->scene($this->request->action())->check($data);
		}catch(ValidateException $e){
			throw new ValidateException ($e->getError());
		}
		return true;
	}

	 /**
     * 生成token
     * @param  userinfo 用户信息
     */
	protected function setToken($userinfo){
        // 生成Token
        $jwt = new JwtTools($this->config);
        $token = $jwt->getToken($userinfo);
		return $token;
	}


	public function __call($method, $args){
        throw new FuncNotFoundException('方法不存在',$method);
    }

}
