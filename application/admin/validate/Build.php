<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/30 0030
 * Time: 16:45
 */

namespace app\admin\validate;


use think\Validate;

class Build extends Validate
{

    protected $rule = [
        'title'  =>  'require|max:25',
        'weigh' =>  'require|number',
    ];

    protected $message  =   [
        'name.require' => '标题必须',
        'name.max'     => '标题最多不能超过25个字符',
        'weigh.number'   => '排序必须是数字',
        'weigh.require'  => '排序必须',
    ];

}