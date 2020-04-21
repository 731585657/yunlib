<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/8 0008
 * Time: 10:03
 */

namespace app\admin\controller;


use app\common\controller\Backend;
use think\Db;
use think\Request;

class Projecttype extends Backend
{
    protected  $model;

    public function __construct(Request $request = null)
    {

        parent::__construct($request);
        $this->model = model('Projecttype');
    }

    public function index()
    {

        if ($this->request->isAjax() ){

            //list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
            $data['rows'] = $this->model->select();
            //var_dump($data);die;
            $data['total'] = $this->model->count();
            return json($data);

        }

        return $this->view->fetch();
    }

    public function add()
    {
        if ($this->request->isAjax()){
            $this->token();
           $params =  $this->request->post('row/a');
         //   $params['status'] = (int)$params['status'];
            //var_dump($params);die;
           $validate = validate('Projecttype');
           if (!$validate->check($params)){
               $this->error($validate->getError());
           }

            $row = $this->model->insert($params);
            if ($row){
                $this->success();
            }else{
                $this->error();
            }
        }

        return $this->fetch();
    }


    public function edit($ids = null)
    {

        $row = $this->model->get(['id' => $ids]);
        //var_dump($row);die;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()){

            $this->token();

            $params = $this->request->post("row/a", []);
            //这里需要针对title做唯一验证

            $validate = validate('Projecttype');
            if (!$validate->check($params)){
                $this->error($validate->getError());
            }

            $result = $row->where('id', $row->id)->update($params);
            // var_dump($result);die;
            if ($result === false) {
                $this->error($row->getError());
            }
            // Cache::rm('__menu__');
            $this->success();

        }
        $this->assign('row',$row);
        return $this->fetch();



    }


    public function del($ids = "")
    {
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

}