<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/31 0031
 * Time: 15:51
 */

namespace app\api\controller;




use app\common\controller\Api;
use think\Cache;
use think\Db;

class Project extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    protected function _initialize()
    {
        parent::_initialize();

        //设置过滤方式
        $this->request->filter(['strip_tags', 'htmlspecialchars']);
    }

    public function ject_index()
    {



        $page = $this->request->get('page');
        $pagesize =  $this->request->get('pagesize');
        $type =  $this->request->get('type_id');


        if (input('?get.page') == false | input('?get.pagesize') == false ){

            $this->error('参数缺失',null,300);
        }


        if (empty($type)){
            $map['status'] = 1;
            //$map['type_id'] = $type;
            $count = Db::name('Project')->where($map)->count();

            $pageNum = ceil($count/$pagesize);

            $offset = ($page-1)*$pagesize;

            $list  = Db::name('Project')->field('id,title,image,intro,clicks,release_time')->where($map)->order('weigh desc,release_time desc')->limit($offset,$pagesize)->select();

            //获取当前类型
            $type_info = null;

        }else{

            $map['status'] = 1;
            $map['type_id'] = $type;
            $count = Db::name('Project')->where($map)->count();

            $pageNum = ceil($count/$pagesize);

            $offset = ($page-1)*$pagesize;

            $list  = Db::name('Project')->field('id,title,image,intro,clicks,release_time')->where($map)->order('weigh desc,release_time desc')->limit($offset,$pagesize)->select();

            //获取当前类型
            $type_info = Db::name('ProjectType')->field('id,title')->where('id',$type)->find();
        }


        if ($list === false){
            $data['list'] = null;
            $this->error('查询出错',$data,400);
        }else{
            if (empty($list)){
                $data['list'] = null;
                $this->error('暂无数据',$data,301);
            }else{
                $data['list'] = $list;
                $data['type_info'] = $type_info;
                $data['pageNum'] = $pageNum;
                $this->success('请求成功',$data,200);
            }
        }
    }


    public function ject_info()
    {
        $id = $this->request->get('id');
        if (input('?get.id') == false ){
            $this->error('参数缺失',null,300);
        }

        Db::name('Project')->where('id',$id)->setInc('clicks');

        $list  = Db::name('Project')->where('id',$id)->find();

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

    public function  ject_type()
    {
        $list = Db::name('ProjectType')->field('id,title')->where('status',1)->select();
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