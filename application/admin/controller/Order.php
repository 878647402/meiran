<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\AdminBase;
class Order extends AdminBase
{
/*---------------订单列表------------------------*/    
    public function list()
    {
        $map = [];
        if ( input('status')!='' ) 
            $map = ['status'=>input('status')];
        $order = model('Order')->where($map)->select();
        $count = model('Order')->count();
        // $order = collection($order)->toArray();
         // dump($order);die;
        $this->assign([
            'list' => $order,
            'count' => $count,
        ]);
        return $this->fetch();
    }
     //状态修改
    public function listStatus()
    {
        $row = model('Order')->where('id',input('id'))->update(['status'=>input('status')]);
        if ($row) {
            $this->success('发货成功');
        } else {
            $this->error('发货失败');
        }
    }
    public function listDelete()
    {
        $row = model('Order')->destroy(input('id'));
        if ($row) {
            $this->success('删除成功');
        } else {
             $this->error('删除失败');
        }
    }
/*---------------订单信息------------------------*/
    public function info()
    {
        return $this->fetch();
    }
/*------------------交易订单 图---------------------*/
    public function order()
    {
        return $this->fetch();
    }
/*-----------------订单管理----------------------*/
    public function manage()
    {
        return $this->fetch();
    }

    //订单管理详情
    public function manage_detail()
    {
        return $this->fetch();
    }
/*-----------------交易金额----------------------*/
    public function money()
    {
        return $this->fetch();
    }
/*-----------------订单处理----------------------*/
    public function dispose()
    {
        return $this->fetch();
    }
/*------------------退款管理---------------------*/
    public function refund()
    {
        return $this->fetch();
    }
}
