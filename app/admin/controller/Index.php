<?php
declare (strict_types = 1);

namespace app\admin\controller;

//首页控制器入口

class Index
{
    public function index()
    {
        return redirect((string)url('/dist/'));
    }
}
