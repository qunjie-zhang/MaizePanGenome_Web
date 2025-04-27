<?php

namespace app\controller\allele;

use think\facade\Request;
use think\facade\View;

// 等位基因信息检索模块

class Index
{

    /**
     * @return string
     * 搜索输入页面 模块首页
     */
    public function index()
    {
        View::assign('title', 'Allele Page');
        return View::fetch('index');
    }

    public function query()
    {
        $data = Request::param();
        dump($data);
    }


}