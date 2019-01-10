<?php
namespace app\index\controller;

use app\common\controller\IndexLogin;
use think\Request;
use app\index\controller\Award as award;
//产品页
class Product extends IndexLogin
{
/*-------------------------产品首页 ------------------------*/
	//遍历首页信息
	 public function index()
	 {
	 	$res = model('Product')->where('status',1)->select();
	 	if ($res) {
		    return json_encode(['code'=>1,'msg'=>'获取成功','data'=>$res]);
		} else {
			return json_encode(['code'=>0,'msg'=>'获取失败']);
		}
	 }
/*-------------------------产品详情页 ----------------------*/
 	public function detail()
 	{
 		$res = model('Product')->where('id',input('id'))->find();
 		$pic = model('ProductPic')->where('pd_id',input('id'))->select();
 		foreach ($pic as $key => $value) {
 			$value->pic = 'http://'.$_SERVER['HTTP_HOST'].$value->pic;
 		}
 		//-----------才分HTML图片合适添加上域名在给数组
 		$arr = explode('src="', $res['pd_detail']);
        $st = '';
        $st .= $arr[0];
        for ($i=1; $i < count($arr); $i++) { 
            $st .= 'src="http://'.$_SERVER['HTTP_HOST'].$arr[$i];
        } 
 		$res['pd_detail'] = $st;
 		//------------------------
	 	if ($res) {
		    return json_encode(['code'=>1,'msg'=>'获取成功','data'=>$res,'pic'=>$pic]);
		} else {
			return json_encode(['code'=>0,'msg'=>'获取失败']);
		}
 	}
/*---------------- --------加入购物车-----------------------*/
 	public function goShop()
 	{
 		$id = input('id');
 		$user = $this->user;
 		$shop = model('Shop');
 		$shop->shop_id = $id;
 		$shop->uid = $user->id;
 		$shop->save();
 		$res = $shop->id;
 		if ($res) {
		    return json_encode(['code'=>1,'msg'=>'添加成功']);
		} else {
			return json_encode(['code'=>0,'msg'=>'添加失败']);
		}
 	}
/*-------------------------产品购买 ------------------------*/
	public function pay()
	{
		if (!input('post.')) {
			return json_encode(['code'=>0,'msg'=>'error']);
		}
		$user = $this->user;
		$ajax = request()->param();
		$product = model('Product')->where('id',$ajax['pid'])->find();
		if(!$product)
			return json_encode(['code'=>0,'msg'=>'非法操作','data'=>$ajax['pid']]);
		//添加订单未付钱
		$order = model('Order');
		$data = [
			'order_number'=>'meiran'.date('YmdHis').substr(md5(rand(100,10000)),0,4),
			'pd_id'=>$ajax['pid'],
			'us_id'=>$user->id,
			'status'=>0,
			'sum'=>1, //数量等待传参数
			'addr'=>'河南 郑州', //地址等待传参数
		];
		$order->save($data);
		$orderId = $order->id;
		$money = $user->wallet - $product->pd_discount;
		if($money >=0 ){
			$level = $user->level + 1;
			$row = model('User')->save(['wallet'=>$money,'level'=>$level],['id'=>$user->id]);
			if($row){
				//扣除后修改订单状态
				$order->save(['status'=>1],['id'=>$orderId]);
				//分配奖励
				$award = new award;
				$controller = request()->controller().request()->action();
				$award->setAward($orderId,$controller);
				return json_encode(['code'=>1,'msg'=>'购买成功']);
			}else
				return json_encode(['code'=>0,'msg'=>'购买失败']);
		}else{
			return json_encode(['code'=>0,'msg'=>'余额不足']);
		}
	}
}