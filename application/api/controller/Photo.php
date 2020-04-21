<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/12 0012
 * Time: 11:47
 */

namespace app\api\controller;


use app\common\controller\Api;
use think\Db;

class Photo extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    protected function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags', 'htmlspecialchars']);
    }

    public function photo_list()
    {
        $page = $this->request->get('page');
        $pagesize = $this->request->get('pagesize');
        if (input('?get.page') == false | input('?get.pagesize') == false ){
            $this->error('参数缺失',null,300);
        }

        //根据分类id查询所属产品总数
        $count = Db::name('photo')->where('status',1)->count();
        //得到总页数
        $pageNum = ceil($count/$pagesize);
        if($page > $pageNum){
            $page =  $pageNum;
        }elseif($page < 1){
            $page =  1;
        }
        $offset=($page-1)*$pagesize;//开始去数据的位置

        $list = Db::name('photo')->field('id,title,intro,image,images,clicks,release_time')->where('status',1)->order('weigh desc,release_time desc')->limit($offset,$pagesize)->select();
        if ( $list === false){
            $this->error('查询失败',null,400);
        }else{



            if ($list){
                $this->success('请求成功',array('list'=>$list,'pageNum'=>$pageNum),200);
            }else{
                $this->error('暂无数据',null,301);
            }
        }

    }

}