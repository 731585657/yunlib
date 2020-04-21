<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/2 0002
 * Time: 15:51
 */

namespace app\api\controller;


use app\common\controller\Api;
use think\Db;

class Search extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    protected function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags', 'htmlspecialchars']);
    }

    public function search_list()
    {
        $type = $this->request->get('type');
        $keyword = $this->request->get('keyword');
        $page = $this->request->get('page');
        $pagesize = $this->request->get('pagesize');

        if (input('?get.type') == false  | input('?get.keyword') ==false | input('?get.page') ==false | input('?get.pagesize')==false){
            $this->error('参数缺失',null,300);
        }

        if (empty(trim($keyword))){
            $this->error('关键词缺失',null,301);
        }

        if ($type == 0){

            $data =  $this->search_prod($keyword,$page,$pagesize);

        }elseif($type == 1){

            $this->search_case($keyword,$page,$pagesize);

        }elseif ($type == 2){

            $this->search_news($keyword,$page,$pagesize);

        }
    }

    public function search_prod($keyword,$page,$pagesize)
    {
        $count = Db::name('products')
            ->where('title','like',"%$keyword%")
            ->where('status',1)
            ->count();

        if (empty($count)){
            $this->error('暂无数据',array('list'=>null),301);
        }
        $pageNum = ceil($count/$pagesize);

        $offset=($page-1)*$pagesize;//去数据开始的位置

        $data = Db::name('products')
            ->where('title','like',"%$keyword%")
            ->where('status',1)
            ->order('weigh desc,release_time desc')
            ->limit($offset,$pagesize)
            ->select();
        $this->success('请求成功',array('list'=>$data,'total'=>$count,'pageNum'=>$pageNum),200);

    }


    public function search_case($keyword,$page,$pagesize)
    {
        $count = Db::name('case')
            ->where('title','like',"%$keyword%")
            ->where('status',1)
            ->count();
        $pageNum = ceil($count/$pagesize);

        $offset=($page-1)*$pagesize;//去数据开始的位置
        $data = Db::name('case')
            ->where('title','like',"%$keyword%")
            ->where('status',1)
            ->order('release_time desc')
            ->limit($offset,$pagesize)
            ->select();
        $this->success('请求成功',array('list'=>$data,'total'=>$count,'pageNum'=>$pageNum),200);
    }


    public function search_news($keyword,$page,$pagesize)
    {

        $count = Db::name('news')
            ->where('title','like',"%$keyword%")
            ->where('status',1)
            ->count();
        $pageNum = ceil($count/$pagesize);

        $offset=($page-1)*$pagesize;//去数据开始的位置
        $data = Db::name('news')
            ->where('title','like',"%$keyword%")
            ->where('status',1)
            ->order('flag desc, weigh desc,release_time desc')
            ->limit($offset,$pagesize)
            ->select();
        $this->success('请求成功',array('list'=>$data,'total'=>$count,'pageNum'=>$pageNum),200);
    }

}