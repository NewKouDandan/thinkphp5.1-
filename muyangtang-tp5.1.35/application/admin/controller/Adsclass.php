<?php

namespace app\admin\controller;

use think\Model;

class Adsclass extends Common
{

    /**
     * 查询数据
     * @return mixed
     */
    public function index()
    {
        //获取列表数据
        $categorys = $this->adsclass->getCategory();
        $this->assign('categorys', $categorys);
        return $this->fetch();

    }

    /**
     * 添加数据页面
     * @return mixed
     */
    public function create()
    {
        $categorys = $this->adsclass->getCategory();
        $this->assign('categorys', $categorys);

        return $this->fetch();
    }

    /**
     * 添加数据
     */
    public function store()
    {
        if (request()->isPost()) {
            //执行添加
            $result = $this->adsclass->store(input('post.'));
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
     * 编辑数据
     * @return mixed
     */
    public function edit()
    {
        if (request()->isPost()) {
            //执行编辑
            $res = $this->adsclass->edit(input('post.'));
            if ($res['valid']) {
                //执行成功
                $this->success($res['msg'], 'index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }
        }

        //接收数据
        $id = input('param.id');
        //查询数据
        $category = $this->adsclass->find($id);
        //查询该分类和其子分类
        $categorys = $this->adsclass->getCateData($id);

        $this->assign(['categorys' => $categorys, 'category' => $category]);

        return $this->fetch();
    }

    /**
     * 删除数据
     */
    public function del()
    {
        if (request()->isGet()) {
            //执行删除
            $res = $this->adsclass->where('id', input('id'))->delete();
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