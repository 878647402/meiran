<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\AdminBase;
class System extends AdminBase
{
    public function list()
    {
        if (input('post.')) {
            $form = input('post.');
            // dump($form);die;
            $row = db('user_award')->where('id',$form['id'])->update($form);
            if($row)
                echo ' <h1 style="text-align:center; color:green">修改成功</h1>';
            else
                echo ' <h1 style="text-align:center; color:green">修改失败</h1>';
        }
        $award = db('user_award')->find(1);
        $this->assign('award',$award);
        return $this->fetch();
    }
    public function systems()
    {
         return $this->fetch();
    }
    
    public function system_section()
    {
         return $this->fetch();
    }
     
    public function system_logs()
    {
         return $this->fetch();
    }
}
