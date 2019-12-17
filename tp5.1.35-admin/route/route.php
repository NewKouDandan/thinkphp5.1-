<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


//首页
\think\facade\Route::rule('/','index/index');//rule方法时，第三个参数不能用来设置参数  get、post可以用第三个参数来设置参数
//或者
//\think\facade\Route::rule('/','index/index')->ext();//ext函数控制后缀，没有参数则表示不允许后缀

//新闻
\think\facade\Route::get('news/:id','news/detail',['ext'=>'html']);
//或者
//\think\facade\Route::get('news/:id','news/detail')->ext('html');
\think\facade\Route::get('news','news/index',['ext'=>'']);
\think\facade\Route::get('news/list/[:smallclassid]','news/index',['ext'=>'']);

//关于我们
\think\facade\Route::get('about','about/index',['ext'=>'']);

//联系我们
\think\facade\Route::get('contact','contact/index',['ext'=>'']);




return [

];
