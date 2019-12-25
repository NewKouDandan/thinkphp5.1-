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
 * @param $db       //指向模型
 * @param $action   //操作
 * @param $ids      //ids
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

        case 'Download':
            $data = $db->whereIn('id', $ids)->order('id desc')->select();
            //构造列表头
            $field = array(
                'A' => array('name', '姓名'),
                'B' => array('xb', '性别'),
                'C' => array('nation', '民族'),
                'D' => array('csny', '出身年月'),
                'E' => array('idcard', '身份证号码'),
                'F' => array('nj', '就读年级'),
                'G' => array('jdxx', '之前就读学校'),
                'H' => array('xxdz', '详细地址'),
                'I' => array('fathername', '父亲姓名'),
                'J' => array('fathertel', '父亲电话'),
                'K' => array('mothername', '母亲姓名'),
                'L' => array('mothertel', '母亲电话'),
                'M' => array('wy1', '期末语文成绩'),
                'N' => array('ws1', '期末数学成绩'),
                'O' => array('we1', '期末英语成绩'),
                'P' => array('wpm1', '期末班级排名'),

            );
            //调用将数据导成xls格式数据的函数
            phpExcel($field, $data, "小升初-".date('Y-m-d'));
            break;
    }

}


/**
 * 将数据 导出为 xls 格式 成功
 * @param $headArr      //表头
 * @param $data         //查询数据
 * @param $filename     //下载文件名
 * 示例：
 * $data = db('message')->whereIn('id', $ids)->select();
 * $field = array(
 * '表格头：A第一列，B第二列...'=>array('字段名：数据表中的字段','表格第一行的介绍')
 * 'A' => array('id', '唯一标识符'),
 * 'B' => array('title', '主题'),
 * 'C' => array('phonenum', '电话'),
 * 'D' => array('addtime', '时间'),
 * );
 * phpExcel($field, $data, date('Y-m-d'));
 */
