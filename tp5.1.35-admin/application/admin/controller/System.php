<?php

namespace app\admin\controller;


class System extends Common
{

    /**
     * 显示编辑资源表单页.
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
                $quest['addtime'] = strtotime(input('post.addtime'));//将时间数字化
            }
            //编辑数据
            $res = $this->system->edit($quest);

            if ($res['valid']) {
                //执行成功
                $this->success($res['msg'], 'edit');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }
        }
        //接收id
        $id = input('param.id');

        //显示当前数据
        $system = $this->system->find($id);
        $this->assign([ 'system' => $system]);

        return $this->fetch();
    }



}