<?php

namespace app\admin\controller;

use app\common\model\Manager;
use think\Controller;
use think\Model;

class Index extends Common
{
    protected $db;

    /**
     * 加载登录界面的入口函数
     */
    public function index()
    {
        //加载模板
        return $this->fetch();//默认加载与当前方法同名的文件模板
    }


    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function main()
    {
        return $this->fetch();
    }

}
