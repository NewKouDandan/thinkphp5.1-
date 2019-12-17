<?php

namespace app\common\validate;

use think\Validate;

class News extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'smallclassid' => 'require',
        'title' => 'require|max:200',
        'content' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'smallclassid.require' => '请选择所属分类',
        'title.require' => "请填写标题",
        'title.max' => "标题不能超过200个字符",
        'content.require' => "请填写内容",
    ];
}
