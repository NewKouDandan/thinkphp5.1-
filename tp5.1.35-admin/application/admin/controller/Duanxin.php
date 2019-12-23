<?php

namespace app\admin\controller;

use app\common\model\Manager;
use think\Controller;
use think\Model;

class Duanxin extends Common
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
     * 发送短信验证码
     * @return mixed|string
     */
    public function yanzheng()
    {
        //引入配置文件
        require_once(EXTEND_PATH . '/duanxin/configSid.php');

        $param = rand(100000,999999);//验证码内容
        $mobile = $_POST['yzmtel'];//手机号码

        //发送验证码
        return configSid($mobile,$param);

    }


}
