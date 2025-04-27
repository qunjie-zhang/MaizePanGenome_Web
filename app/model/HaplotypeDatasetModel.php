<?php

namespace app\model;
use think\Model;
class HaplotypeDatasetModel extends Model
{
    protected $table = 'haplotype_dataset';
    protected $pk = 'id';


    /**
     * @param $name string
     * @param $param array
     * @return bool
     * 添加数据集到数据库中
     * 如果数据集已经存在则返回FALSE
     * 如果数据集添加成功则返回TRUE
     */
    public function add($name,$path)
    {
        $res = $this->save([
            'name' => $name,
            'path' => $path,
        ]);
        return $res;
    }


    /**
     * @param $name
     * @param $path
     * @return bool
     * 判断数据集名称和路径是否可用。
     * 用户名和路径均不可重复
     */
    public function isAvailable($name,$path)
    {
        $res = $this->where('name',$name)->whereOr('path',$path)->findOrEmpty();
        if ($res->isEmpty()){
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * @param $name
     * @return mixed
     * 通过数据集名称获得数据集ID
     */
    public function getIdByName($name)
    {
        $res = $this->where('name',$name)->value('id');
        return $res;
    }


    /**
     * @return array
     * 获取所有数据集列表
     */
    public function list()
    {
        return $this->field(['id','name'])->select()->toArray();
    }


}