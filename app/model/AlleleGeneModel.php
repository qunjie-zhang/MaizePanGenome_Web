<?php
namespace app\model;
use think\Model;
use think\model\relation\HasMany;

class AlleleGeneModel extends Model

{

    protected $table = 'allele_gene';
    protected $pk = 'id';
    protected $json = ['extra_attributes'];


    /**
     * @param $gene_id
     * @return AlleleGeneModel|array|mixed|Model
     * 通过gene_id获取基因信息
     */
    public function getByGeneId($gene_id)
    {
        return $this->where('gene_id',$gene_id)->findOrEmpty();
    }

    public function getById($id){
        return $this->where('id',$id)->findOrEmpty();
    }


    /**
     * @param $gene_id
     * @param $gene_name
     * @param $family
     * @param $pfam
     * @param $go
     * @param $kegg
     * @param $discription
     * @param $extra_attributes
     * @return bool
     * 添加基因信息
     */
    public function add($gene_id,$gene_name,$family,$pfam,$go,$kegg,$discription,$extra_attributes)
    {
        $res = $this->save([
            'gene_id' => $gene_id,
            'gene_name' => $gene_name,
            'family' => $family,
            'pfam' => $pfam,
            'go' => $go,
            'kegg' => $kegg,
            'discription' => $discription,
            'extra_attributes' => $extra_attributes,
        ]);
        return $res;
    }


    /**
     * @return HasMany
     * 一对多 关联模型 获取别名
     */
    public function aliasName()
    {
        return $this->hasMany(AlleleAliasModel::class,'gene_id','gene_id');
    }


}