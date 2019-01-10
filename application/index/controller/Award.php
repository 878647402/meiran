<?php
namespace app\index\controller;

use app\common\controller\IndexLogin;
use think\Request;
//分配奖励
class Award extends IndexLogin
{
	//防止恶意访问
	 public function setAward($orderId,$controller='')
	 {
	 	if(!$controller || $controller!='Productpay')
	 		die('非法操作,已经记录下你的IP地址:'.request()->ip().' 给予警告!!!');   	
	 	$this->index($orderId);
	 }
/*-------------------------分配奖励----------------------------------------*/
	 //接口接收orderid
	 protected function index($orderId)
	 {
	 	//获取用户id   产品价格和数量,订单号
	  	$user = $this->user;   
        $user1 = model('User')->where('id',$user->referrer)->find(); //推荐人
        $order = model('Order')->where('id',$orderId)->find(); //订单
        $price = $order->pd_id_text->pd_discount;  //价格
        $award = db('user_award')->find(1);
        $sum = $order->sum;  //数量
        //开始分配  获取推荐人
        $path = $user->path;
        $path = explode(',', $path);
        rsort($path);
        //-----------------分配2级--5800---8000-判断价格------------
 
        if ($price == 5800) {
            model('User')->where('id',$path[0])->setInc('integral',$award['5800_one']);
            model('User')->where('id',$path[1])->setInc('integral',$award['5800_two']);
        } elseif($price == 8000) {
            model('User')->where('id',$path[0])->setInc('integral',$award['8000_one']);
            model('User')->where('id',$path[1])->setInc('integral',$award['8000_two']);
        }
        //------------------------------------------
        //获取直接推荐人有几个  超过5个添加一个的290  
        $c = count($path)>10 ? 10 : count($path);
        for ($i=0; $i < $c; $i++) { 
            //获取直接推荐人和全部推荐人   5-50,100-500,1000-5000,10000-50000
            $ref = model('User')->where('referrer',$path[$i])->count();
            $ref1 = model('User')->where('path','like','%'.$path[$i].'%')->count();
            //直接推荐人必须大于5个才分配  团队10+人    
            if($ref>5 && $ref1>10){
                if( $ref1>10 && $ref1<=100)
                    model('User')->where('id',$path[$i])->setInc('integral',$award['10-100']);
                elseif( $ref1>100 && $ref1<=1000)
                    model('User')->where('id',$path[$i])->setInc('integral',$award['100-1000']);
                elseif( $ref1>1000 && $ref1<=10000)
                    model('User')->where('id',$path[$i])->setInc('integral',$award['1000-10000']);
                elseif( $ref1>10000)
                    model('User')->where('id',$path[$i])->setInc('integral',$award['10000+']);
            }
        }
 
	 }
}