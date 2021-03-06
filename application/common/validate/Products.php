<?php
namespace app\common\validate;

use think\Validate;

class Products extends Validate
{
    /**
     * 正则
     */
    protected $regex = ['format' => '[a-z0-9_\/]+'];

    /**
     * 验证规则
     */
    protected $rule = [

        'title' => 'require|unique:products',
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'title' => __('Title'),
        ];
        $this->message['name.format'] = __('Name only supports letters, numbers, underscore and slash');
        parent::__construct($rules, $message, $field);
    }


}