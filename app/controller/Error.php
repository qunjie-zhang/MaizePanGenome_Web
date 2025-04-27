<?php

namespace app\controller;

use think\Env;
use think\facade\Request;


class Error
{
//    public function __call($method, $args)
//    {
//        return 'error request!';
//    }

    public function E404()
    {
        return view('404')->code(404);
    }

}