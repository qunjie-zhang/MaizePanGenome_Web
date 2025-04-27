<?php
namespace app\controller\expression;
use think\facade\View;
class Index
{
    public function download()
    {
        View::assign([
            'title' =>'表达量信息下载'
        ]);

        return View::fetch();
    }
}