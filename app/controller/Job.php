<?php

namespace app\controller;

use think\facade\View;
use app\BaseController;
use app\model\JobModel;
use think\facade\Session;


class Job extends BaseController

{
    // 根据id生成唯一哈希路径。用于输出文件夹路径
    protected function createHashById($id){
        return md5($id);
    }

    public function index()
    {
        View::assign('title', '作业管理');
        return View::fetch();
    }

    /**
     * @return \think\response\Json
     * 获取作业列表
     *
     *  用于 Job 管理页面 datatables ajax接口
     */
    public function getJobList()
    {
        // 如果没有指定排序字段则默认为第一字段
        $orderColumnIndex = $this->request->param('order');
        if ($orderColumnIndex == null){
            $orderColumnIndex = 0;
        }else{
            $orderColumnIndex = $orderColumnIndex[0]['column'];
        }
        // 如果没有指定排序则默认倒序
        if ($this->request->param('order') == null){
            $orderDir = 'desc';
        }else {
            $orderDir = $this->request->param('order')[0]['dir'];
        };

        $orderColumn = $this->request->param('columns')[$orderColumnIndex]['data'];

        $start = $this->request->param('start');
        $length = $this->request->param('length');

        // 当前表内总记录数
        $recordsTotal = $recordsFiltered = JobModel::where('user_id', Session::get('user_id'))->count();

        // 判断是否有输入查询内容
        $search = $this->request->param('search.value');
        if ($search != ''){
            $sql = JobModel::where('user_id', Session::get('user_id'))
                ->where('title','like','%'.$search.'%')
                ->whereOr('id','like','%'.$search.'%')
                ->whereOr('app','like','%'.$search.'%')
                ->whereOr('create_time','like','%'.$search.'%')
                ->whereOr('start_time','like','%'.$search.'%')
                ->whereOr('end_time','like','%'.$search.'%');
            $recordsFiltered = $sql->count();
            $sqlRes = $sql->order($orderColumn,$orderDir)->limit($start,$length)->select();
        }else{
            // SQL查询结果
            $sqlRes = JobModel::where('user_id', Session::get('user_id'))->order($orderColumn,$orderDir)->limit($start,$length)->select();
        }

        $output = array(
            'draw' => (int)$this->request->param('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $sqlRes,
        );
        return json($output);
    }


    /**
     * @return \think\response\Json
     * 删除作业信息
     */
    public function delete()
    {
        $output = array(
            'status' => FALSE,
            'msg' => '删除失败',
        );
        $job_id = $this->request->param('job_id');

        if ($job_id == null){
            $output['msg'] = '未指定job_id';
            return json($output);
        }

//       // 删除作业一并删除文件  因php fpm不能以root身份运行服务，该功能暂时不可用。
//        $res = deleteDirectory(public_path().'/result/'.$this->createHashById($job_id));
//        if($res == false){
//            $output['msg'] = '文件夹删除失败';
//            return json($output);
//        }

        $res = JobModel::where('id', $job_id)->where('user_id',Session::get('user_id'))->delete();
        if ($res){
            $output['status'] = TRUE;
            $output['msg'] = '删除成功';
        }

//        $dir_path = 'path/to/directory';
//        if (deleteDirectory($dir_path)) {
//            echo '目录删除成功';
//        } else {
//            echo '目录不存在或删除失败';
//        }
        return json($output);
    }


    // 重做任务
    public function redo()
    {
        $output = array(
            'status' => false,
            'msg' => null,
        );
        $job_id = $this->request->param('job_id');
        if ($job_id == null){
            $output['msg'] = 'job_id 参数缺失';
            return json($output);
        }
        $res = JobModel::where('id',$job_id)->where('user_id',Session::get('user_id'))->data([
            'start_time' => null,
            'end_time' => null,
            'status' => 0
        ])->save();

        if($res == 1){
            $output['status'] = true;
            return json($output);
        }else{
            $output['msg'] = $res;
            return json($output);
        }
    }

    /**
     * @return \think\response\Json
     * 更新作业信息
     * 传入目标作业id，指定数据库字段和值
     * http://localhost/api.job/updateInfo?job_id=2&key=status&value=1
     * http://localhost/api.job/updateInfo?job_id=2&key=start_time&value=2024-10-20%2000:13:48
     */
    public function updateInfo()
    {
        $output = array(
            'status' => FALSE,
            'msg' => '更新失败',
        );

        $job_id = $this->request->param('job_id');
        $key = $this->request->param('key');
        $value = $this->request->param('value');

        if ($job_id == null || $key == null || $value == null){
            $output['msg'] = '参数不完整';
            return json($output);
        }

        $job_module = new JobModel();
        $res = $job_module->updateInfo($job_id, $key, $value);

        if ($res){
            $output['status'] = TRUE;
            $output['msg'] = '更新成功';
        }
        return json($output);
    }


    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 获取一个工作任务参数
     */

    public function getOneJob()
    {
        $output = array(
            'status' => FALSE,
            'msg' => '',
            'app' => null,
            'data' => null,
        );

        $app = $this->request->param('app');
        if ($app == null){
            $output['msg'] = '未指定app';
            return json($output);
        }

        $jobModel = new JobModel();
        $res = $jobModel->getJob($app);

        if($res == null){
            $output['msg'] = '暂无更新任务';
            return json($output);
        }else{
            $output['data'] = $res;
            $output['status'] = true;
            $output['app'] = $app;
            $output['msg'] = '获取成功';
        }

        return json($output);
    }


    /**
     * @return void
     *  用于显示作业结果
     * 这是一个临时方法，后续补充结果显示页面
     */
    public function view()
    {
        $output = [
            'status' => FALSE,
            'msg' => null,
        ];
        $job_id = $this->request->param('job_id');
        if ($job_id == null){
            $output['msg'] = '未指定job_id';
            return json($output);
        }

        // 获取对应作业的参数
        $jobModel = new JobModel();
        $job_param = $jobModel->where('id',$job_id)->where('user_id',Session::get('user_id'))->value('parameter');

        if (empty($job_param)){
            $output['msg'] = '未找到符合条件作业作业';
            return json($output);
        }
        
        // 输出路径包含public_path，此处剔除。
        $output_path = str_replace(public_path(),'',$job_param['output_path']);

        // 进行跳转
        return redirect(config('app.app_host').'/'.$output_path);
    }

}