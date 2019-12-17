<?php

namespace app\common\validate;

use think\Validate;

class Ads extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'adsposition' => "require",
        'adsname' => 'require',
        'adsfile' => 'require',
//        'sortid'=>'num',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'adsposition.require' => "请选择分类",
        'adsname.require' => "请填写名称",
        'adsfile.require' => "请选择文件",
//        'sortid.num'=>'排序必须为数字',
    ];
}
