<?php
namespace app\common\controller;
 
use app\common\controller\Cors;
/**
 * app登陆验证基类
 */
class IndexLogin extends Cors
{
    public $user;
    public function _initialize()
    {    
        // if (!isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        //     return $this->redirect('/index/index/register');
    	parent::_initialize();
    	$data = request()->param();
        if(!isset($data['token']))
            die(json_encode(['code'=>0,'msg'=>'请登录']));
        $user = model('User')->where('token',$data['token'])->find();
        if(!$user)
            die(json_encode(['code'=>0,'msg'=>'没有找到该用户']));
        //获取用户信息继承给子类   
        $this->user = $user; 
    }
}