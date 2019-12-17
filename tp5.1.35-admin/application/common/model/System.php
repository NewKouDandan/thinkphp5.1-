<?php

namespace app\common\model;

use think\facade\Validate;
use think\Model;

class System extends Model
{
    protected $pk = 'id';
    protected $field = true;
    protected $table='sw_system';

    /**
     * 编辑
     * @param $data
     * @return array
     */
    public function edit($data)
    {
        //1、执行验证 单独验证
        $validate = Validate::make
        (
            ['web_name' => 'require',
                'web_title' => 'require',
            ], ['web_name.require' => '请输入名称',
                'web_title.require' => '请输入标题',
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
