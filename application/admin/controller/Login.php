<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Validate;
class Login extends Controller
{
    public function login()
    {
         return $this->fetch();
    }

    public function isLogin(Request $req)
    {
        $data = $req->post();
	    $validate = validate('Yzq');
        if(!$validate->scene('edit')->check($data)){
            return $this->error($validate->getError());
        }
        $user = db('admin')->where('tell',$data['tell'])->find();
        if ($user) {
            if($user['pass_word']!=$data['password'])
                return $this->error('密码错误');
        } else {
           return $this->error('账号错误');
        }
        session('admin',$user);
        return $this->success('登陆成功','/admin/index/index');
    }
}