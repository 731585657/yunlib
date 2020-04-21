<?php


namespace app\admin\controller;


use app\common\controller\Backend;
use think\Db;
use think\exception\PDOException;

class Prodtype extends Backend
{
    protected $model = null;


    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Prodtype');

    }

    public function index()
    {
        if ($this->request->isAjax() ){

                //list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
                $data['rows'] = Db::name('ProdType')->where('status',1)->select();
                //var_dump($data);die;
                $data['total'] = Db::name('ProdType')->count();
                return json($data);

        }

        return $this->view->fetch();
    }

    public function add()
    {
        if ($this->request->isAjax()){
            //验证token
            $this->token();
            $params = $this->request->post("row/a", []);

            //var_dump($params);die;
            $row = Db::name('ProdType')->insert($params);
            if ($row){
                $this->success();
            }else{
                $this->error();
            }
        }

        return $this->view->fetch();
    }


    /**
     * 编辑分类
     * @param null $ids
     * @return mixed
     */
    public function edit($ids = null)
    {

        $row = $this->model->get(['t_id' => $ids]);
        //var_dump($row);die;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()){

            $this->token();

            $params = $this->request->post("row/a", []);
            //这里需要针对title做唯一验证

            $result = $row->where('t_id', $row->t_id)->update($params);
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