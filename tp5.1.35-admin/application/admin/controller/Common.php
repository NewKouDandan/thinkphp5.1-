<?php

namespace app\admin\controller;

use app\common\model\Ads;
use app\common\model\Adsclass;
use app\common\model\Cases;
use app\common\model\Casesclass;
use app\common\model\Manager;
use app\common\model\Message;
use app\common\model\News;
use app\common\model\Newsclass;
use app\common\model\Staticclass;
use app\common\model\Statics;
use app\common\model\Staticsclass;
use app\common\model\System;
use think\App;
use think\Controller;
use think\Request;

class Common extends Controller
{

    public function __construct(App $app)
    {
        parent::__construct($app);

        //执行登录验证
        //if语句的写法相当于$_SESSION['admin']['id']
        if(!session('admin.id')){
            $this->redirect('admin/login/index');
        }

        $this->news=new News();
        $this->newsclass=new Newsclass();

        $this->statics=new Statics();
        $this->staticclass=new Staticsclass();

        $this->ads=new Ads();
        $this->adsclass=new Adsclass();

        $this->system=new System();

        $this->manager=new Manager();

        $this->message=new Message();

    }

    //转至登录界面
    public function ceshi(Request $request = null){
        //执行登录验证
        //if语句的写法相当于$_SESSION['admin']['id']
        if(!session('admin.id')){
            $this->redirect('admin/login/index');
        }

        $this->news=new News();
        $this->newsclass=new Newsclass();

        $this->statics=new Statics();
        $this->staticclass=new Staticsclass();

        $this->ads=new Ads();
        $this->adsclass=new Adsclass();

        $this->system=new System();

        $this->manager=new Manager();

        $this->message=new Message();
    }

}
