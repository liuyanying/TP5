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
namespace app\index\controller;

use think\Controller;

class Common extends Controller
{
    public function _initialize()//登录页
    {
		if(cookie('username')!='')
		{
			session('username',cookie('username'));
		}
		if(session('username')!='')//记住用户的id  登录信息
		{
			$this->redirect('index/welcome');
		}
    }
}