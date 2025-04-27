<?php

namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Config;
use think\facade\Route;
class Index extends BaseController
{
    /**
     * 首页
     */
    public function index()
    {
        View::assign([
            'title' => '华南农业大学生物数据分析平台 - 华南农业大学生物信息学实验室',
        ]);
        return View::fetch('index');
    }
}
