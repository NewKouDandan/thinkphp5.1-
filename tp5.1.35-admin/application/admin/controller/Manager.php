<?php

namespace app\admin\controller;

use think\Controller;

class Manager extends Common
{

    /**
     * 显示资源列表
     * @return mixed
     */
    public function index()
    {
        //查询后台管理员用户
        $manager = $this->manager->order('logintime desc')->select();
        $this->assign(['manager' => $manager]);

        return $this->fetch('index');
    }

    /**
     * 修改密码 的 传递处理函数
     */
    public function changepassword()
    {
        if (request()->isPost()) {

            //执行模型函数
            $res = $this->manager->changepassword(input('post.'));
            if ($res['valid']) {
                //清除登录session()的值
                session(null);
                //执行成功
                $this->success($res['msg'], 'admin/manager/changepassword');
                exit();
            } else {
                $this->error($res['msg']);
                exit;
            }
        }
        return $this->fetch();
    }


    public function create()
    {
        return $this->fetch('index');
    }

    /**
     * 添加用户
     * @return mixed
     */
    public function managerstore()
    {
        if (request()->isPost()) {

            //接收输入的数据
            $quest = $this->request->post();

            //对密码进行处理
            $quest['password'] = md5($quest['password']);
            //存储数据
            $res = $this->manager->store($quest);

            if ($res['valid']) {
                //操作成功
                $this->success($res['msg'], 'admin/manager/index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }
        }

        return $this->fetch();
    }

    /**
     * 编辑
     * @return mixed
     */
    public function edit()
    {
        if (request()->isPost()) {

            $quest = $this->request->post();

            //判断是否修改密码
            if ($quest['password'] == '') {
                unset($quest['password']);
            } else {
                $quest['password'] = md5($quest['password']);
            }
            //通过模型编辑数据
            $res = $this->manager->edit($quest);
            if ($res['valid']) {
                //执行成功
                $this->success($res['msg'], 'admin/manager/index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }
        }
        //接收id
        $id = input('param.id');

        //显示当前数据
        $manager = $this->manager->find($id);
        $this->assign(['manager' => $manager]);

        return $this->fetch();
    }

    /**
     * 删除
     */
    public function del()
    {
        if (request()->isGet()) {

            $res = $this->manager->where('id', input('id'))->delete();
            //执行删除
            if ($res) {
                //成功提示
                $this->success('操作成功', 'index');
                exit;
            } else {
                $this->error('操作失败');
                exit;
            }
        }
    }
}
