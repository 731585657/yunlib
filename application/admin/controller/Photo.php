<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/12 0012
 * Time: 09:34
 */

namespace app\admin\controller;


use app\common\controller\Backend;
use think\Db;

class Photo extends Backend
{

    protected $model = null;


    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Photo');

    }

    public function index()
    {
        if ($this->request->isAjax()){
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);


            $data['rows'] = Db::name('Photo')->order("$sort $order,release_time desc")->limit($offset,$limit)->select();
            // var_dump($data);die;
            $data['total'] = Db::name('Photo')->count();
            //var_dump($total);die;
            return json($data);
        }

        return $this->view->fetch();
    }

    public function add()
    {
        if ($this->request->isAjax()){
            $this->token();
            $params = $this->request->post("row/a", []);
           // var_dump($params['images']);die;
           // $list = explode(',',$params['images']);

            $params['create_time'] = date('Y-m-d H:i:s');
            //var_dump($params);die;

            $row = Db::name('Photo')->insert($params);

            if ($row){

                $this->success();

            }else{

                $this->error();
            }
        }
        //获取数据库最大的排序值+1
        $row = Db::name('Photo')->field('max(weigh) as max')->where('status',1)->find();

        $max = $row['max']+1;
        $this->assign('max',$max);
         return $this->view->fetch();
    }


    /**
     * 编辑相册
     * @param null $ids
     * @return mixed
     */
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

            $result = $row->where('id', $row->id)->update($params);
            if ($result === false) {
                $this->error($row->getError());
            }
            // Cache::rm('__menu__');
            $this->success();

        }

        $this->assign('row',$row);
        return $this->fetch();



    }


    /**
     * 删除
     */
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