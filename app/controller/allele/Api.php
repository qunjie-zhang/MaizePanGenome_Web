<?php

namespace app\controller\allele;

use app\BaseController;
use think\facade\Request;

use app\model\AlleleGeneModel;
use app\model\AlleleAliasModel;
use app\model\AllelePavsModel;

class Api extends BaseController
{

    /**
     * @return \think\response\Json
     * 添加基因基本属性信息
     * http://127.0.0.1/api/allele/geneadd?gene_id=GENE1234&gene_name=BRCA1&family=Kinase&pfam=PF12345&go=GO:1234567&kegg=hsa12345&discription=Involved+in+cell+cycle+regulation&extra_attributes=%7B%22expression_level%22%3A75%2C%22mutation_rate%22%3A0.45%7D
     */
    public function geneAdd()
    {
        // 返回结果
        $output = [
            'status' => False,
            'msg' => ''
        ];

        $model = new AlleleGeneModel();
        $gene_id = Request::param('gene_id');

        // 判断是否存在  相同gene_id 的基因
        if($model->getByGeneId($gene_id)->isExists()){
            $output['msg'] = '基因ID已存在！';
            return json($output);
        }

        // 数据库固定字段列表
        $fields = array('gene_id', 'gene_name', 'family', 'pfam', 'go', 'kegg', 'discription', 'extra_attributes');
        // 待保存到数据库的数据
        // 固定字段信息直接传入，非固定字段信息以json形式写入extra_attributes字段
        $data = array();
        foreach (Request::param() as $key => $value) {
            if(in_array($key, $fields)) {
                $data[$key] = $value;
            }
            else{
                $data['extra_attributes'][$key] = $value;
            }
        }

        try{
            $res = $model->save($data);
        }catch (\Exception $e){
            $output['msg'] = $e->getMessage();
            return json($output);
        };
        $output['status'] = True;
        $output['msg'] = '添加成功';
        return json($output);
    }


    /**
     * @return \think\response\Json
     * 添加基因别名
     * http://127.0.0.1/api/allele/genealiasadd?gene_id=11&alias_name=11
     */
    public function geneAliasAdd()
    {
        $gene_id = Request::param('gene_id');
        $alias_name = Request::param('alias_name');
        $from = Request::param('from');

        // 输出信息模板
        $output = [
            'status' => False,
            'msg' => null
        ];

        foreach (array($gene_id,$alias_name) as $item){
            if (empty($item)){
                $output['msg'] = '必要参数缺失';
                return json($output);
            }
        }

        $model = new AlleleAliasModel();
        // 判断别名是否存在
        if ($model->test($gene_id,$alias_name,$from)->isExists()){
            $output['msg'] = '该别名已存在';
            return json($output);
        }

        // 添加别名信息
        if ($model->add($gene_id,$alias_name,$from)){
            $output['status'] = True;
            $output['msg'] = '添加成功';
        }
        else{
            $output['msg'] = '添加失败';
        }
        return json($output);
    }


    // http://127.0.0.1/api/allele/pavadd?gene_id=11&species_name=dd&value=6
    public function pavAdd()
    {
        $gene_id = Request::param('gene_id');
        $species_name = Request::param('species_name');
        $value = Request::param('value');

        $output = [
            'status' => False,
            'msg' => null
        ];

        foreach (array($gene_id,$species_name,$value) as $item){
            if ($item == null){
                $output['msg'] = '参数不完整！';
                return json($output);
            }
        }

        $model = new AllelePavsModel();

        if ($model->test($gene_id,$species_name,$value)->isExists()){
            $output['msg'] = '该信息已存在';
            return json($output);
        }

        if ($model->add($gene_id,$species_name,$value)){
            $output['status'] = True;
            $output['msg'] = '添加成功';
            return json($output);
        }
        else{
            $output['msg'] = '添加失败';
            return json($output);
        }

    }



}