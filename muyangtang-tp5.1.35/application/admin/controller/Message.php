<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Message extends Common
{

    /**
     * 资源显示列表
     * @return mixed
     */
    public function index()
    {
        //获取页面数据
        $title = input('get.title') ?: '';

        //对数据进行审核，推荐
        if (input('?post.Action')) {
            update($this->message,input('post.Action'), input('post.nid/a'));
        }

        //查询数据
        $message = db('message')->where('name', 'like', '%' . $title . '%')->order("addtime desc")->paginate(20);
        $this->assign(['message' => $message, 'title' => $title]);//传递数据
        return $this->fetch();

    }

    public function create()
    {
        return $this->fetch();
    }


    /**
     * 添加
     */
    public function store()
    {
        if (request()->isPost()) {
            //接收输入的数据
            $quest = $this->request->post();
            //处理数据
            if (input('?post.addtime')) {
                $quest['addtime'] = strtotime(input('post.addtime'));
            }
            //存储数据
            $res = $this->message->store($quest);
            if ($res['valid']) {
                //操作成功
                $this->success($res['msg'], 'index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }
        }

    }


    /**
     * 显示编辑资源表单页.
     */
    public function edit()
    {
        if (request()->isPost()) {
            //接收数据
            $quest = $this->request->post();
            //处理数据
            if (input('?post.addtime')) {
                $quest['addtime'] = strtotime(input('post.addtime'));
            }
            //编辑数据
            $res = $this->message->edit($quest);
            if ($res['valid']) {
                //执行成功
                $this->success($res['msg'], 'index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }
        }

        //接收id
        $id = input('param.id');

        //显示当前数据b
        $message = $this->message->find($id);
        $this->assign(['message' => $message]);

        return $this->fetch();
    }


    /**
     * 删除
     */
    public function del()
    {
        if (request()->isGet()) {
            //执行删除
            $res = $this->message->where('id', input('id'))->delete();
            if ($res) {
                //成功提示
                $this->success('操作成功', 'index');
                exit;
            } else {
                $this->error('操作失败');
                exit;
            }
        }
        return $this->fetch('index');
    }

}
