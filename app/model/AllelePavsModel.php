<?php

namespace app\model;
use think\Model;

class AllelePavsModel extends Model
{
    protected $table = 'allele_pavs';
    protected $pk = 'id';

    public function add($gene_id,$species_name,$value)
    {
        $res = $this->save([
            'gene_id' => $gene_id,
            'species_name' => $species_name,
            'value' => $value
        ]);
        return $res;
    }

    public function test($gene_id,$species_name,$value)
    {
        return $this->where('gene_id',$gene_id)->where('species_name',$species_name)->where('value',$value)->findOrEmpty();
    }

    // 通过数据库ID定位
    public function getById($id)
    {
        return $this->where('id',$id)->findOrEmpty();
    }

    // 通过基因ID定位
    public function getByGeneId($gene_id)
    {
        return $this->where('gene_id',$gene_id)->select();
    }

    // 通过物种名称定位
    public function getBySpeciesName($species_name)
    {
        return $this->where('species_name',$species_name)->select();
    }

    // 通过基因ID和物种名称定位
    public function getByGeneIdAndSpeciesName($gene_id,$species_name)
    {
        return $this->where('gene_id',$gene_id)->where('species_name',$species_name)->findOrEmpty();
    }

}