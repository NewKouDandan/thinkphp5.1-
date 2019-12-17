<?php

namespace app\admin\controller;

use think\Controller;
use think\Model;

class Newsclass extends Common
{

    /**
     * 显示资源列表
     * @return mixed
     */
    public function index()
    {
        //获取列表数据
        $categorys = $this->newsclass->getCategory();
        $this->assign('categorys', $categorys);
        return $this->fetch();

    }

    /**
     * 添加页面
     * @return mixed
     */
    public function create()
    {
        //查询分类-树状结构
        $categorys = $this->newsclass->getCategory();
        $this->assign('categorys', $categorys);

        return $this->fetch();
    }


    /**
     * 添加
     */
    public function store()
    {
        if (request()->isPost()) {
            //存储数据
            $result = $this->newsclass->store(input('post.'));
            if ($result['valid']) {
                //操作成功
                $this->success($result['msg'], 'index');
                exit;
            } else {
                $this->error($result['msg']);
                exit;
            }
        }
    }

    /**
     * 编辑
     * @return mixed
     */
    public function edit()
    {
        if (request()->isPost()) {
            //编辑数据
            $res = $this->newsclass->edit(input('post.'));
            if ($res['valid']) {
                //执行成功
                $this->success($res['msg'], 'index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }
        }

        //获取数据
        $id = input('param.id');
        //查询数据
        $category=$this->newsclass->find($id);
        //查询分类-树状结构
        $categorys = $this->newsclass->getCateData($id);

        $this->assign(['categorys'=>$categorys,'category'=>$category]);

        return $this->fetch();
    }

    /**
     * 删除
     */
    public function del()
    {
        if (request()->isGet()) {

            $res = $this->newsclass->where('id', input('id'))->delete();
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