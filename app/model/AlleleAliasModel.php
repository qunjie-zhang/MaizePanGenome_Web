<?php
namespace app\model;

use think\Model;

// 一个基因可以对应多个别名
// 一个别名只能对应一个基因

class AlleleAliasModel extends Model
{
    protected $table = 'allele_alias';
    protected $pk = 'id';

    /**
     * @param $gene_id
     * @param $alias
     * @param $from
     * @return bool
     * 添加别名
     */
    public function add($gene_id,$alias,$from)
    {
        $res = $this->save([
            'gene_id' => $gene_id,
            'alias_name' => $alias,
            'from' => $from
        ]);
        return $res;
    }


    /**
     * @param $gene_id
     * @param $alias
     * @param $from
     * @return AlleleAliasModel|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 判断该别名是否已经存在
     */
    public function test($gene_id,$alias,$from)
    {
        $sql = $this->where('gene_id',$gene_id)->where('alias_name',$alias);
        if (empty($from)){
            return $sql->findOrEmpty();
        }else{
            return $sql->where('from',$from)->findOrEmpty();
        }
    }


    /**
     * @param $gene_id
     * @return AlleleAliasModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 通过gene_id获取别名
     */
    public function getAlias($gene_id)
    {
        return $this->where('gene_id',$gene_id)->select();
    }

    /**
     * @param $alias
     * @return mixed
     * 通过别名获取gene_id
     */
    public function getGeneId($alias)
    {
        return $this->where('alias',$alias)->value('gene_id');
    }

}