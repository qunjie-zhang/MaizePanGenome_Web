<?php
declare (strict_types = 1);

namespace app\middleware;

use think\facade\Route;
use think\facade\Session;
use think\Response;

class CheckLogin
{
    /**
     * 判断用户是否登录
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        if (!Session::has('user_id')) {
            $url = (string)Route::buildUrl('user/login')->domain(config('app.app_host'))->build();
            return redirect($url);
        }
        return $next($request);
    }
}
