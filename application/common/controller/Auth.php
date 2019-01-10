<?php
namespace app\common\controller;
use \think\Controller;
use think\Loader;
/*
*后台
**
 */
 
class Auth extends Controller
{
    protected $current_action;
    public function _initialize()
    {
        Loader::import("extend/Auth", EXTEND_PATH);
        $auth=new \Auth();
        $this->current_action = request()->module().'/'.request()->controller().'/'.lcfirst(request()->action());
        $result = $auth->check($this->current_action,session('admin')['id']);
        // if(!$result){
        //       $this->error('你没有权限');
        // } 
    }
}