<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/26 0026
 * Time: 10:33
 */

namespace app\api\controller;


use app\common\controller\Api;
use think\Db;

class News extends Api
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
     * @ApiTitle    (获取新闻列表)
     * @ApiSummary  (获取新闻列表)
     * @ApiMethod   (get)
     * @ApiParams   ($page(页码))
     * @ApiParams   ($pagesize(条数))
     * @ApiReturn   (array)
     */
    public function news_index()
    {

        $page = $this->request->get('page');
        $pagesize = $this->request->get('pagesize');
        $type = $this->request->get('type');
        if (input('?get.page') == false | input('?get.pagesize') == false | input('?get.type') == false){
            $this->error('参数缺失',null,300);
        }

        if ($type == 1){
            //列表页
            //获取新闻总数
            $news_count = Db::name('news')->where('status',1)->count();
            if (empty($news_count)){
                $this->error('暂无数据',array('list'=>null),301);
            }

            //获取总页数
            $pageNum = ceil($news_count/$pagesize);

            //判断页码是否正确
            if($page > $pageNum){
                $page =  $pageNum;
            }elseif($page < 1){
                $page =  1;
            }

            //获取数据开始的地方
            $offset = ($page-1)*$pagesize;
            //var_dump($page-1);die;

            //查询新闻列表
            $news_list = Db::name('news')->where('status',1)->order('flag desc,weigh desc,release_time desc')->limit($offset,$pagesize)->select();

            $data['pageNum'] = $pageNum;
            if (empty($news_list)){
                $data['list'] = null;
                $this->error('暂无数据',array('list'=>null),301);
            }
            $data['list'] = $news_list;
            $this->success('请求成功',$data,200);


        }

        //获取新闻总数
        $news_count = Db::name('news')->where('status',1)->count();
        if (empty($news_count)){
            $this->error('暂无数据',array('list'=>null),301);
        }

        //获取总页数
        $pageNum = ceil($news_count/$pagesize);
        //判断页码是否正确
        if($page > $pageNum){
            $page =  $pageNum;
        }elseif($page < 1){
            $page =  1;
        }


        //获取数据开始的地方
        $offset = ($page-1)*$pagesize;


        //查询新闻列表
        $news_list = Db::name('news')->where(array('status'=>1,'flag'=>0))->order('weigh desc,release_time desc')->limit($offset,$pagesize)->select();

        //查询置顶新闻
        $flag_list = Db::name('news')->where(array('status'=>1,'flag'=>1))->order('flag desc,weigh desc,release_time desc')->find();

        $data['pageNum'] = $pageNum;
        if (empty($news_list)){
            $data['list'] = null;
            $this->error('暂无数据',array('list'=>null),301);
        }
        $data['list'] = $news_list;
        $data['flag'] = $flag_list;
        $this->success('请求成功',$data,200);


    }


    public function news_info()
    {
        $news_id = $this->request->get('news_id');

        if (input('?get.news_id') == false){
            $this->error('参数缺失',null,300);
        }

        //更新点击量
        Db::name('news')->where('n_id', $news_id)->setInc('clicks');

        //查询新闻详情
        $info = Db::name('news')->where('n_id',$news_id)->find();


        if (empty($info)){
            $this->error('暂无该数据',array('list'=>null),301);
        }
        $data['list'] = $info;
        //获取上一条数据
        $data['before'] =  Db::name('news')->field('title,n_id')->where('weigh','>',$info['weigh'])->order('weigh asc')->find();
        //获取下一条数据
        $data['Rear'] =  Db::name('news')->field('title,n_id')->where('weigh','<',$info['weigh'])->order('weigh desc')->find();


        $this->success('请求成功',$data,200);

    }



}