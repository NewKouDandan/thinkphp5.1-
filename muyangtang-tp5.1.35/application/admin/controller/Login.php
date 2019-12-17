<?php

namespace app\admin\controller;

use think\Controller;

class Login extends Controller
{
    public $username;


    /**
     * 登录页面
     * @return mixed
     */
    public function index()
    {
        if (request()->isPost()) {

            $res = (new \app\common\model\Login())->login(input('post.'));
            if ($res['valid']) {
                //说明登录成功
                $this->success($res['msg'], '/admin/index/index');
            } else {
                //登录失败
                $this->error($res['msg']);
                exit;
            }
        }

        return $this->fetch();
    }

    /**
     * 退出登录
     */
    public function logout()
    {

        //是否记住用户名
        if(!empty(session('ckRemember'))){
            session('admin.username',session('admin.username'));
        }else{
            session(null);
        }
        return $this->redirect('/admin/login/index');
    }

}
