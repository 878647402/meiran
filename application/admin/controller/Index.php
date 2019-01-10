<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\AdminBase;
class Index extends AdminBase
{
    public function index()
    {
        return $this->fetch();
    }
    public function home()
    {
        $user = model('User')->count();
        $or = model('Order');
        $order = $or->count();
        $order0 = $or->where('status',0)->count();
        $order1 = $or->where('status',1)->count();
        $order2 = $or->where('status',2)->count();
        $order3 = $or->where('status',3)->count();
        $orderM = $or->where('status',3)->select();
        $sum = '';
        foreach ($orderM as $key => $value) {
            $sum += $value->product->pd_discount;
        }
        $pro = model('Product');
        $p2 = $pro->count();
        $p1 = $pro->where('status',1)->count();
        $p0 = $pro->where('status',0)->count();
        $this->assign([
            'user'=>$user,
            'order0'=>$order0,
            'order1'=>$order1,
            'order2'=>$order2,
            'order3'=>$order3,
            'order'=>$order,
            'sum'=>$sum,
            'p2'=>$p2,
            'p1'=>$p1,
            'p0'=>$p0,
        ]);
        return $this->fetch();
    }
    public function outLogin()
    {
         session('admin',null);
         $this->success('退出成功','/admin/login/login');
    } 
}
