<?php


namespace app\admin\controller;


use app\common\controller\Backend;
use fast\Tree;
use think\Cache;
use think\Db;
use think\exception\PDOException;
use think\Validate;

class Product extends Backend
{
    protected $model = null;


    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Product');

    }

    public function  index()
    {

        if ($this->request->isAjax()){
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);


            $data['rows'] = Db::name('Products')->alias('p')->field('p.*,t.title as t_title')
                ->join('fa_prod_type t', 'p.t_id = t.t_id and t.status=1','left')
                    ->order("p.$sort $order,p.release_time desc")->limit($offset,$limit)->select();
           // var_dump($data);die;
            $data['total'] = Db::name('Products')->count();
            //var_dump($total);die;
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
                //var_dump(explode(',',$params['images']));die;
                $params['prod_type'] = trim($params['prod_type']);
                if ($params['is_push'] == 1){

                   $pushNum =  Db::name('Products')->where(array('status'=>1,'is_push'=>1))->count();
                   if ($pushNum > 6){

                       $this->error('产品推荐已到最大数!');
                   }
                }

                $params['create_time'] = date('Y-m-d H:i:s');
                //var_dump($params);die;

                $row = Db::name('Products')->insert($params);

                if ($row){

                   $this->success();

                }else{

                   $this->error();
                }
        }
        //获取数据库最大的排序值+1
        $row = Db::name('Products')->field('max(weigh) as max')->where('status',1)->find();
        $max = $row['max']+1;
        $this->assign('max',$max);

        //获取产品分类
        $type = Db::name('ProdType')->field('t_id,title')->where('status',1)->select();

        $this->assign('type',$type);
        return $this->fetch();
    }

    /**
     * 编辑产品
     * @param null $ids
     * @return mixed
     */
    public function edit($ids = null)
    {

        $row = $this->model->get(['prod_id' => $ids]);
        //var_dump($row);die;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()){

            $this->token();

            $params = $this->request->post("row/a", []);
           // var_dump($params);die;
            if ($params['is_push'] == 1){
                $pushNum =  Db::name('Products')->where(array('status'=>1,'is_push'=>1))->count();
                if ($pushNum > 6){
                    $this->error('产品推荐已到最大数!');
                }
            }

//            //这里需要针对name做唯一验证
//            $ruleValidate = \think\Loader::validate('Product');
//            //var_dump($ruleValidate);die;
//            $ruleValidate->rule([
//                'title' => 'require|format|unique:Product,title,' . $row->prod_id,
//            ]);
//            //var_dump($row);die;
//            $result = $row->validate()->save($params);
           // var_dump($result);die;
            $result = $row->where('prod_id', $row->prod_id)->update($params);
            if ($result === false) {
                $this->error($row->getError());
            }
           // Cache::rm('__menu__');
            $this->success();

        }
        //获取产品分类
        $type = Db::name('ProdType')->field('t_id,title')->where('status',1)->select();

        $this->assign('type',$type);

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