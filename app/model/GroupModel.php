<?php

namespace app\model;

use think\Model;
class GroupModel extends Model
{
    protected $table = 'group';
    protected $pk = 'id';


    public function add($species,$gene_id,$group_id)
    {
        return $this->save([
            'species' => $species,
            'gene_id' => $gene_id,
            'group_id' => $group_id,
        ]);
    }


    /**
     * @param $species
     * @param $gene_id
     * @param $group_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * 判断是否已经存在
     */
    public function isAdd($species,$gene_id,$group_id)
    {
        $res = $this->where('species', $species)
            ->where('gene_id', $gene_id)
            ->where('group_id', $group_id)
            ->find();
        if ($res == null) {
            return false;
        } else {
            return true;
        }
    }

}