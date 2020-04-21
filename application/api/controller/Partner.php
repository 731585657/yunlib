<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/28 0028
 * Time: 17:29
 */

namespace app\api\controller;


use app\common\controller\Api;
use think\Db;

class Partner extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    protected function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags', 'htmlspecialchars']);
    }

    public function partner_index()
    {
        $Partner_list = Db::name('Partner')->field('id,title,logo,url,type')->where('status',1)->order('weigh desc,create_time desc')->select();

        if ($Partner_list){
            $this->success('请求成功',array('list'=>$Partner_list),200);
        }else{
            $this->success('请求成功',array('list'=>null),301);
        }
    }


    public function partner_info()
    {
        $id = $this->request->get('id');

        if (input('?get.id') == false){

            $this->error('参数缺失',null,300);
        }


        $Partner_list = Db::name('Partner')->where('id',$id)->find();
        if ($Partner_list === false){
            $this->error('请求失败',null,400);
        }else{
            if ($Partner_list){
                $this->success('请求成功',array('list'=>$Partner_list),200);
            }else{
                $this->success('请求成功',array('list'=>null),301);
            }
        }

    }

}