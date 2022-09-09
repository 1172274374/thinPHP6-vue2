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

// +----------------------------------------------------------------------
// | 自定义配置
// +----------------------------------------------------------------------
return [


    // ++++++++++++++++++++++++++++++++++++++基本设置+++++++++++++++++++++++++++++++++++++++++++++
    // 授权码
    'appid'             => '',
    'secrect'           => '',
    //文件上传二级目录 标准的日期格式
    'upload_subdir'		=>  'Ym',
    //不需要验证权限的url
    'nocheck'			=> [
        'admin/Login/verify',
        'admin/Login/index',
        'admin/Login/logout',
        'admin/Base/getRoleMenus'
    ],
    //写入日志的状态码
    'error_log_code'	=> 500,
    //密码加密秘钥
    'password_secrect'	=> 'Rapid_Development_System',
    //总后台是否开启多设备登录 true多设备登录 false单设备登录
    'multiple_login'		=> true,
    //默认导出格式
    'dump_extension'	=> 'xlsx',
    //后台登录验证码开关
    'verify_status'		=> true,
    //水印图片路径
    'water_img'			=>	'./static/images/water.png',
    //上传图片是否检测图片存在
    'check_file_status'	=> true,
    // ++++++++++++++++++++++++++++++++++++++基本设置+++++++++++++++++++++++++++++++++++++++++++++

    // ++++++++++++++++++++++++++++++++++++++JWT配置+++++++++++++++++++++++++++++++++++++++++++++
    // 记录api请求日志
    'api_input_log' => true,
    // jwt鉴权配置
    'api'   => [
        'jwt_issued_by'             => 'rds.server',
        'jwt_permitted_for'         => 'rds.client',
        'jwt_secrect'               => 'aHR0cDovL3Jkcy5yYWlzZWluZm8uY24=',
        'jwt_expires'               => '+24 hour',
    ],
    'admin' => [
        'jwt_issued_by'             => 'rds.server',
        'jwt_permitted_for'         => 'rds.client',
        'jwt_secrect'               => 'aHR0cDovL3Jkcy5yYWlzZWluZm8uY24=',
        'jwt_expires'               => '+24 hour',
    ],
    // ++++++++++++++++++++++++++++++++++++++JWT配置+++++++++++++++++++++++++++++++++++++++++++++




    // ++++++++++++++++++++++++++++++++++++++短信网关+++++++++++++++++++++++++++++++++++++++++++++

    'juhe_sms_key'		=> '',      //聚合短信配置 - key
    'juhe_sms_tempCode'	=> '',      //聚合短信配置 - 短信验证码模板


    'ali_sms_accessKeyId'		=> '',      //阿里云短信配置 - keyId
    'ali_sms_accessKeySecret'	=> '',      //阿里云短信配置 - keysecret
    'ali_sms_signname'			=> '',      //阿里云短信配置 - 签名
    'ali_sms_tempCode'			=> '',      //阿里云短信配置 -  短信模板 Code

    'cryun_accesskey'   => '',      // 创瑞云短信配置 - accesskey
    'cryun_secret'      => '',      // 创瑞云短信配置  - 加密字符串
    'cryun_templateCode'=> '',      // 创瑞云短信配置 - 模板编号
    'cryun_sign'        => '',      // 创瑞云短信配置 - 验证码
    // ++++++++++++++++++++++++++++++++++++++短信网关+++++++++++++++++++++++++++++++++++++++++++++



    // ++++++++++++++++++++++++++++++++++++++云存储+++++++++++++++++++++++++++++++++++++++++++++
    //oss开启状态 以及配置指定oss
    'oss_status'			=> false,	//true启用  false 不启用
    'oss_upload_type'		=> 'server',//client 客户端直传  server 服务端传
    'oss_default_type'		=> 'ali',	//oss使用类别  ali 则使用ali的oss  qiniuyun 则使用七牛云oss

    //七牛云oss配置
    'qny_oss_accessKey' 	    => '',  //  access_key
    'qny_oss_secretKey' 	    => '',  //  secret_key
    'qny_oss_bucket'	  	    => '',	//  bucket 空间名称
    'qny_oss_domain'	  	    => '', 	//  绑定域名，以/结尾
    'qny_oss_client_uploadurl'	=> '',	//七牛云客户端直传上传地址 不用动如果提示地址错误 根据提示换就行

    //阿里云oss配置
    'ali_oss_accessKeyId'		=> '',  //阿里云 keyId
    'ali_oss_accessKeySecret'	=> '',	//阿里云 keysecret
    'ali_oss_endpoint'			=> '',	//不写bucket名字
    'ali_oss_bucket'			=> '',	//阿里bucket
    // ++++++++++++++++++++++++++++++++++++++云存储+++++++++++++++++++++++++++++++++++++++++++++






    // ++++++++++++++++++++++++++++++++++++++小程序配置+++++++++++++++++++++++++++++++++++++++++++++
    //小程序配置
    'mini_program'			=> [
        'app_id' => '',		//小程序appid 春考之家
        'secret' => '',		//小程序secret
    ],
    // ++++++++++++++++++++++++++++++++++++++小程序配置+++++++++++++++++++++++++++++++++++++++++++++



    // ++++++++++++++++++++++++++++++++++++++微信公众号配置+++++++++++++++++++++++++++++++++++++++++++++
    // 公众号
    'official_accounts'		=> [
        'app_id'        => '', //appid
        'secret'		=> '', //测试公众号secret
        'token'			=> '', // Token
    ],
    // ++++++++++++++++++++++++++++++++++++++微信公众号配置+++++++++++++++++++++++++++++++++++++++++++++



    // ++++++++++++++++++++++++++++++++++++++微信支付配置+++++++++++++++++++++++++++++++++++++++++++++
    'pay_display'	=> 1,

    //微信支付配置
    'wechat_pay'			=> [
        'mch_id'                => '',	//商户号
        'key'                   => '',  //微信支付32位秘钥
        'cert_path'             => app()->getRootPath().'extend/utils/wechat/zcerts/apiclient_cert.pem',	//证书路径
        'key_path'              => app()->getRootPath().'extend/utils/wechat/zcerts/apiclient_key.pem',	//证书路径
        'rsa_public_key_path'   => app()->getRootPath().'extend/utils/wechat/zcerts/public.pem',	        //rsa公钥
    ],
    // ++++++++++++++++++++++++++++++++++++++微信支付配置+++++++++++++++++++++++++++++++++++++++++++++




    // ++++++++++++++++++++++++++++++++++++++API配置+++++++++++++++++++++++++++++++++++++++++++++
    'api_upload_auth'=> false,	//api应用上传是否验证token  true 验证 false不验证 需要重新生成
    // ++++++++++++++++++++++++++++++++++++++API配置+++++++++++++++++++++++++++++++++++++++++++++



    // ++++++++++++++++++++++++++++++++++++++生成的代码注释+++++++++++++++++++++++++++++++++++++++++++++
    //文件注释
    'comment'=>[
        'api_comment'   =>  true,	                //api接口详细注释 true生成 false不生成
        'file_comment'  =>  true,	                //文件头部注释  true生成 false不生成
        'author'        =>  'your name',            // 定义作者信息
        'contact'       =>  'your email address',   // 定义联系方式
    ],
    // ++++++++++++++++++++++++++++++++++++++生成的代码注释+++++++++++++++++++++++++++++++++++++++++++++



    // ++++++++++++++++++++++++++++++++++++++邮件配置+++++++++++++++++++++++++++++++++++++++++++++
    //邮件发送服务
    'email'	=> [
        'Host'		=> '',	    //邮箱smtp服务器地址
        'Port'		=> 25,		//邮箱端口
        'From'		=> '',	    //发送者邮箱
        'FromName'	=> '',		//发送者昵称
        'Username'	=> '',	    //登录邮箱用户名
        'Password'	=> '',	    //登录邮箱授权码 注意不是账号密码
    ],
    // ++++++++++++++++++++++++++++++++++++++邮件配置+++++++++++++++++++++++++++++++++++++++++++++

    // end
];
