<?php

namespace app\common\model;

use think\Model;

class Ads extends Model
{
    protected $pk = 'id';
    protected $field = true;
    protected $table = 'sw_ads';


    /**
     * 后端分页查询列表数据
     * @param string $title     标题
     * @param int $smallclassid 分类id
     * @param int $num          查询数据条数
     * @return \think\Paginator
     */
    public function adminList($title = '', $smallclassid = 0, $num = 20)
    {
        return db('ads')->alias('a')
            //关联查询
            ->join('adsclass c', 'a.adsposition=c.id')
            //允许显示字段
            ->field('a.adsname,a.linkurl,a.sortid,a.id,c.cnname,a.checked')
            //查询数据条件 1、title查询  2、分类查询
            ->where(function ($query) use ($title) {
                if (!empty($title)) {
                    $query->where('adsname', 'like', '%' . $title . '%');
                }
            })->where(function ($query) use ($smallclassid) {
                if ($smallclassid > 0) {
                    $query->where('adsposition', $smallclassid);
                }
            })
            ->order('sortid asc,addtime desc')
            //分页查询
            ->paginate($num, false, ['query' => request()->get()]);
    }


    /**
     * 添加数据
     * @param array $data
     * @return array
     */
    public function store($data)
    {
        //1、验证数据
        $validate = new \app\common\validate\Ads();
        $res = $validate->check($data);
        if (!$res) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        } else {
            //2、存储数据
            $result = $this->save($data);
            //3、判断存入成功与否
            if ($result) {
                return ['valid' => 1, 'msg' => "输入成功"];
            } else {
                return ['valid' => 0, 'msg' => $this->getError()];
            }
        }
    }

    /**
     * 编辑数据
     * @param $data //编辑数据
     * @return array
     */
    public function edit($data)
    {
        //1、验证数据
        $validate = new \app\common\validate\Ads();
        $res = $validate->check($data);
        if (!$res) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        } else {
            //2、存储数据
            $result = $this->save($data, [$this->pk => $data['id']]);

            if ($result) {
                //执行成功
                return ['valid' => 1, 'msg' => '编辑成功'];
            } else {
                return ['valid' => 0, 'msg' => $this->getError()];
            }
        }
    }

}
