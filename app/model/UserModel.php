<?php

namespace app\model;
use think\Model;

class UserModel extends Model
{

    protected $pk = 'id'; //默认主键
    protected $table = 'user';   //默认数据表
    protected $autoWriteTimestamp = true; //自动时间戳
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * @param $username
     * @param $password
     * @return bool
     * 添加用户名密码到数据库
     */
    public function add($username,$password){
        $res = $this->save(
            [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]
        );
        return $res;
    }

    /**
     * @param $username
     * @return mixed
     * 验证用户名密码
     */
    public function check($username,$password)
    {
        $pwd = $this->where('username',$username)->value('password');
        if ($pwd == null){
            return false;
        }
        return password_verify($password,$pwd);
    }

    /**
     * @param $username
     * @return mixed
     * 通过用户名获取用户id
     */
    public function getIdByUsername($username)
    {
        return $this->where('username', $username)->value('id');
    }

}