<?php

namespace app\common\model;

use think\Model;

class News extends Model
{
    protected $pk = 'id';
    protected $field = true;
    protected $table = 'sw_news';


    /**
     * 后端分页多条件查询列表数据
     * @param int $smallclassid 分类id
     * @param string $title 模糊匹配字段值
     * @param int $num 每页显示数据数量
     * @return \think\Paginator
     */
    public function adminLists($smallclassid = 0, $title = '', $num = 15)
    {
        return db('news')->alias('a')
            ->join('newsclass c', 'a.smallclassid=c.id')
            ->field('a.id,a.title,a.addtime,c.cnname,a.checked,a.commend')
            ->where(function ($query) use ($title) {
                if (!empty($title)) {
                    $query->where('title', 'like', '%' . $title . '%');
                }
            })->where(function ($query) use ($smallclassid) {
                if ($smallclassid > 0) {
                    $query->where('smallclassid', $smallclassid)->whereOr('FIND_IN_SET(' . $smallclassid . ', bigclassid)');
                }
            })
            ->order('commend desc,checked desc,addtime desc')
            //'query'=>request()->get() 是为了保证翻页时保存当前搜索条件
            ->paginate($num, false, ['query' => request()->get()]);
    }


    /**
     * 前端 首页不分页 多条件查询数据
     * @param int $smallclassid
     * @param int $num
     * @param string $title
     * @param int $checked
     * @return mixed
     */
    public function indexLists($smallclassid = 0, $num = 5, $title = '', $checked = 1)
    {
        return db('news')->alias('a')
            ->join('newsclass c', 'a.smallclassid=c.id')
            ->field('a.id,a.title,a.addtime,c.cnname,a.checked,a.commend,a.detail,a.smpic,a.hits,a.content')
            ->where(function ($query) use ($title) {
                if (!empty($title)) {
                    $query->where('title', 'like', '%' . $title . '%');
                }
            })
            ->where(function ($query) use ($smallclassid) {
                if ($smallclassid > 0) {
                    $query->where('smallclassid', $smallclassid)->whereOr('FIND_IN_SET(' . $smallclassid . ', bigclassid)');
                }
            })
            ->where('checked', $checked)
            ->order('commend desc,addtime desc')
            ->limit(0, $num)
            ->select();

    }


    /**
     * 前端 内页分页 多条件查询
     * @param int $smallclassid
     * @param int $num
     * @param string $title
     * @return mixed
     */
    public function lists($smallclassid = 0, $num = 10, $title = '')
    {
        return db('news')->alias('a')
            //关联分类数据表
            ->join('newsclass c', 'a.smallclassid=c.id')
            ->field('a.id,a.title,a.addtime,c.cnname,a.detail,a.smpic,a.hits,a.author,a.smallclassid')
            ->where(function ($query) use ($title) {
                if (!empty($title)) {
                    $query->where('title', 'like', '%' . $title . '%');
                }
            })->where(function ($query) use ($smallclassid) {
                if ($smallclassid > 0) {
                    $query->where('smallclassid', $smallclassid)->whereOr('FIND_IN_SET(' . $smallclassid . ', bigclassid)');
                }
            })
            ->where('checked', 1)
            ->order('commend desc,addtime desc')
            ->paginate($num, false, ['type' => 'bootstrap5']);
    }


    /**
     * 添加
     * @param array $data
     * @return array
     */
    public function store($data)
    {
        //1、验证数据
        $validate = new \app\common\validate\News();
        $res = $validate->check($data);
        if (!$res) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }

        //2、处理相关数据

        //将所有父类存入bigclassid
        $parentid1 = $this->getbigclassid($data['smallclassid'], "");
        $data['bigclassid'] = $parentid1;

        //3、存储数据
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
        $validate = new \app\common\validate\News();
        $res = $validate->check($data);
        if (!$res) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }

        //2、处理相关数据

        //将所有父类存入bigclassid
        $parentid1 = $this->getbigclassid($data['smallclassid'], "");
        $data['bigclassid'] = $parentid1;

        //3、存储数据
        $result = $this->save($data, [$this->pk => $data['id']]);
        if ($result) {
            //执行成功
            return ['valid' => 1, 'msg' => '编辑成功'];
        } else {
            return ['valid' => 0, 'msg' => $this->getError()];
        }
    }


    /**
     * 增加浏览数量
     * @param $id //对应id
     * @param $ziduan //加一的字段名称
     * @param int $num //增加的数量
     */
    public function autoAdd($id, $ziduan, $num = 1)
    {
        $row = $this->find($id);

        $d["$ziduan"] = $row["$ziduan"] + $num;

        $this->where('id', $id)->update($d);
    }


    /**
     * 搜索所有父级
     * @param $id
     * @param $str
     * @return mixed|string
     */
    public function getbigclassid($id, $str)
    {
        $row = db('newsclass')->where('id', $id)->find();

        if ($row) {
            if (empty($str)) {
                $str = $row['parentid'];
            } else {
                $str = $row['parentid'] . ',' . $str;
            }

            return $this->getbigclassid($row['parentid'], $str);
        } else {
            return $str;
        }

    }
}
