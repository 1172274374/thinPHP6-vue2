<?php
// +----------------------------------------------------------------------
// | Cookie设置
// +----------------------------------------------------------------------
return [
    // cookie 保存时间
    'expire'    => 0,
    // cookie 保存路径
    'path'      => '/',
    // cookie 有效域名
    'domain'    => '',
    //  cookie 启用安全传输
    'secure'    => false,
    // httponly设置 [ 如果设置false 安全扫描时会提示漏洞 ]
    'httponly'  => true,
    // 是否使用 setcookie
    'setcookie' => false,
];
