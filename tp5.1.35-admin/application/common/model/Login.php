<?php

namespace app\common\model;

use think\Model;

class Login extends Model
{

    /**
     * 登录判断
     * @param $data
     * @return array
     */
    public function login($data)
    {

        //1、验证页面是否输入数据
        $validate = new \app\common\validate\Login();
        $res = $validate->check($data);
        if (!$res) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }

        //2、验证输入的用户或密码是否正确 用户是否已经登录
        $userInfo = $this->where('username', $data['username'])->where('password', md5($data['password']))->find();

        //用户名或密码错误
        if (!$userInfo) {
            //记住用户名
            if (!empty($data['ckRemember'])) {
                session_set_cookie_params(10);//设置全局变量的生命周期
                session('admin.username', $data['username']);//设置全局变量，用于记住用户名
                session_regenerate_id(true);
            } else {
                session('admin.username', '');
            }
            return ['valid' => 0, 'msg' => '用户名或密码错误或用户已锁定'];
        } //用户名密码正确
        else {
            //3、将数据存作全局变量
            session_set_cookie_params(10800);//设置全局变量的生命周期
            session('admin.id', $userInfo['id']);//用来判断是否登录
            session('admin.username', $userInfo['username']);//用来显示当前用户
            session('admin.permissions', $userInfo['permissions']);//用户等级
            session_regenerate_id(true);

            $data['password'] = md5($data['password']);
            $data['logintime'] = time();
            $data['loginIp'] = gethostbyname($_SERVER['SERVER_NAME']);
            $this->save($data, [$this->pk => $userInfo['id']]);

            //记住用户名单选框的值存入session中，方便后面判断
            if (!empty($data['ckRemember'])) {
//                session_set_cookie_params(60);//设置全局变量的生命周期
                session('ckRemember', $data['ckRemember']);
//                session_regenerate_id(true);
            } else {
                session('ckRemember', null);
            }
            return ['valid' => 1, 'msg' => '登陆成功'];
        }
    }

}
