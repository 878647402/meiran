<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\AdminBase;
//广告管理
class Ads extends AdminBase
{
     
    
    public function manage()
    {
         return $this->fetch();
    }
     
    public function cate()
    {
         return $this->fetch();
    }
    
}
