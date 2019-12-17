<?php

namespace app\common\validate;

use think\Validate;

class Newsclass extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'cnname'=>'require',
        'parentid'=>'require',
        'sortid'=>'require|number'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'cnname.require'=>"请填写分类名称",
        'parentid.require'=>"请选择父类类别",
        'sortid.require'=>"排序必须为数字",
        'sortid.number'=>'排序必须为数字'
    ];
}
