<?php

namespace app\controller\haplotype;
use app\BaseController;
use app\model\HaplotypeDatasetModel;
use app\model\HaplotypeGeneModel;
use app\model\JobModel;
use think\facade\Config;
use think\facade\Filesystem;
use think\facade\Route;
use think\facade\Session;
use think\facade\View;

class CreateTask extends BaseController
{
    /**
     * @return string
     * 渲染主要页面
     */
    public function index()
    {
        View::assign([
            'title' => '华南农业大学生物数据分析平台 - 华南农业大学生物信息学实验室',
            'action' => Route::buildUrl('haplotype/action')->domain(config('app.app_host'))->build(),
        ]);
        return View::fetch('index');
    }


    /**
     * 任务提交处理
     */
    public function action()
    {

        // 输出信息
        $output = array(
            'status' => FALSE,
            'msg' => null,
        );

        $title = trim($this->request->param('title'));
        $dataset_id = $this->request->param('dataset_id');
        $chr = trim($this->request->param('chr'));
        $start = trim($this->request->param('start'));
        $end = trim($this->request->param('end'));
        $gene_id = $this->request->param('gene_id');
        $prefix = $this->request->param('prefix');

        $na_drop = $this->request->param('na_drop');
        // 是否使用了自定义位置选项
        $custom_pos = $this->request->param('custom_pos');
        $hetero_remove = $this->request->param('hetero_remove');
        $filter_vcf_mode = $this->request->param('filter_vcf_mode');
        $filter_vcf_type = $this->request->param('filter_vcf_type');

        // 过滤类型列表
        $filter_vcf_type_list = array();
        foreach($filter_vcf_type as $k => $v){
            $filter_vcf_type_list[] = $v;
        }


        //  定义输出路径
        $outpyt_path_suffix = md5(uniqid());
        $output_path = Config::get('app.output_path').$outpyt_path_suffix;

        // 输出路径不存在则创建路径
        if(!file_exists($output_path)){
            mkdir($output_path, 0777, true);
        }


        // 获取数据集相关信息
        $haplotypeDataset = new HaplotypeDatasetModel();
        $haplotypeDatasetPath = $haplotypeDataset->where('id', $dataset_id)->value('path');
        if($haplotypeDatasetPath == null){
            $output['msg'] = '数据集信息不存在';
            return json($output);
        }

        // 作业所需参数
        $param = array(
            'dataset_path' => $haplotypeDatasetPath,
            'gene_id' => ($gene_id == '') ? null : $gene_id,
            'prefix' => $prefix,
            'chr' => ($chr == '') ? null : $chr,
            'start' => ($start == '') ? null : $start,
            'end' => ($end == '') ? null : $end,
            'na_drop' => ($na_drop == 'on') ? TRUE : FALSE,
            'custom_pos' => ($custom_pos == 'on') ? TRUE : FALSE,
            'hetero_remove' => ($hetero_remove == 'on') ? TRUE : FALSE,
            'filter_vcf_mode' => ($filter_vcf_mode == 'on') ? TRUE : FALSE,
            'filter_vcf_type' => $filter_vcf_type_list,
            'output_path' => $output_path,
        );
        //  如果上传表型文件，则替换默认数据集表型文件位置，放置在输出路径的 pheo.csv
        if ($_FILES['pheo_file']['name'] != ''){
            $file = $this->request->file('pheo_file');
            $savename = Filesystem::disk('result')->putFileAs($outpyt_path_suffix, $file,'pheo.csv');
            $param['pheo_file'] = 'pheo.csv'; //  注意此处写死路径，如果修改了文件名，需要修改此处
        }else{
            $param['pheo_file'] = null;
        }

        if ($_FILES['acc_file']['name'] != ''){
            $file = $this->request->file('acc_file');
            $savename = Filesystem::disk('result')->putFileAs($outpyt_path_suffix, $file,'acc.csv');
            $param['acc_file'] = 'acc.csv'; //  注意此处写死路径，如果修改了文件名，需要修改此处
        }else{
            $param['acc_file'] = null;
        }

         // 如果上传自定义位置文件，则命名为 range_file.json 放置在输出路径
        if ($_FILES['range_file']['name'] != ''){
            $file = $this->request->file('range_file');
            $savename = Filesystem::disk('result')->putFileAs($outpyt_path_suffix, $file,'range_file.json');
            $param['range_file'] = 'range_file.json';   // 注意此处写死路径，如果修改了文件名，需要修改此处
        }else{
            $param['range_file'] = null;
        }

        // 将作业参数写入json文件放置在输出路径
//        file_put_contents($output_path.'/parameter.json',json_encode($param));

        // 添加作业
        $jobModel = new JobModel();
        $res = $jobModel->add($title, Session::get('user_id','0'), 'haplotype', $param);

        if($res){
            $output['status'] = TRUE;
            $output['msg'] = '作业提交成功';
        }
        return json($output);
    }


    /**
     * @return \think\response\Json
     * 获取数据集列表
     */
    public function datasetGetList()
    {
        $haplotypeDataset = new HaplotypeDatasetModel();
        $res = $haplotypeDataset->list();
        return json($res);
    }


    /**
     * @return \think\response\Json
     * 获取基因信息
     * 用户在创建任务列表输入基因名称后查询基因信息自动填充到页面
     */
    public function getGeneInfo()
    {
        $output = array(
            'status' => FALSE,
            'msg' => null,
            'data' => array(),
        );

        $dataset_id = $this->request->param('dataset_id');
        $gene_id = $this->request->param('gene_id');

        if ($dataset_id == null || $gene_id == null){
            $output['msg'] = '参数错误';
            return json($output);
        }

        $haplotypeGene = new HaplotypeGeneModel();
        $res = $haplotypeGene->getGeneInfo($dataset_id, $gene_id);

        if($res != null){
            $output['status'] = TRUE;
            $output['data']['start'] = $res['start'];
            $output['data']['end'] = $res['end'];
            $output['data']['chr'] = $res['chr'];
        }
        return json($output);
    }

    // 用于 创建任务 页面下载自定义位置配置模板文件
    public function getRangeFile(){
        $range_file = '{
    "species": [
        "Huangzaosi",
        "Dan340",
        "Ye478"
    ],
    "chr": "chr02",
    "location": [
        [
            220376775,
            120399460
        ],
        [
            220419444,
            220421627
        ]
    ]
}';
        return download($range_file, 'range_file.json',true);
    }
}