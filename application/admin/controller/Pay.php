<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\AdminBase;
class Pay extends AdminBase
{
    public function manage()
    {
         return $this->fetch();
    }
    
    public function method()
    {
         return $this->fetch();
    }
     
    public function config()
    {
         return $this->fetch();
    }
}
