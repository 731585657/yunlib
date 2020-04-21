<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/27 0027
 * Time: 09:23
 */

namespace app\api\controller;


use app\common\controller\Api;
use think\Db;

class Webconfig extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    protected function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags', 'htmlspecialchars']);
    }


    /**
     * @ApiTitle    (获取banner列表)
     * @ApiSummary  (获取banner列表)
     * @ApiMethod   (get)
     * @ApiParams   (参数无)
     * @ApiReturn   (array)
     */
    public function index_banner()
    {
        $banner_list = Db::name('webconfig')->where('type',1)->select();
       if ($banner_list){
           $this->success('请求成功',array('list'=>$banner_list),200);
       }else{
           $this->success('暂无数据',array('list'=>null),301);
       }
    }

    /**
     * @ApiTitle    (获取网站设置)
     * @ApiSummary  (获取网站设置)
     * @ApiMethod   (get)
     * @ApiParams   (参数无)
     * @ApiReturn   (array)
     */
    public function index_config()
    {
        //查询站点名称
        $site_name = Db::name('config')->field('id,type,title,value')->where('name','name')->find();

        //查询备案号
        $site_beian = Db::name('config')->field('id,type,title,value')->where('name','beian')->find();


        //查询友情链接
        $site_url = Db::name('config')->field('id,type,title,value')->where('name','url')->find();
        //var_dump(json_decode($site_url['value'],true));
        //查询微信图片
        $site_img = Db::name('webconfig')->field('id,title,image')->where('type',2)->select();
        //var_dump($site_img);
        //公司地址
        $site_address = Db::name('config')->field('id,type,title,value')->where('name','address')->find();

        //查询坐标
        $site_coordinate = Db::name('config')->field('id,type,title,value')->where('name','coordinate')->find();
        //var_dump($site_address);die;
        $site_address['coordinate'] = $site_coordinate['value'];
        //查询公司电话
        $site_phone = Db::name('config')->field('id,type,title,value')->where('name','phone')->find();
        //查询公司QQ
        $site_qq = Db::name('config')->field('id,type,title,value')->where('name','qq_number')->find();
        //查询公司email
        $site_email = Db::name('config')->field('id,type,title,value')->where('name','email')->find();

        $data['site_name'] = $site_name;
        $data['site_beian'] = $site_beian;
        $data['site_url'] = $site_url;
        $data['site_img'] = $site_img;
        $data['site_address'] = $site_address;
        $data['site_phone'] = $site_phone;
        $data['qq'] = $site_qq;
        $data['email'] = $site_email;

        $this->success('请求成功',$data,200);
    }



}