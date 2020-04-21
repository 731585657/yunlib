<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/2 0002
 * Time: 09:26
 */

namespace app\admin\controller;


use app\common\controller\Backend;
use think\Db;

class Webimg extends Backend
{
    protected $model = null;


    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Webconfig');

    }


    public function index($type=null)
    {
        if ($this->request->isAjax() ){
            $pid =  json_decode($this->request->get('filter'),true);

            $type=$pid['type'];
            //var_dump($type);die;
            $data['rows'] = Db::name('Webconfig')->field('id,title,image,release_time')
                ->where('type',$type)->select();

            //var_dump($data);die;
            // var_dump($data);die;
            $data['total'] = Db::name('Webconfig')->where('type',$type)->count();
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
//          /  $params['create_time'] = date('Y-m-d H:i:s');
            //$params['type'] = 1;
            //var_dump($params);die;
            $row = Db::name('Webconfig')->insert($params);
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
        $this->assign('row', $row);
        return $this->fetch();
    }
}