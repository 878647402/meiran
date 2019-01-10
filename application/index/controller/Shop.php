<?php
namespace app\index\controller;

use app\common\controller\IndexLogin;
use think\Request;
//购物车
class Shop extends IndexLogin
{
/*-------------------------我的购物车----------------------------------------*/
	 public function index()
	 {
	 	$user = $this->user;
	 	$res = model('Shop')->where('uid',$user['id'])->select();
	 	$data = [];
	    foreach ($res as $key => $value) {
	    	$data[]=$value->shop_id_text;
	    }
	 	if ($data) {
			return json_encode(['code'=>1,'msg'=>'获取成功','data'=>$data]);
		} else {
			return json_encode(['code'=>0,'msg'=>'获取失败']);
		}
	 }
}