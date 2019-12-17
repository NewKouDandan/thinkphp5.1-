<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;


class Statics extends Common
{

    /**
     * 显示资源列表
     * @return mixed
     */
    public function index()
    {

        //接收页面参数
        if (input('?post.Action')) {
            update($this->statics,input('post.Action'), input('post.nid/a'));//调用update()函数，接收数组时后加/a
        }
        $title = input('get.title') ?: '';
        $classid = input('get.classid') ?: 0;

        //查询数据
        $statics = $this->statics->adminLists($classid, $title);
        //查询分类
        $categorys = $this->staticclass->getCategory();

        $this->assign(['statics' => $statics, 'title' => $title, 'categorys' => $categorys, 'classid' => $classid]);

        return $this->fetch();

    }

    /**
     * 添加页面
     * @return mixed
     */
    public function create()
    {
        //查询树状分类
        $categorys = $this->staticclass->getCategory();
        $this->assign('categorys', $categorys);
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
            //存入数据
            $res = $this->statics->store($quest);
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
     *
     * @param  int $id
     * @return \think\Response
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
            $res = $this->statics->edit($quest);
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

        //显示当前数据
        $statics = $this->statics->find($id);

        //获取分类
        $categorys = $this->staticclass->getCategory();

        $this->assign(['statics' => $statics, 'categorys' => $categorys]);

        return $this->fetch();
    }


    /**
     * 删除
     */
    public function del()
    {
        if (request()->isGet()) {

            $res = $this->statics->where('id', input('id'))->delete();
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
        //获取列表数据
        $field = $this->statics->select();
        $this->assign('field', $field);

    }

}
