<?php
namespace app\model;

use think\Model;

class ExpressionModel extends Model
{
    protected $table = 'expression';
    protected $pk = 'id';

    /**
     * @param $species
     * @param $gene_id
     * @param $organization
     * @param $value
     * @return bool
     *
     * 注册表达量信息
     */
    public function add($species,$gene_id,$organization,$value)
    {
        return $this->save([
            'species' => $species,
            'gene_id' => $gene_id,
            'organization' => $organization,
            'value' => $value,
        ]);
    }

    /**
     * @param $species
     * @param $gene_id
     * @param $group_id
     * @return bool
     *
     * 判断表达量信息是否存在
     */
    public function isAdd($species,$gene_id,$organization,$value)
    {
        $res = $this->where('species',$species)
            ->where('gene_id', $gene_id)
            ->where('organization', $organization)
            ->where('value', $value)
            ->find();
        if ($res == null){
            return false;
        }else{
            return true;
        }
    }


}
