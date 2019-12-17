<?php

namespace app\common\validate;

use think\Validate;

class Changepw extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'password' => 'require',
        'new_password' => 'require',
        'confirm_password' => 'require|confirm:new_password',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'password.require' => '请输入原始密码',
        'new_password.require' => '请输入新密码',
        'confirm_password.require' => '请输入确认密码',
        'confirm_password.confirm' => '确认密码与新密码不一致'
    ];
}
