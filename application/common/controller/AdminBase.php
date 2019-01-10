<?php
namespace app\common\controller;

use think\Controller;

/**
 * 后台
 */
use app\common\controller\Auth;
class AdminBase extends Auth
{
	
	public function _initialize()
	{
		 parent::_initialize();
		if(!session('admin')){
			$this->error('请登陆','/admin/login/login');
		}

	}
}