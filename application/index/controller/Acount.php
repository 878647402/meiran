<?php
namespace app\index\controller;

use app\common\controller\IndexLogin;
use think\Request;
//账户信息 
class Acount extends IndexLogin
{	
/*-------------------------账户信息 -------------------------*/
	public function acountInfo()
	{
		$user = $this->user;
		if ($user) {
			//提起获取器设置的信息   获取--获取器的内容
			$user->level = $user->level_text;
			$user->referrer = $user->referrer_text;
			// $user = json_encode($user);
			return json_encode(['code'=>1,'msg'=>'ok','data'=>$user]);
		} else {
			return json_encode(['code'=>0,'msg'=>'err,获取不到token']);
		}
	}
	//修改密码
	public function changPwd(Request $request)
	{
		$user = $this->user;
		$form = $request->param();
 		if (md5($form['pwd']) != $user->us_pwd) {
            return json(['code'=>0,'msg'=>'密码错误']);
        }
        if (!preg_match("/^\d{6,16}$/",$form['pwd1'])) {
  			return json(['code'=>0,'msg'=>'密码6-12位']);
  		}
  		if ($form['pwd1']!=$form['pwd2']) {
			return json(['code'=>0,'msg'=>'两次输入的密码不一致']);
		}
		$res = model('User')->save(['us_pwd'=>$form['pwd1']],['id'=>$user->id]);
		if ($res) {
			return json(['code'=>1,'msg'=>'修改成功']);
		} else {
			return json(['code'=>0,'msg'=>'修改失败']);
		}
	}
	//修改名字
	public function changName(Request $request)
	{
		$user = $this->user;
		$form = $request->param();
		if ($form['name']=='') {
			return json(['code'=>0,'msg'=>'名字不能为空']);
		}
		if( !(strlen($form['name'])>3 && strlen($form['name'])<=15) ){
			return json(['code'=>0,'msg'=>'中文名字最多5个字']);
		}
		$res = model('User')->save(['us_name'=>$form['name']],['id'=>$user->id]);
		if ($res) {
			return json(['code'=>1,'msg'=>'修改成功']);
		} else {
			return json(['code'=>0,'msg'=>'修改失败']);
		}
	}
/*-------------------------我的团队---------------------------*/
	public function team()
	{
	 	$user = $this->user;
	 	$res = model('User')->where('path','like','%'.$user['id'].'%')->select();
	 	if ($res) 
		    return json_encode(['code'=>1,'msg'=>'获取成功','data'=>$res]);
		else 
			return json_encode(['code'=>0,'msg'=>'获取失败']);
	 }
/*-------------------------返回二维码链接-----------------------*/
    public function tell(Request $request)
    {
        $user = $this->user;
        if($user['token'])
            return json_encode(['code'=>1,'msg'=>'OK','url'=>'http://'.$_SERVER['HTTP_HOST']."/index/index/register?tell=".$user['tell'],'info'=>$user]);
        else
             return json_encode(['code'=>0,'msg'=>'token is null']);
    }
}