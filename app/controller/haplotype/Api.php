<?php
namespace app\controller\haplotype;
use app\BaseController;

use app\model\HaplotypeDatasetModel;
use app\model\HaplotypeGeneModel;

// 用于实现Haplotype 模块的 程序对接接口

class Api extends BaseController
{
    public function index()
    {
        return 'Hello, Haplotype!';
    }

    /**
     * @param $name string
     * @param $param array
     * @return bool
     * 添加数据集到数据库
     */
    public function datasetAdd()
    {

        // 定义一个输出信息模板
        $output = array(
            'status' => False,
            'msg' => null,
            'id' => null
        );

        $dataset_name = $this->request->param('dataset_name');
        $dataset_path = $this->request->param('dataset_path');

        // 判断参数是否齐全
        if ($dataset_name == null or $dataset_path == null){
            $output['msg'] = '参数缺失';
            return json($output);
        }

        $haplotypeDataset = new HaplotypeDatasetModel();

        // 判断是否存在  相同名称 的数据集
        if($haplotypeDataset->getIdByName($dataset_name) != null){
            $output['status'] = True;
            $output['msg'] = '数据集已添加！';
            $output['id'] = $haplotypeDataset->getIdByName($dataset_name);
            return json($output);
        }

        // 判断是否与已存在数据集名称或路径冲突
        if ($haplotypeDataset->isAvailable($dataset_name, $dataset_path)){
            $output['msg'] = '数据集名称或路径出现重复！';
            return json($output);
        }

        // 执行添加操作
        if($haplotypeDataset->add($dataset_name, $dataset_path)){
            $output['status'] = True;
            $output['msg'] = '添加成功';
            $output['id'] = $haplotypeDataset->getIdByName($dataset_name);
        }else{
            // 通常此处应该无问题，若出现问题检查数据库是否连接成功是否可用
            $output['msg'] = 'failed！';
        }

        return json($output);
    }

    /**
     * @return \think\response\Json|void
     * 向数据集中添加基因数据
     * （工程接口 仅用于更新基因列表使用）
     */
    public function geneAdd()
    {
        $output = array(
            'status' => FALSE,
            'msg' => null,
        );
        $haplotypeGene = new HaplotypeGeneModel();

        // 判断参数是否齐全
        $params = array('dataset_id', 'gene_id', 'chr', 'start', 'end');
        foreach ($params as $param){
            if (!$this->request->has($param)){
                $output['msg'] = '参数缺失:'.$param;
                return json($output);
            }
        }

        $dataset_id = $this->request->param('dataset_id');
        $gene_id = $this->request->param('gene_id');
        $chr = $this->request->param('chr');
        $start = $this->request->param('start');
        $end = $this->request->param('end');

        // 是否检查重复  默认检查，设置check参数为0则不检查
        $check = $this->request->param('check',true);

        if ($check != false){
            // 判断是否重复
            if ($haplotypeGene->isExist($dataset_id, $gene_id, $chr, $start, $end)) {
                $output['msg'] = '当前基因已存在！';
                return json($output);
            }
        }

        // 写入数据
        if ($haplotypeGene->add($dataset_id, $gene_id, $chr, $start, $end)) {
            $output['status'] = TRUE;
            $output['msg'] = '基因添加成功';
            $output['gene_id'] = $gene_id;
            $output['checked'] = $check;
            return json($output);
        }
    }


}