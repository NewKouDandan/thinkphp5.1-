<?php

namespace app\common\model;

use Houdunwang\Arr\Arr;
use think\Model;

class Newsclass extends Model
{
    protected $pk = 'id';

    /**
     * 添加分类
     * @param $data
     * @return array
     */
    public function store($data)
    {
        //1、验证数据
        $validate = new \app\common\validate\Newsclass();
        $res = $validate->check($data);
        if (!$res) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }

        //2、存储数据
        $result = $this->save($data);
        //执行验证
        if ($result) {
            return ['valid' => 1, 'msg' => "输入成功"];
        } else {
            return ['valid' => 0, 'msg' => $this->getError()];
        }
    }

    /**
     * 编辑
     */
    public function edit($data)
    {
        //1、验证数据
        $validate = new \app\common\validate\Newsclass();
        $res = $validate->check($data);
        if (!$res) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }

        //存储数据
        $result = $this->save($data, [$this->pk => $data['id']]);
        if ($result) {
            //执行成功
            return ['valid' => 1, 'msg' => '编辑成功'];
        } else {
            return ['valid' => 0, 'msg' => $this->getError()];
        }
    }


    /**
     *  获取子集以及自己
     * @param $id   //当前分类id
     * @return array
     */
    public function getCateData($id)
    {
        $classids = $this->getSon(db('newsclass')->select(), $id);
        $classids[] = $id;
        $arr=new Arr();
        return $arr->tree(db('newsclass')->order('sortid asc')->whereNotIn('id', $classids)->select(),'cnname',$fieldPri='id',$fieldPid='parentid');
    }

    /**
     * 获取子集
     * @param $data //条件查询出的数据集
     * @param $id   //当前分类id
     * @return array
     */
    public function getSon($data, $id)
    {
        static $temp = [];

        foreach ($data as $k=>$v)
        {
            if($id==$v['parentid'])
            {
                $temp[]=$v['id'];
                $this->getSon($data,$v['id']);
            }
        }
        return $temp;

    }

    /**
     * 获取所有分类-树状结构
     * @return mixed
     */
    public function getCategory()
    {
        $arr=new Arr();
        return  $arr->tree(db('newsclass')->order('sortid asc')->select(),'cnname',$fieldPri='id',$fieldPid='parentid');
    }

    /**
     * 有条件查询分类-树状结构
     * @param $select //数据结果集
     * @return array
     */
    public function getCate($select)
    {
        $arr=new Arr();
        return  $arr->tree($select,'cnname',$fieldPri='id',$fieldPid='parentid');
    }

}
