<?php

namespace app\common\model;

use think\facade\Validate;
use think\Model;

class Manager extends Model
{
    //声明主键
    protected $pk = 'id';
    //此处需要设置当前模型对应的完整的数据表名称，就算在config中已经设置了表的前缀
    protected $table = 'sw_manage';

    /*
     * 修改密码
     */
    public function changepassword($data)
    {

        //1、验证数据
        $validate = new \app\common\validate\Changepw();
        $res = $validate->check($data);
        if (!$res) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        } else {

            //2.原始密码是否正确
            $userInfo = $this->where('id', session('admin.id'))->where('password', md5($data['password']))->find();
            if (!$userInfo) {
                return ['valid' => 0, 'msg' => "原始密码不正确"];
            }

            //3.修改密码
            // save方法第二个参数为更新条件
            $res = $this->save([
                'password' => md5($data['new_password']),
            ], [$this->pk => session('admin.id')]);
            if ($res) {
                return ['valid' => 1, 'msg' => "修改密码成功"];
            } else {
                return ['valid' => 0, 'msg' => "修改密码不成功"];
            }
        }
    }

    /**
     * 添加
     * @param $data
     * @return array
     */
    public function store($data)
    {
        //1.执行验证  单独验证
        $validate = Validate::make(
            ['username' => 'require',
                'password' => 'require',
            ], ['username.require' => '请输入用户姓名',
                'password.require' => '请输入用户密码',

            ]
        );
        if (!$validate->check($data)) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }

        //2、存储数据
        $result = $this->save($data);
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
        //1.执行验证
        $validate = Validate::make(
            ['username' => 'require',
                'password' => 'require',
            ], ['username.require' => '请输入用户姓名',
                'password.require' => '请输入用户密码',
            ]
        );
        if (!$validate->check($data)) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }

        //2.更新数据
        $res = $this->save($data, [$this->pk => $data['id']]);
        if ($res) {
            return ['valid' => 1, 'msg' => "编辑成功"];
        } else {
            return ['valid' => 0, 'msg' => $this->getError()];
        }

    }
}
