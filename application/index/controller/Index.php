<?php
namespace app\index\controller;
use think\Request;
use app\common\controller\Cors;
use app\common\api\Sms as Sms;
use app\common\api\Express as exp;
//登陆注册
class Index extends Cors
{
	public function index()
	{
	    session('id',1);

        $this->redirect('register');

    }
    //登陆页面
    public function login($value='')
    {
        return $this->fetch();
    }
    //短信验证
    public function sms()
    {
        $ajax = request()->param();
        $sms = new Sms;
        $rand = rand(1000,9999);
        session('yzm',$rand);
        return $sms->sms($ajax['phone'],$rand);
    }
    //注册页面
    public function register(Request $request)
    {
        if(input('tell'))
            $this->assign(['tell'=>input('tell')]);
        return $this->fetch('register');
    }
    //返回二维码链接
    public function tell(Request $request)
    {
        if(input('tell')){
            $user = model('User')->where('token',input('tell'))->find();
            return json_encode(['code'=>1,'msg'=>'OK','url'=>'http://'.$_SERVER['HTTP_HOST']."/index/index/register?tell=".$user->us_tell,'info'=>$user]);
        }else
             return json_encode(['code'=>0,'msg'=>'token is null']);
    }
    //app下载页面
    public function down()
    {
        return $this->fetch();
    }
}







