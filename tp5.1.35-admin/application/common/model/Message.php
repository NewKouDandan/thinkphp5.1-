<?php

namespace app\common\model;

use think\facade\Validate;
use think\Model;

class Message extends Model
{
    protected $pk = 'id';
    protected $table = 'sw_message';


    /**
     * 后端分页查询列表数据
     * @param $classid
     * @param int $num
     * @return \think\Paginator
     */
    public function adminList($classid, $num = 20)
    {
        return $this->where('classid', $classid)->order('addtime desc')->paginate($num, false, ['query' => request()->get()]);
    }

    /**
     * 添加
     * @param array $data
     * @return array
     */
    public function store($data)
    {
        //1.执行验证
        $validate = Validate::make(
            [
                'name' => 'require',
                'phonenum' => 'require',
            ], [
                'name.require' => '请输入姓名',
                'phonenum.require' => '请输入联系方式',
            ]
        );
        if (!$validate->check($data)) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }

        //2、存储数据
        $result = $this->save($data);

        if ($result) {
            return ['valid' => 1, 'msg' => "成功"];
        } else {
            return ['valid' => 0, 'msg' => $this->getError()];
        }
    }

    /**
     * 编辑数据
     * @param $data
     * @return array
     */
    public function edit($data)
    {
        //1.执行验证
        $validate = Validate::make(
            [
                'name' => 'require',
                'phonenum' => 'require',
            ], [
                'name.require' => '请输入姓名',
                'phonenum.require' => '请输入联系方式',
            ]
        );
        if (!$validate->check($data)) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }

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
