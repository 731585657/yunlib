<?php


namespace app\api\controller;


use app\common\controller\Api;
use think\Db;
use think\Request;
use think\response\Json;

class Product extends  Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    protected function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags', 'htmlspecialchars']);
    }

    /**
     * @ApiTitle    (获取产品列表)
     * @ApiSummary  (获取产品列表)
     * @ApiMethod   (get)
     * @ApiParams   ($type_id(分类id))
     * @ApiParams   ($page(页码))
     * @ApiParams   ($pagesize(条数))
     * @ApiReturn   (array)
     */
    public function prod_index()
    {
        $page = $this->request->get('page');
        $pagesize = $this->request->get('pagesize');
        $type_id = $this->request->get('type_id');


        if (input('?get.page') == false | input('?get.pagesize') == false | input('?get.type_id') == false){
          $this->error('参数缺失',null,300);
        }

        //获取所有当前type_id所代表的的分类
        $prod_type = Db::name('ProdType')->where(array('t_id'=>$type_id,'status'=>1))->find();
        //var_dump($prod_type);die;
        if (empty($prod_type)){
            $this->error('未查到分类',null,301);
        }

        //根据分类id查询所属产品总数
        $prod_count = Db::name('products')->where(array('t_id'=>$type_id,'status'=>1))->count();

        if (empty($prod_count)){
            $this->error('暂无数据',array('prod_type'=>$prod_type,'list'=>null),301);
        }

        //得到总页数
        $pageNum = ceil($prod_count/$pagesize);
        //判断页码是否正确
        if($page > $pageNum){
            $page =  $pageNum;
        }elseif($page < 1){
            $page =  1;
        }

        $offset=($page-1)*$pagesize;//开始去数据的位置

        //根据分类id查询所属产品
        $prod_list = Db::name('products')->where(array('t_id'=>$type_id,'status'=>1))->order('weigh desc,release_time')->limit($offset,$pagesize)->select();

        $data['prod_type'] = $prod_type;
        $data['pageNum'] = $pageNum;
        if (empty($prod_list)){
            $data['list'] = null;
            $this->error('暂无数据',array('list'=>null),301);
        }
            $data['list'] = $prod_list;

        $this->success('请求成功',$data,200);

    }


    /**
     * @ApiTitle    (获取产品详情)
     * @ApiSummary  (获取产品详情)
     * @ApiMethod   (get)
     * @ApiParams   ($prod_id(产品id))
     * @ApiReturn   (array)
     */
    public function prod_info()
    {
        $prod_id = $this->request->get('prod_id');

        if (input('?get.prod_id') == false ){
            $this->error('参数缺失',null,300);
        }
        //更新点击量
        Db::name('Products')->where('prod_id', $prod_id)->setInc('clicks');

        $data['list'] = Db::name('Products')->alias('p')->field('p.*,t.title as t_title')
            ->join('fa_prod_type t', 'p.t_id = t.t_id and t.status=1','inner')
            ->where("p.prod_id=$prod_id")->find();


        //获取上一条数据
        $data['before'] =  Db::name('Products')->field('title,prod_id')->where('weigh','>',$data['list']['weigh'])->order('weigh asc')->find();

        //获取下一条数据
        $data['Rear'] =  Db::name('Products')->field('title,prod_id')->where('weigh','<',$data['list']['weigh'])->order('weigh desc')->find();


        //查询所属分类
        $data['prod_type'] =  Db::name('ProdType')->where(array('t_id'=>$data['list']['t_id'],'status'=>1))->find();

        if (empty($data['list'])){
            $this->error('暂无数据',array('prod_type'=>$data['prod_type'],'list'=>null),301);
        }

        //获取该产品案例列表
        $data['prod_case'] = Db::name('Case')->where("find_in_set($prod_id,prod_id) and `status`=1")->order("release_time desc")->count();

        if (empty($data['prod_case'])){
            $data['prod_case'] = false;
        }else{
            $data['prod_case'] = true;
        }

        $this->success('请求成功',$data,200);

    }

    /**
     * @ApiTitle    (获取首页推荐产品)
     * @ApiSummary  (获取首页推荐产品)
     * @ApiMethod   (get)
     * @ApiParams   (参数为空)
     * @ApiReturn   (array)
     */

    public function prod_push()
    {
        $prod_list = Db::name('products')->alias('p')->field('p.prod_id,p.title,p.image,p.t_id,p.is_push,t.title as t_title')
            ->join('fa_prod_type t','p.t_id=t.t_id','inner')
            ->where(array('p.is_push'=>1,'p.status'=>1))->order('p.weigh desc')->select();
        //var_dump($prod_list);die;
        if (empty($prod_list)){
            $this->error('暂无数据',array('list'=>null),301);
        }

        $this->success('请求成功',array('list'=>$prod_list),200);
    }


    /**
     * @ApiTitle    (获取首页分类产品)
     * @ApiSummary  (获取首页分类产品)
     * @ApiMethod   (get)
     * @ApiParams   (参数为空)
     * @ApiReturn   (array)
     */

    public function index_type()
    {

        $type = $this->request->get('type');
        if (input('?get.type') == false){
            $this->error('参数缺失',null,300);
        }


        $prod_type = Db::name('ProdType')->where('status',1)->select();
        if ($type == 2){
            $prod_list = Db::name('Products')->field('prod_id,title,t_id')->where('status',1)->order('weigh desc,release_time desc')->select();
            //var_dump($prod_list);die;
            $list =array();
            foreach ($prod_type as &$t){
                $t['list'] =$list;
                //var_dump($t);
                foreach ($prod_list as $p){
                    //var_dump($p);
                    if ($p['t_id'] == $t['t_id']){
                        $t['list'][] =$p;
                    }
                }
            }
        }


        $this->success('请求成功',array('list'=>$prod_type),200);


    }





}