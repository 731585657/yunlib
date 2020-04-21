<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/27 0027
 * Time: 10:11
 */

namespace app\api\controller;

use app\common\controller\Api;
use think\Db;

class Me extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    protected function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags', 'htmlspecialchars']);
    }
    //云图简介
    public function intro()
    {
        Db::name('me')->where('type', 1)->setInc('clicks');
        
        $intro = Db::name('me')->where('type',1)->find();
        $vr_img = Db::name('config')->field('id,type,title,value')->where('name','vr_img')->find();

        if ($intro){
            $this->success('请求成功',array('list'=>$intro,'vr_img'=>$vr_img),200);
        }else{
            $this->success('暂无数据',array('list'=>null),301);
        }
    }


    //服务理念
    public function idea()
    {

        Db::name('me')->where('type', 2)->setInc('clicks');

        $idea = Db::name('me')->where('type',2)->find();
        if ($idea){
            $this->success('请求成功',array('list'=>$idea),200);
        }else{
            $this->success('暂无数据',array('list'=>null),301);
        }
    }

    //云图资质
    public function certificate()
    {
        $certificate = Db::name('me')->where('type',3)->select();
        if ($certificate){
            $this->success('请求成功',array('list'=>$certificate),200);
        }else{
            $this->success('暂无数据',array('list'=>null),301);
        }
    }

    //云图招聘
    public function Recruitment()
    {
        //获取招聘列表
        $Recr = Db::name('me')->where('type',4)->select();

        //获取福利
        $site_welfare = Db::name('config')->field('id,type,title,value')->where('name','welfare')->find();

        //获取公司风采
        $site_style = Db::name('config')->field('id,type,title,value')->where('name','style')->find();

        //招聘联系方式
        $site_contact = Db::name('config')->field('id,type,title,value')->where('name','contact')->find();
        //var_dump($site_contact);die;
        if ($Recr){
            $this->success('请求成功',array('list'=>$Recr,'welfare'=>$site_welfare,'site_style'=>$site_style,'site_contact'=>$site_contact),200);
        }else{
            $this->success('暂无数据',array('list'=>null),301);
        }

    }


    public function get(){
        $data = file_get_contents('http://wz.yunlib.cn/Api/Me/Recruitment');
        echo $data;
    }

}