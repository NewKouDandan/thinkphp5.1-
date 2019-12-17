<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//+----------------------------------------------------------------------
//   后端 常用公共函数
//+----------------------------------------------------------------------

//审核状态
function getchecked($checked)
{
    switch ($checked) {
        case "0":
            return "<font color=>待审核</font>";
            break;
        case "1":
            return "<font color=blue>已审核</font>";
            break;
        default:
            return "未知";
            break;
    }
}


//推荐状态
function getcommend($commend)
{
    switch ($commend) {
        case "0":
            return "<font color=>普通</font>";
            break;
        case "1":
            return "<font color=blue>推荐</font>";
            break;
        default:
            return "未知";
            break;
    }
}

/**
 * 更新后台部分字段常用
 * @param $db //指向模型
 * @param $action //操作
 * @param $ids //ids
 */
function update($db, $action, $ids)
{
    switch ($action) {
        case 'Pass':
            $db->whereIn('id', $ids)->update(['checked' => 1]);
            break;
        case 'NoPass':
            $db->whereIn('id', $ids)->update(['checked' => 0]);
            break;
        case 'Commend':
            $db->whereIn('id', $ids)->update(['commend' => 1]);
            break;
        case 'NoCommend':
            $db->whereIn('id', $ids)->update(['commend' => 0]);
            break;
        case 'Del':
            $db->whereIn('id', $ids)->delete();
            break;
    }

}



//+----------------------------------------------------------------------
//  前端 常用公共函数
//+----------------------------------------------------------------------


/**
 * 给编辑器里的图片加上域名地址
 * @param string $content 编辑器里的内容
 * @param string $rooturl 域名地址
 * @return mixed|string
 */
function replace_img_url($content = "", $rooturl = "")
{
    $pregRule = "/<[img|IMG].*?src=[\'|\"].*?/";
    $content = preg_replace($pregRule, '<img src="' . $rooturl . '${1}', $content);
    return $content;
}

