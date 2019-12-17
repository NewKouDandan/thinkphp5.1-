<?php

namespace app\admin\controller;

use think\Controller;
use app\common\model\Newsclass;
use think\db;
use think\Paginator;
use think\paginator\driver\Bootstrap;

class News extends Common
{

    /**
     * 显示资源列表
     * @return mixed
     */
    public function index()
    {
        //对数据进行审核，推荐
        if (input('?post.Action')) {
            update($this->news, input('post.Action'), input('post.nid/a'));
        }

        //获取页面需要查找的数据
        $title = input('get.title') ?: '';
        $smallclassid = input('get.smallclassid') ?: 0;

        //获取数据库数据
        $news = $this->news->adminLists($smallclassid, $title);
        $categorys = $this->newsclass->getCategory();
        //传递数据
        $this->assign(['lists' => $news, 'title' => $title, 'categorys' => $categorys, 'smallclassid' => $smallclassid]);

        return $this->fetch();
    }

    /**
     * 添加数据页面
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
            //接收输入的数据
            $quest = $this->request->post();

            //处理相关数据
            //关键字
            $quest['tags'] = str_replace('，', ',', $quest['tags']);
            //转换为时间戳
            $quest['addtime'] = input('post.addtime') ? strtotime(input('post.addtime')) : time();

            //调用model中的函数，存储数据
            $res = $this->news->store($quest);
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

            $quest = $this->request->post();

            //处理相关数据
            //关键字
            $quest['tags'] = str_replace('，', ',', $quest['tags']);
            //时间
            if (input('?post.addtime')) {
                $quest['addtime'] = strtotime(input('post.addtime'));
            }
            //更新数据
            $res = $this->news->edit($quest);

            if ($res['valid']) {
                //执行成功
                $this->success($res['msg'], 'index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }
        }
        //获取分类列表
        $categorys = $this->newsclass->getCategory();

        //接收id
        $id = input('param.id');

        //显示当前数据b
        $news = $this->news->find($id);
        $this->assign(['categorys' => $categorys, 'detail' => $news]);

        return $this->fetch();
    }


    /**
     * 删除
     */
    public function del()
    {
        if (request()->isGet()) {
            //执行删除
            $res = $this->news->where('id', input('id'))->delete();
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
