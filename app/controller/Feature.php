<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
class Feature extends BaseController{
    public function pangene()
    {
        return View::fetch();
    }

    public function graph()
    {
        return View::fetch();
    }
}
