<?php
namespace app\admin\controller;
use app\common\controller\Backend;
use think\Db;
use think\exception\PDOException;

class Cases extends Backend
{

    protected $model = null;


    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Cases');

    }

    public function index($ids = null)
    {

        if ($this->request->isAjax() ){
          $pid =  json_decode($this->request->get('filter'),true);

            $ids=$pid['pid'];
            if (empty($ids)){

                list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
                 $data['rows'] = Db::name('Case')->order("$sort $order,release_time desc")->limit($offset,$limit)->select();

                $data['total'] = Db::name('Case')->count();
                return json($data);
            }else{

                list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);


                $data['rows'] = Db::name('Case')->where("find_in_set($ids,prod_id) and `status`=1")->order("$sort $order,release_time desc")->limit($offset,$limit)->select();

                $data['total'] = Db::name('case')->where("find_in_set($ids,prod_id) and `status`=1")->count();
                //var_dump($data);die;
                return json($data);
            }
        }
        $this->assignconfig('pid',$ids);
        return $this->view->fetch();
    }


    public function add()
    {
        if ($this->request->isAjax()){
            //验证token
            $this->token();
            $params = $this->request->post("row/a", []);

            $params['create_time'] = date('Y-m-d H:i:s');
            $params['sort'] = 1;

            if (empty($params['prod_id'][0])){
                $this->error('相关产品必须');
            }
            //分割数组
            $params['prod_id'] = implode(',',$params['prod_id']);

            //根据id查出对应的分类
            $type_list = Db::name('Products')->field('t_id')->where('prod_id','in',$params['prod_id'])->select();

            foreach ($type_list as $v){
                $list[] = $v['t_id'];
            }

            //去除重复的数组元素(array_unique)并合并成字符串(implode)
            $params['t_id'] = implode(',',array_unique($list));
            var_dump($params['t_id']);die;

            $row = Db::name('case')->insert($params);
            if ($row){
                $this->success();
            }else{
                $this->error();
            }
        }
        //或取所有的產品
        $prodList  = Db::name('Products')->where(array('status'=>1))->order("weigh desc,release_time desc")->select();
        //var_dump($prodList);die;
        $this->assign('prodList',$prodList);
        return $this->fetch();
    }


    /**
     * 编辑案例
     * @param null $ids
     * @return mixed
     */
    public function edit($ids = null)
    {

        $row = $this->model->get(['ca_id' => $ids]);
        //var_dump($row);die;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()){

            $this->token();

            $params = $this->request->post("row/a", []);
            //分割数组成字符串
            $params['prod_id'] = implode(',',$params['prod_id']);
            //根据id查出对应的分类
            $type_list = Db::name('Products')->field('t_id')->where('prod_id','in',$params['prod_id'])->select();

            foreach ($type_list as $v){
                $list[] = $v['t_id'];
            }
            //去除重复的数组元素(array_unique)并合并成字符串(implode)
            $params['t_id'] = implode(',',array_unique($list));


            $result = $row->where('ca_id', $row->ca_id)->update($params);
            // var_dump($result);die;
            if ($result === false) {
                $this->error($row->getError());
            }
            // Cache::rm('__menu__');
            $this->success();

        }
        //或取所有的产品
        $prodList  = Db::name('Products')->where(array('status'=>1))->order("weigh desc,release_time desc")->select();
        //var_dump($prodList);die;
        $this->assign('prodList',$prodList);
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


