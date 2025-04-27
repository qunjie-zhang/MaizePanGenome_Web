<?php
namespace app\controller\expression;
use app\BaseController;

use app\model\ExpressionModel;
class Api extends BaseController
{
    public function test()
    {
        dump(app()->isDebug());
    }

    // 注册表达量信息
    public function add()
    {
        $species = $this->request->param('species');
        $gene_id = $this->request->param('gene_id');
        $organization = $this->request->param('organization');
        $value = $this->request->param('value');

        // 默认输出信息模板
        $output = array(
            'status' => False,
            'msg' => null,
        );

        if (empty($species) || empty($gene_id) || empty($organization) || empty($value)) {
            $output['msg'] = '参数不完整!';
            return json($output);
        }

        $expression_model = new ExpressionModel();

        if ($expression_model->isAdd($species,$gene_id,$organization,$value)){
            $output['msg'] = '该表达量信息已注册！';
            return json($output);
        }
        
        $result = $expression_model->add($species,$gene_id,$organization,$value);

        if ($result){
            $output['status'] = True;
            $output['msg'] = '注册成功！';
            return json($output);
        }else{
            $output['msg'] = '注册失败！';
            return json($output);
        }

    }

}