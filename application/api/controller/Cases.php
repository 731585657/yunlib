<?php


namespace app\api\controller;


use app\common\controller\Api;
use think\Db;
use think\Request;

class Cases extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    protected function _initialize()
    {
        parent::_initialize();

        //设置过滤方式
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
    public function case_index()
    {
        $page = $this->request->get('page');

        $pagesize = $this->request->get('pagesize');

        $type_id = $this->request->get('type_id');

        $prod_id = $this->request->get('prod_id');

        if (input('?get.page') == false | input('?get.pagesize') == false ){
            $this->error('参数缺失',null,300);
        }

        //根据产品分类查询案例
        if (!empty($type_id) & empty($prod_id)) {

            $case_count = Db::name('case')->where("find_in_set($type_id,t_id) and `status`=1")->count();
            //var_dump($case_count);die;
            if (empty($case_count)){

                $this->error('暂无数据',array('list'=>null),301);
            }

            //得到总页数
            $pageNum = ceil($case_count/$pagesize);

            $offset=($page-1)*$pagesize;//开始去数据的位置

            //根据分类id查询所属产品

            $prod_list = Db::name('case')->fetchSql()->alias('c')->field('c.*,GROUP_CONCAT(p.title) as p_title')
                ->join('fa_products p','FIND_IN_SET(p.prod_id,c.prod_id)')
                ->where("find_in_set($type_id,c.t_id) and c.status=1")
                ->group('c.ca_id')
                ->order('c.release_time desc')
                ->limit($offset,$pagesize)->select();

            // $data['prod_type'] = $prod_type;
            $data['pageNum'] = $pageNum;
            $data['total'] = $case_count;
            if (empty($prod_list)){
                $data['list'] = null;
                $this->error('暂无数据',array('list'=>null),301);
            }
            $data['list'] = $prod_list;
            $this->success('请求成功',$data,200);

        }

            //根据产品分类和产品id查询案例
        if (!empty($type_id) & !empty($prod_id)) {
            $case_count = Db::name('case')->where("find_in_set($type_id,t_id) and find_in_set($prod_id,prod_id) and `status`=1")->count();
            if (empty($case_count)){
                $this->error('暂无数据',array('list'=>null),301);
            }

            //得到总页数
            $pageNum = ceil($case_count/$pagesize);

            $offset=($page-1)*$pagesize;//开始去数据的位置

            //根据分类id查询所属产品
            $prod_list =Db::name('case')->alias('c')->field('c.*,GROUP_CONCAT(p.title) as p_title')
                ->join('fa_products p','FIND_IN_SET(p.prod_id,c.prod_id)')
                ->where("find_in_set($type_id,c.t_id) and find_in_set($prod_id,c.prod_id) and c.status=1")
                ->group('c.ca_id')
                ->order('c.release_time desc')
                ->limit($offset,$pagesize)->select();
            // $data['prod_type'] = $prod_type;
            $data['pageNum'] = $pageNum;
            if (empty($prod_list)){
                $data['list'] = null;
                $this->error('暂无数据',array('list'=>null),301);
            }
            $data['list'] = $prod_list;
            $data['total'] = $case_count;
            $this->success('请求成功',$data,200);
        }



        //根据分类id查询所属产品总数
        $case_count = Db::name('case')->where(array('status'=>1))->count();
        if (empty($case_count)){
            $this->error('暂无数据',array('list'=>null),301);
        }

        //得到总页数
        $pageNum = ceil($case_count/$pagesize);

        $offset=($page-1)*$pagesize;//开始去数据的位置

        //根据分类id查询所属产品
        $prod_list = Db::name('case')->alias('c')->field('c.*,GROUP_CONCAT(p.title) as p_title')
            ->join('fa_products p','FIND_IN_SET(p.prod_id,c.prod_id)')
            ->where('c.status',1)
            ->group('c.ca_id')
            ->order('c.release_time desc')
            ->limit($offset,$pagesize)->select();

       // $data['prod_type'] = $prod_type;
        $data['pageNum'] = $pageNum;
        if (empty($prod_list)){
            $data['list'] = null;
            $this->error('暂无数据',array('list'=>null),301);
        }
        $data['list'] = $prod_list;
        $data['total'] = $case_count;
        $this->success('请求成功',$data,200);
    }





    /**
     * @ApiTitle    (获取产品相关案例)
     * @ApiSummary  (获取产品相关案例)
     * @ApiMethod   (get)
     * @ApiParams   ($prod_id(分类id))
     * @ApiParams   ($page(页码))
     * @ApiParams   ($pagesize(条数))
     * @ApiReturn   (array)
     */
    public function prod_case()
    {
        $prod_id = $this->request->get('prod_id');
        $page = $this->request->get('page');
        $pagesize = $this->request->get('pagesize');
        if (input('?get.page') == false | input('?get.pagesize') == false | input('?get.prod_id') == false){
            $this->error('参数缺失',null,300);
        }

        //获取总条数
        $count = Db::name('case')->where("find_in_set($prod_id,prod_id) and `status`=1")->count();
        if (empty($count)){
            $this->error('暂无案例',null,301);
        }
            $pageNum = ceil($count/$pagesize);


            $offset=($page-1)*$pagesize;//去数据开始的位置


        $case_list = Db::name('case')->alias('c')->field('c.*,GROUP_CONCAT(p.title) as p_title')
            ->join('fa_products p','FIND_IN_SET(p.prod_id,c.prod_id)')
            ->where("find_in_set($prod_id,c.prod_id) and c.`status`=1")
            ->group('c.ca_id')
            ->order('c.release_time desc')
            ->limit($offset,$pagesize)->select();

        $prod = Db::name('Products')->where('prod_id',$prod_id)->find();

        if (empty($case_list)){
            $this->error('暂无数据',array('list'=>null,'prod'=>$prod,'pageNum'=>$pageNum),301);
        }
        $this->success('请求成功',array('list'=>$case_list,'prod'=>$prod,'pageNum'=>$pageNum),200);

    }

    /**
     * @ApiTitle    (获取案例详情)
     * @ApiSummary  (获取案例详情)
     * @ApiMethod   (get)
     * @ApiParams   ($ca_id(案例id))
     * @ApiReturn   (array)
     */

    public function case_info()
    {
        $ca_id = $this->request->get('ca_id');

        if (input('?get.ca_id') == false ){
            $this->error('参数缺失',null,300);
        }

        //更新点击量
        Db::name('case')->where('ca_id', $ca_id)->setInc('clicks');

        //查询案例详情
        $data['case_info'] = Db::name('case')->where(array('ca_id'=>$ca_id,'status'=>1))->find();
        if (empty($data['case_info'])){

            $this->error('暂无数据',null,301);
        }


        //获取下一条数据
        $data['Rear'] =  Db::name('case')->field('title,ca_id')->where('release_time','<',$data['case_info']['release_time'])->order('release_time desc')->find();

        //获取上一条数据
        $data['before'] =  Db::name('case')->field('title,ca_id')->where('release_time','>',$data['case_info']['release_time'])->order('release_time asc')->find();



        //根据案例关联的产品id查询产品
        $prod_ids = $data['case_info']['prod_id'];

        $data['prod_list'] = Db::name('products')->field('prod_id,title,intro,image,release_time')->where("prod_id in($prod_ids) and `status`=1")->order('weigh desc,release_time desc')->select();

        if (empty($data['prod_list'])){
            $data['prod_list'] =null;
        }

        $this->success('请求成功',$data,200);

    }




}