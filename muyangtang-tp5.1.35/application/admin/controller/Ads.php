<?php

namespace app\admin\controller;

use app\common\model\Adsclass;
use think\Db;


class Ads extends Common
{

    /**
     * 显示资源列表
     * @return mixed
     */
    public function index()
    {
        //接收页面参数
        if (input('?post.Action')) {
            //更新数据
            update($this->ads, input('post.Action'), input('post.nid/a'));//调用update()函数
        }
        $title = input('get.title') ?: '';
        $smallclassid = input('get.smallclassid') ?: 0;
        //页面传参
        $this->assign(['title' => $title, 'smallclassid' => $smallclassid]);

        //调用模型中的函数，查询数据
        $ads = $this->ads->adminList($title, $smallclassid);
        $this->assign('ads', $ads);

        //调用模型中的函数，查询分类
        $categorys = $this->adsclass->getCategory();
        $this->assign('categorys', $categorys);

        //模块加载
        return $this->fetch();
    }


    /**
     * 添加数据页面
     * @return mixed
     */
    public function create()
    {
        //调用模型中的函数，查询分类
        $categorys = $this->adsclass->getCategory();
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
            $quest['addtime'] = time();
            //存入数据
            $res = $this->ads->store($quest);
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
     * 编辑
     * @return mixed
     */
    public function edit()
    {
        if (request()->isPost()) {
            //接收输入的数据
            $quest = $this->request->post();
            //编辑数据
            $res = $this->ads->edit($quest);
            if ($res['valid']) {
                //执行成功
                $this->success($res['msg'], 'index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }
        }

        //获取分类
        $categorys = (new Adsclass())->getCategory();
        //接收id
        $id = input('param.id');
        //显示当前数据b
        $ads = $this->ads->find($id);
        $this->assign(['ads' => $ads, 'categorys' => $categorys]);

        return $this->fetch();
    }


    /**
     * 删除
     */
    public function del()
    {
        if (request()->isGet()) {
            //执行删除
            $res = $this->ads->where('id', input('id'))->delete();
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
