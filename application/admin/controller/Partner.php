<?php
namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
use think\exception\PDOException;

class Partner extends Backend
{

    protected $model = null;


    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Partner');

    }

    public function  index()
    {

        if ($this->request->isAjax()){

            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);

            $data['rows'] = Db::name('Partner')
                ->order("$sort $order,create_time desc")->limit($offset,$limit)->select();

            $data['total'] = Db::name('Partner')->count();
           // var_dump($data);die;
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
            $params['create_time'] = date('Y-m-d');
            //var_dump($params);die;
            $row = Db::name('Partner')->insert($params);
            if ($row){
                $this->success();
            }else{
                $this->error();
            }
        }
        //获取数据库最大的排序值+1

        $row = Db::name('Partner')->field('max(weigh) as max')->where('status',1)->find();

        if (!empty($row)){

            $max = $row['max']+1;

            $this->assign('max',$max);
        }




        return $this->fetch();
    }



    /**
     * 编辑产品
     * @param null $ids
     * @return mixed
     */
    public function edit($ids = null)
    {
        //var_dump($ids);die;

        $row = $this->model->get(['id' => $ids]);
        //var_dump($row);die;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()){

            $this->token();

            $params = $this->request->post("row/a", []);

//            //这里需要针对name做唯一验证
//            $ruleValidate = \think\Loader::validate('Product');
//            //var_dump($ruleValidate);die;
//            $ruleValidate->rule([
//                'title' => 'require|format|unique:Product,title,' . $row->prod_id,
//            ]);
//            //var_dump($row);die;
//            $result = $row->validate()->save($params);
            // var_dump($result);die;
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