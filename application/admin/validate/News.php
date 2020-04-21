<?php


namespace app\admin\validate;


use app\api\controller\Validate;

class News extends Validate
{

    /**
     * 正则
     */
    protected $regex = ['format' => '[a-z0-9_\/]+'];

    /**
     * 验证规则
     */
    protected $rule = [
        'title'  => 'require|unique:News',
    ];

    /**
     * 提示消息
     */
    protected $message = [
        'title.format' => '标题不能重复!'
    ];

    /**
     * 字段描述
     */
    protected $field = [
    ];

    /**
     * 验证场景
     */
    protected $scene = [
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