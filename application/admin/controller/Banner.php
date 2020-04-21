<?php


namespace app\admin\controller;


use app\common\controller\Backend;
use think\Db;

class Banner extends Backend
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
            $data['rows'] = Db::name('Webconfig')->alias('w')->field('w.id,w.title,w.image,w.release_time,w.video,p.title as p_title')
                ->join('fa_products p','w.prod_id=p.prod_id','left')
                ->where('w.type',$type)->select();

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

//          /  $params['create_time'] = date('Y-m-d H:i:s');
            if ($params['type']){
                $params['type'] = 1;
                $params['value'] = 1;
            }

            //var_dump($params);die;
            $row = Db::name('Webconfig')->insert($params);
            if ($row){
                $this->success();
            }else{
                $this->error();
            }
        }


        $this->assign('type',$type);
        if ($type == 2){

            return $this->fetch('video');
        }
        //或取所有的產品
        $prodList  = Db::name('Products')->where(array('status'=>1))->order("weigh desc,release_time desc")->select();
        //var_dump($prodList);die;
        $this->assign('prodList',$prodList);


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

        if ($row['value'] == 1){

            $this->assign('row', $row);
            return $this->fetch('video_edit');
        }else{

            $prodList  = Db::name('Products')->where(array('status'=>1))->order("weigh desc,release_time desc")->select();
            $this->assign('row', $row);
            $this->assign('prodList',$prodList);
            return $this->fetch();
    }

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