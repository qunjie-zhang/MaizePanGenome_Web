<?php
namespace app\controller\group;

use app\BaseController;


use app\model\GroupModel;

class Api extends BaseController{

    public function add()
    {

//       默认输出信息模板
        $output = array(
            'status' => False,
            'msg' => null,
        );

        $species = $this->request->param('species');
        $gene_id = $this->request->param('gene_id');
        $group_id = $this->request->param('group_id');

        if (empty($species) || empty($gene_id) || empty($group_id)) {
            $output['msg'] = '参数错误';
            return json($output);
        }

        $group_model = new GroupModel();

        if ($group_model->isAdd($species, $gene_id, $group_id)) {
            $output['msg'] = '已存在该基因信息!';
            return json($output);
        }

        $result = $group_model->add($species, $gene_id, $group_id);

        if ($result){
            $output['status'] = True;
            $output['msg'] = '添加成功';
            return json($output);
        }else{
            $output['msg'] = '添加失败';
            return json($output);
        }

    }

}

