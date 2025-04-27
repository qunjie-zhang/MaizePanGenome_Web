<?php
namespace app\model;
use think\Model;

class JobModel extends Model
{
    protected $pk = 'id'; //默认主键
    protected $table = 'job';   //默认数据表
    protected $json = ['parameter']; //json字段

    // 定义一个状态映射关系
    protected $status_map = array(
        0=>'wait',
        1=>'running',
        2=>'success',
        3=>'failed'
    );

    // 捕获器
    public function getStatusAttr($value)
    {
        return $this->status_map[$value];
    }
    // 修改器
    public function setStatusAttr($value)
    {
        return array_flip($this->status_map)[$value];
    }


    // 根据作业载荷获得一个作业。
    public function getJob($app)
    {
        return $this->where('status',0)->where('app',$app)->order('id','asc')->find();
    }

    /*
     * 添加作业
     */
    public function add($title,$user_id,$app,$parameter=array()){
        $res = $this->save(
            [
                'title' => $title,
                'create_time' => date('Y-m-d H:i:s'),
                'user_id' => $user_id,
                'status' => 'wait',
                'app' => $app,
                'parameter' => $parameter,
            ]
        );
        return $res;
    }

    // 更新作业信息
    public function updateInfo($id,$key,$value){
        $res = $this->update(
            [
                $key => $value,
                ],
            ['id' => $id]
        );
        return $res;
    }


}
