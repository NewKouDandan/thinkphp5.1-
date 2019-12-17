<?php

namespace app\common\validate;

use think\Validate;

class Statics extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'classid' => 'require',
        'title' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'class.require' => "请选择分类",
        'title.require' => "请填写单页标题",
    ];
}
