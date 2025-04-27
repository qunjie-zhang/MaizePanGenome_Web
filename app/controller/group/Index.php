<?php

namespace app\controller\group;

use app\BaseController;
use think\facade\View;

class Index extends BaseController{
    public function download(){
        View::assign('title', 'Group Download');
        return View::fetch();
    }
}

