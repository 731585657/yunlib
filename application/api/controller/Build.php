<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/31 0031
 * Time: 15:29
 */

namespace app\api\controller;


use app\common\controller\Api;
use think\Cache;
use think\Db;

class Build extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    protected function _initialize()
    {
        parent::_initialize();

        //设置过滤方式
        $this->request->filter(['strip_tags', 'htmlspecialchars']);
    }

    public function build_index()
    {
        $page = $this->request->get('page');
        $pagesize =  $this->request->get('pagesize');

        if (input('?get.page') == false | input('?get.pagesize') == false ){
            $this->error('参数缺失',null,300);
        }


        $count = Db::name('Build')->where('status',1)->count();

        $pageNum = ceil($count/$pagesize);

        $offset = ($page-1)*$pagesize;

        $list  = Db::name('Build')->field('id,title,image,intro,clicks,release_time')->where('status',1)->order('weigh desc,release_time desc')->limit($offset,$pagesize)->select();
        if ($list === false){
            $data['list'] = null;
            $this->error('查询出错',$data,400);
        }else{
            if (empty($list)){
                $data['list'] = null;
                $this->error('暂无数据',$data,301);
            }else{
                $data['list'] = $list;
                $data['pageNum'] = $pageNum;
                $this->success('请求成功',$data,200);
            }
        }


    }

    public function build_info()
    {
        $id = $this->request->get('id');
        if (input('?get.id') == false ){
            $this->error('参数缺失',null,300);
        }

        Db::name('Build')->where('id',$id)->setInc('clicks');

        $list  = Db::name('Build')->where('id',$id)->find();
        if ($list === false){
            $data['list'] = null;
            $this->error('查询出错',$data,400);
        }else{
            if (empty($list)){
                $data['list'] = null;
                $this->error('暂无数据',$data,301);
            }else{
                $data['list'] = $list;
                $this->success('请求成功',$data,200);
            }
        }

    }


}