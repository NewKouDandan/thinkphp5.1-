<?php

namespace app\admin\controller;


class Waptemp extends Common
{
    //查询目录
    public $path = "../application/wap/view";


    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //获取文件名
        if (input('?param.dirname')) {
            //获取传递的根路径
            $root = input('param.root');
            //获取目录名
            $dirname = input('param.dirname');
            //构造完整路径
            $this->path = $root . '/' . $dirname;

        }
        //读取目录
        $array = $this->readir($this->path);

//        $handle = opendir($this->path);//打开文件目录
//        $arr = readdir($handle);
//        dump($arr);

        //传递参数，渲染页面
        $this->assign(['array' => $array, 'path' => $this->path]);
        return $this->fetch();
    }

    /**
     * 编辑
     * @return mixed
     */
    public function edit()
    {
        if (input("?param.root")) {
            //获取传递的根路径
            $root = input('param.root');
            //接收文件名
            $filename = input('param.filename');
            //构造路径
            $this->path = $root . '/' . $filename;
        } else {
            //获取页面传递路径
            $this->path = input('param.path');
        }

        //读取文件内容
        $content = file_get_contents($this->path);
        //保证html原样输出
        $content=str_replace("<",'&lt;',$content);
        $content=str_replace(">",'&gt;',$content);

        //获取页面修改数据
        if (input('?param.content')) {
            $content = input('param.content');
            //修改文件内容
            $res = file_put_contents($this->path, $content);

            //方法二
            //读取文件
//            $open=fopen($path,'w');
            //修改文件
//            $res=fwrite($open,$content);

            //判断修改成功与否
            if ($res) {
                //成功返回首页
                $this->success('成功', 'index');
            } else {
                //不成功停留当前页面
                $this->error($res);
            }
        }

        //传递参数，渲染页面
        $this->assign(['content' => $content, 'path' => $this->path]);
        return $this->fetch();
    }


    /**
     * 遍历目录，只读取目录最外层
     * @param  $path
     * @return   mixed
     */
    public function readir($path)
    {
        $handle = opendir($path);//打开文件目录
        //定义数组存放文件目录
        $arr = array();
        //读取文件，判断是否为空
        while (($item = readdir($handle)) !== false) {
            //考虑到"."(当前文件)和".."(上一级文件)的特殊性
            if ($item != '.' && $item != '..') {
                //判断是否为文件
                if (is_file($path . '/' . $item)) {
                    $arr['file'][] = $item;
                }
                //判断是否为目录
                if (is_dir($path . '/' . $item)) {
//                readir($path.'/'.$item);
                    $arr['dir'][] = $item;
                }
            }
        }

        closedir($handle);
        return $arr;
    }


}