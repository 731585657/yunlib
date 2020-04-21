<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/8 0008
 * Time: 10:19
 */

namespace app\admin\validate;




use think\Validate;

class Projecttype extends Validate
{
    protected $rule = [
        'title'  =>  'require|max:25',
        'image' =>  'require',
    ];

    protected $message  =   [
        'name.require' => '标题必须',
        'name.max'     => '标题最多不能超过25个字符',
        'image.require'  => '排序必须',
    ];

}