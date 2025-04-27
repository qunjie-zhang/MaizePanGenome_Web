<?php

namespace app\controller\download;

use think\facade\View;
use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        View::assign('title','x');
        return 'download/index/index';
    }

    public function expression_species()
    {
        View::assign('title','表达量（样本名称） - 数据下载');
       return View::fetch();
    }

    public function expression_gene()
    {
        View::assign('title','表达量(基因名称) - 数据下载');
        return View::fetch();
    }

    public function group()
    {
        View::assign('title','共线性 - 数据下载');
        return View::fetch();
    }

}