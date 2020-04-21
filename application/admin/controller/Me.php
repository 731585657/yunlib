<?php


namespace app\admin\controller;


use app\common\controller\Backend;
use think\Db;

class Me extends Backend
{

    protected $model = null;


    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Me');

    }

    public function index($type=null)
    {


        //var_dump($type);die;
        if ($this->request->isAjax() ){
            $pid =  json_decode($this->request->get('filter'),true);

            $type=$pid['type'];
            //var_dump($type);die;
            $data['rows'] = Db::name('Me')->where('type',$type)->select();

            //var_dump($data);die;
            // var_dump($data);die;
            $data['total'] = Db::name('Me')->count();
            //var_dump($total);die;
            return json($data);

        }
        $this->assignconfig('type',$type);
        return $this->view->fetch();

    }


    public function add($type=null)
    {

        if ($this->request->isAjax()){
            //验证token
            $this->token();
            $params = $this->request->post("row/a", []);
            //var_dump($params);die;
            $params['create_time'] = date('Y-m-d H:i:s');
            //$params['type'] = 1;
            //var_dump($params);die;
            $row = Db::name('Me')->insert($params);
            if ($row){
                $this->success();
            }else{
                $this->error();
            }
        }
        $this->assign('type',$type);
        return $this->fetch();
    }


    public function edit($ids = null)
    {

        $row = $this->model->get(['id' => $ids]);
        //var_dump($row);die;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()) {

            $this->token();

            $params = $this->request->post("row/a", []);

            $result = $row->where('id', $row->id)->update($params);
            if ($result === false) {
                $this->error($row->getError());
            }
            // Cache::rm('__menu__');
            $this->success();

        }
        //获取产品分类
        $this->assign('row', $row);
        return $this->fetch();
    }

}