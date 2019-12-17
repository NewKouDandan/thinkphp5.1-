<?php

namespace app\admin\controller;

use think\Controller;
use think\Model;

class Staticsclass extends Common
{

    /**
     * 显示资源列表
     * @return mixed
     */
    public function index()
    {
        //获取列表数据
        $categorys = $this->staticclass->getCategory();
        $this->assign('categorys', $categorys);
        return $this->fetch();
    }

    /**
     * 添加页面传参
     * @return mixed
     */
    public function create()
    {
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
            $result = $this->staticclass->store(input('post.'));
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
            $res = $this->staticclass->edit(input('post.'));
            if ($res['valid']) {
                //执行成功
                $this->success($res['msg'], 'index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }
        }

        $id = input('param.id');
        $category = $this->staticclass->find($id);
        //获取自己及子集
        $categorys = $this->staticclass->getCateData($id);

        $this->assign(['categorys' => $categorys, 'category' => $category]);

        return $this->fetch();
    }

    /**
     * 删除
     */
    public function del()
    {
        if (request()->isGet()) {
            $res = $this->staticclass->where('id', input('id'))->delete();
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