function phpExcel($headArr, $data, $filename)
{
    //引入文件
    require_once(EXTEND_PATH . 'PHPExcel/Classes/PHPExcel.php');
    //实例化
    $objPHPExcel = new PHPExcel();
    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel); //设置保存版本格式
    foreach ($data as $key => $value) {
        foreach ($headArr as $k => $v) {

            //设置报表列头
            if ($key == 0) {
                $objPHPExcel->getActiveSheet()->setCellValue($k . '1', $v[1]);


                /**
                 * 表格相关设置
                 */
                //设置固定单元格宽度
//                $objPHPExcel->getActiveSheet()->getColumnDimension($k)->setWidth(20);
                //设置单元格宽度自适应文本长度
//                $objPHPExcel->getActiveSheet()->getColumnDimension($k)->setAutoSize(true);
                //水平居中
//                $objPHPExcel->getActiveSheet()->getStyle($k . '1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //垂直居中
//                $objPHPExcel->getActiveSheet()->getStyle($k . '1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }

            //添加表格内容
            $i = $key + 2; //表格是从2开始的
            $objPHPExcel->getActiveSheet()->setCellValue($k . $i, $value[$v[0]]);


            /**
             * 表格相关设置
             */

            //设置格式为PHPExcel_Style_NumberFormat::FORMAT_NUMBER，避免某些大数字
            //被使用科学记数方式显示，配合下面的 setAutoSize 方法可以让每一行的内容
            //都按原始内容全部显示出来
            $objPHPExcel->getActiveSheet()->getStyle($k.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

            //设置单元格宽度自适应文本长度
            $objPHPExcel->getActiveSheet()->getColumnDimension($k)->setAutoSize(true);
            //水平居中
//            $objPHPExcel->getActiveSheet()->getStyle($k . $i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //垂直居中
//            $objPHPExcel->getActiveSheet()->getStyle($k . $i)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //文本自动换行
            $objPHPExcel->getActiveSheet()->getStyle($k . $i)->getAlignment()->setWrapText(true);
        }
    }

    //
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
    header("Content-Type:application/force-download");
    header("Content-Type:application/vnd.ms-execl");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");;
    header('Content-Disposition:attachment;filename=' . $filename . '.xls');
    header("Content-Transfer-Encoding:binary");
    $objWriter->save('php://output');
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



/**
 * 图片裁剪
 * @param $url                  //原图片地址 绝对地址
 * @param $w                    //裁剪宽度
 * @param $h                    //裁剪高度
 * @param string $savePath      //裁剪后图片保存地址 绝对地址
 * @param int $x                //裁剪起始x位置 默认为居中裁剪
 * @param int $y                //裁剪起始y位置
 * @return bool|mixed|string    //成功返回相对地址
 */
function cropImg($url, $w, $h, $savePath = '', $x = 0, $y = 0)
{
    //打开图片
    $image = \think\Image::open($url);
    //上传图片的宽高
    $imgW = $image->width();
    $imgH = $image->height();

    //判断原图片宽高与裁剪宽高
    if ($imgW < $w) {
        $w = $imgW;
    }
    if ($imgH < $h) {
        $h = $imgH;
    }

    //文件类型
    $type = $image->type();

    //设置裁剪图片的宽高，起始位置

    //居中裁剪
    if ($x == 0 || $y == 0) {
        $x = ($imgW - $w) / 2;
        $y = ($imgH - $h) / 2;
    }

    if ($savePath == '') {
        //保存路径
        $path = ROOT_PATH . 'public';
        $dirName = 'uploads' . DS . 'images' . DS . 'cropImg' . DS . date('Ymd', time());
        $fileName = md5(time());

        //目标目录不存在，则创建目录
        if (!file_exists($dirName)) {
//            mkdir($dirName);
            mkdir($dirName, 0777, true);
        }

        //完整路径
        $pathName = $path . DS . $dirName . DS . $fileName . '.' . $type;

    } else {
        $dirName = $savePath;

        //目标目录不存在，则创建目录
        if (!file_exists($dirName)) {
//            mkdir($dirName);
            mkdir($dirName, 0777, true);
        }

        $fileName = md5(time());
        $pathName = $dirName . DS . $fileName . '.' . $type;
    }

    //裁剪图片
    $res = $image->crop($w, $h, $x, $y)->save($pathName);

    if ($res) {
        //数据库保存地址
        $dbPath = DS . $dirName . DS . $fileName . '.' . $type;
        //将 反斜杠 替换成 斜杠
        $dbPath = str_replace("\\", '/', $dbPath);

        return $dbPath;
    } else {
        return false;
    }
}

/**
 * 缩略图
 * @param $url                  //原图片地址 绝对地址
 * @param $w                    //缩略图宽
 * @param $h                    //缩略图高
 * @param string $savePath      //保存路径 绝对地址
 * @return bool|mixed|string    //成功返回相对地址，失败false
 */
function thumbImg($url, $w, $h, $savePath = '')
{
    //打开图片
    $image = \think\Image::open($url);
    //文件类型
    $type = $image->type();
    //上传图片的宽高
    $imgW = $image->width();
    $imgH = $image->height();

    //判断原图片宽高与裁剪宽高
    if ($imgW < $w) {
        $w = $imgW;
    }
    if ($imgH < $h) {
        $h = $imgH;
    }

    if ($savePath == '') {
        //保存路径
        $path = ROOT_PATH . 'public';
        $dirName = 'uploads' . DS . 'images' . DS . 'thumbImg' . DS . date('Ymd', time());
        $fileName = md5(time());

        //目标目录不存在，则创建目录
        if (!file_exists($dirName)) {
//            mkdir($dirName);
            mkdir($dirName, 0777, true);
        }

        //完整路径
        $pathName = $path . DS . $dirName . DS . $fileName . '.' . $type;

    } else {
        $dirName = $savePath;

        //目标目录不存在，则创建目录
        if (!file_exists($dirName)) {
//            mkdir($dirName);
            mkdir($dirName, 0777, true);
        }

        $fileName = md5(time());
        $pathName = $dirName . DS . $fileName . '.' . $type;
    }


    //缩略图片  第三个参数 6 标识缩略图固定尺寸缩放类型
    $res = $image->thumb($w, $h, 6)->save($pathName);

    if ($res) {
        //数据库保存地址
        $dbPath = DS . $dirName . DS . $fileName . '.' . $type;
        //将 反斜杠 替换成 斜杠
        $dbPath = str_replace("\\", '/', $dbPath);

        return $dbPath;
    } else {
        return false;
    }

}


/**
 * 邮箱主体
 * @param $token //用来验证的token值（开发者自己构造token值，发送到用户邮箱并保存到数据库，两者对比后方便验证）
 * @return string
 */
function body($url, $token)
{

    return
        '<div class="wrapper" style="margin: 20px auto 0; width: 500px; padding-top:16px; padding-bottom:10px;">
    <div class="header clearfix">
        <a class="logo"
           href="http://sctrack.sc.gg/track/click/eyJtYWlsbGlzdF9pZCI6IDAsICJ0YXNrX2lkIjogIiIsICJlbWFpbF9pZCI6ICIxNTU3NzEzNjI0OTY0XzY3NDhfMjE2ODlfNDk4My5zYy0xMF85XzQwXzE2NC1pbmJvdW5kMCRteWxhb3d1QHZpcC5xcS5jb20iLCAic2lnbiI6ICJjMTAzMzI4Y2UzYzdjMDg4ZjIxNWY5YjkyMzk3ZmRhNCIsICJ1c2VyX2hlYWRlcnMiOiB7fSwgImxhYmVsIjogMCwgImxpbmsiOiAiaHR0cHMlM0EvL3Nlby5qdXppc2VvLmNvbSIsICJ1c2VyX2lkIjogNjc0OCwgImNhdGVnb3J5X2lkIjogMTMxMjM0fQ==.html"
           target="_blank"><b>来自Archrace的邮件</b></a>
    </div>
    <br style="clear:both; height:0">

    <div class="content"
         style="background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #E9E9E9; margin: 2px 0 0; padding: 30px;">

        <p>您好: </p>

        <p>这是您在 Archrace 上的重要邮件, 功能是进行 Archrace 帐户邮箱验证, 请点击下面的连接完成验证</p>

        <p style="border: 1px solid #DDDDDD;margin: 15px 0 25px;padding: 15px;background-color:#fafafa">
            请点击链接继续: <a href="' . $url . '/jihuo?token=' . $token . '"target=" _blank">' . $url . '/jihuo?token=' . $token . '</a>
        </p>

        <p class="footer" style="border-top: 1px solid #DDDDDD; padding-top:15px; margin-top:25px; color:#838383;">
            有任何建议或需人工帮助，请回复邮件到 <br>archrace@163.com<br>
            或登录网站：<a href="http://www.archrace.com" target="_blank">https://www.archrace.com</a><br><br><br></p>
    </div>
</div>';

}

/**
 * PHPMailer类的使用
 * 成功
 * @param $email  //收件人
 * @param $body   //邮件正文
 * @return bool
 * @throws \PHPMailer\PHPMailer\Exception
 */
function phpmail($email, $body)
{
    //实例化PHPMailer核心类
    $mail = new PHPMailer\PHPMailer\PHPMailer();

    // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    //'0'是不显示debug；'1'显示debug 默认为0
//        $mail->SMTPDebug = 1;
    // 使用smtp鉴权方式发送邮件
    $mail->isSMTP();
    // smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;

    // 链接qq域名邮箱的服务器地址
//    $mail->Host = 'smtp.qq.com';
    $mail->Host = "smtp.163.com";// 发送方的SMTP服务器地址

    // 设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = 'ssl';//163邮箱就注释
    // 设置ssl连接smtp服务器的远程服务器端口号
    $mail->Port = 465;
    //$mail->Port = 994;// 163邮箱的ssl协议方式端口号是465/994
    // 设置发送的邮件的编码
    $mail->CharSet = 'UTF-8';
    // 邮件正文是否为html编码 注意此处是一个方法
    $mail->isHTML();

    //设置邮件格式：CONTENT_TYPE_TEXT_HTML：html  默认为'text/plain'文本格式
//    $mail->ContentType = "text/html";

    // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = '发送者';
    // smtp登录的账号 QQ邮箱即可
    $mail->Username =
        '1137607952@qq.com';      //$mail->Username='...@163.com'
    // smtp登录的密码 使用生成的授权码
    $mail->Password =
//        'wujian1014123';
        'byfidzazjubwggff';       //...
    // 设置发件人邮箱地址 同登录账号 must be same as the author
    $mail->From =
        '1137607952@qq.com';          //...

    //设置发件人的昵称
    //$mail->setFrom('1137607952@qq.com','在线邮箱');
    //与$mail->FromName和$mail->From效果等同


    //接收页面参数

    // 设置收件人邮箱地址，(昵称)
//    $mail->addAddress($_POST['name'],'客户');
    // 添加多个收件人 则多次调用方法即可
    $mail->addAddress($email);

    // 添加该邮件的主题
    $mail->Subject = "邮箱验证";
//        $_POST['title'];
    // 添加邮件正文
    $mail->Body = $body;
    // 为该邮件添加附件
//        $mail->addAttachment('./example.pdf');
    // 发送邮件 返回状态
    $status = $mail->send();
    return $status;
}


