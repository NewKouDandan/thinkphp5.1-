<?php

namespace app\common\model;

use think\Model;

class Statics extends Model
{
    protected $pk = 'id';
    protected $field = true;
    protected $table = 'sw_static';


    /**
     * 后端多条件分页查询
     * @param string $title
     * @param int $classid
     * @return \think\Paginator
     */
    public function adminLists($classid = 0, $title = '', $num = 15)
    {
        return db('static')->alias('a')
            ->join('staticclass c', 'a.classid=c.id')->field('a.id,a.title,a.addtime,c.cnname,a.sortid')
            ->where(function ($query) use ($title) {
                if (!empty($title)) {
                    $query->where('title', 'like', '%' . $title . '%');
                }
            })->where(function ($query) use ($classid) {
                if ($classid > 0) {
                    $query->where('classid', $classid);
                }
            })
            ->order('sortid asc,addtime desc')->paginate($num, false, ['query' => request()->get()]);
    }


    /**
     * 前端多条件分页查询
     * @param int $classid
     * @param int $num
     * @param string $title
     * @return \think\Paginator
     */
    public function lists($classid = 0, $num = 15, $title = '')
    {
        return db('static')
            ->where(function ($query) use ($title) {
                if (!empty($title)) {
                    $query->where('title', 'like', '%' . $title . '%');
                }
            })->where(function ($query) use ($classid) {
                if ($classid > 0) {
                    $query->where('classid', $classid);
                }
            })
            ->order('sortid asc,addtime desc')->paginate($num, false, ['query' => request()->get()]);
    }


    /**
     * 添加
     * @param array $data
     * @return array
     */
    public function store($data)
    {
        //1、验证数据
        $validate = new \app\common\validate\Statics();
        $res = $validate->check($data);
        if (!$res) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }

        //存储数据
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
     * @param $data
     * @return array
     */
    public function edit($data)
    {
        //1、验证数据
        $validate = new \app\common\validate\Statics();
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

}
