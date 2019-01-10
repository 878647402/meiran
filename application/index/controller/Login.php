<?php
namespace app\index\controller;

use app\common\controller\Cors;
use think\Request;
/**
 * Make one feild rule
 *
 * @param object $field
 * @return array|string
 * @throws Exception
 */
class Login extends Cors
{

    //登陆验证
    public function index(Request $request)
    {
        $data = $request->param();
        $res = model('User')->where('us_tell',$data['phone'])->find();
        if ($res) {
            if (md5($data['password'])==$res->us_pwd) {
                // session('token',$res->token);
                // session('user',$res->id);
                return json_encode(['code'=>1,'msg'=>'登陆成功','data'=>$res->token]);
            } else {
                return json_encode(['code'=>0,'msg'=>'密码错误']);
            }
        } else {
            return json_encode(['code'=>0,'msg'=>'账号错误']);
        }
    }
    //注册验证
    public function register(Request $req)
    {
        $data = $req->post();
        $validate = validate('Yzq');
        if(!$validate->scene('register')->check($data)){
            return json_encode(['code'=>0,'msg'=>$validate->getError()]);
        }
        $user = model('User');
        $res = $user->where('us_tell',$data['us_tel'])->value('id');
        if(!$res){
            $referrer = $user->field('id,path')->where('us_tell',$data['tname'])->find();
            if (!$referrer) 
                return json_encode(['code'=>0,'msg'=>'推荐人不存在']);
            if(!$data['note_code'])
                return json_encode(['code'=>0,'msg'=>'验证码不能为空']);
            if($data['note_code'] != session('yzm'))
                return json_encode(['code'=>0,'msg'=>'验证码错误']);
            $token = md5($data['us_tel'].md5($data['us_pwd']));
            $user->us_name = $data['us_name'];
            $user->us_tell = $data['us_tel'];
            $user->us_pwd = $data['us_pwd'];
            $user->referrer = $data['tname'];
            $user->path =  $referrer['path'].','.$referrer['id'];
            $user->token = $token;
            $user->save();
            $id = $user->id;
            // session('user',$id);
            // session('token',$token);
            session('yzm',null);
            return json_encode(['code'=>1,'msg'=>'注册成功','data'=>$token]);
        }else{
            session('yzm',null);
            return json_encode(['code'=>0,'msg'=>'账号已经注册']);
        }
    }
   
}
   
 