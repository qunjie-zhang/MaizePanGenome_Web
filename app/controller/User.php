<?php

namespace app\controller;

use app\BaseController;
use app\model\UserModel;
use think\facade\Session;
use think\facade\Cookie;
use think\facade\View;
use think\facade\Route;

/**
 *  用户身份验证相关操作
 */

class User extends BaseController
{

    /**
     * @return string
     * 用户登录页面
     */
    public function login()
    {
        View::assign([
            'title' => '用户登录',
        ]);
        return View::fetch('login');
    }

    /**
     * @return \think\response\Json
     * 用户登录验证
     */
    public function verify()
    {
        $output = [
            'status' => FALSE,
            'msg' => '用户名或密码错误',
        ];
        if(!$this->request->has('username') or !$this->request->has('password')){
            $output['msg'] = '用户名或密码参数缺失';
            return json($output);
        };
        $username = trim($this->request->param('username'));
        $password = trim($this->request->param('password'));

        if($username == '' or $password == '' ){
            $output['msg'] = '用户名或密码不能为空';
            return json($output);
        };

        $user = new UserModel();
        // 验证用户名密码
        $login_res = $user->check($username, $password);
        if ($login_res){
            $userId = $user->getIdByUsername($username);
            $output['status'] = TRUE;
            $output['msg'] = '登录成功';
            $output['user_id'] = $userId;
            $output['user_name'] = $username;
            // 登录成功后设置 session
            Session::set('user_id', $userId);
            Session::set('user_name', $username);

            Cookie::set('login_time', time());
        }

        return json($output);
    }


    /**
     * 退出登录
     */
    public function logout()
    {
        Session::clear();
//        $output = [
//            'status' => TRUE,
//            'msg' => '注销登录',
//        ];
//        return json($output);
        return redirect(Route::buildUrl('user/login'));
    }


    public function status()
    {
        $output = [
            'status' => FALSE,
            'msg' => '未登录',
            'user_id' => Session::get('user_id'),
            'user_name' => Session::get('user_name'),
        ];
        if (Session::has('user_id')){
            $output['status'] = TRUE;
            $output['msg'] = '已登录';
        }
        return json($output);
    }



    /**
     * 添加用户
     */
    public function userAdd()
    {
        $output = [
            'status' => FALSE,
        ];
        $user = new UserModel();
        $res = $user->add($this->request->param('username'),$this->request->param('password'));
        if ($res) {
            $output['status'] = TRUE;
        }
        return json($output);
    }


}