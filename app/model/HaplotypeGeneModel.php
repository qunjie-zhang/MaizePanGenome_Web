<?php

namespace app\model;
use think\Model;
class HaplotypeGeneModel extends Model
{
    protected $pk = 'id';
    protected $table = 'haplotype_gene';


    public function getGeneInfo($dataset_id,$gene_id)
    {
        $res = $this->where('dataset_id', $dataset_id)
            ->where('gene_id', $gene_id)
            ->find();
        return $res;
    }


    /**
     * @param $dataset_id
     * @param $gene_id
     * @param $chr
     * @param $start
     * @param $end
     * @return bool
     * 向数据集中添加基因数据
     */
    public function add($dataset_id,$gene_id,$chr,$start,$end)
    {
        $res = $this->save([
            'dataset_id' => $dataset_id,
            'gene_id' => $gene_id,
            'chr' => $chr,
            'start' => $start,
            'end' => $end,
        ]);
        return $res;
    }

    /**
     * @param $dataset_id
     * @param $gene_id
     * @param $chr
     * @param $start
     * @param $end
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *  通过名称染色体 判断基因是否已经存在
     */
    public function isExist($dataset_id,$gene_id,$chr,$start,$end)
    {
        $res = $this->where('dataset_id', $dataset_id)
            ->where('gene_id', $gene_id)
            ->where('chr', $chr)
//            ->where('start', $start)
//            ->where('end', $end)
            ->find();
        if ($res == null){
            return FALSE;
        }else{
            return TRUE;
        }
    }


}