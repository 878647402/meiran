<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\AdminBase;
class User extends AdminBase
{
//------------------会员管理------------------------------- 
    public function grade()
    {
        $level = [
            'a'=>model('User')->where('level',0)->count(),
            'b'=>model('User')->where('level',1)->count(),
            'c'=>model('User')->where('level',2)->count(),
            'd'=>model('User')->where('level',3)->count(),
            'e'=>model('User')->where('level',4)->count(),
            'all'=>model('User')->count(),
        ];
        $this->assign(['level'=>$level]);
         return $this->fetch();
    }

//------------------会员列表-------------------------------
    public function list()    
    {
        if (input('post.')) {
            return model('User')->where('id',input('id'))->find();
        }
         $user = model('User');
         $list = $user->all();
         $count = $user->count();
         $this->assign([
                'list'=>$list,
                'count'=>$count,
         ]);
         return $this->fetch();
    }
    //修改信息
    public function listUpdate()
    {
        $form = request()->param();
        $row = model('User')->save($form,['id'=>$form['id']]);
        if ($row) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }
    //批量删除
    public function listDeleteAll()
    {
        $v = json_decode(input('v'));
        $str = '';
        foreach ($v as $key => $value) {
            $str .= $value.',';
        }
        $row = model('User')->destroy($str);
        if ($row) {
            $this->success('删除成功');
        } else {
             $this->error('删除失败');
        }
    }
    //会员添加
    public function listAdd(Request $req)
    {
        $form = $req->param();
        $user = model('User');
        $referrer = $user->field('id,path')->where('us_tell',$form['phone'])->find();
        if (!$referrer) 
            return json_encode(['code'=>0,'msg'=>'推荐人不存在']);
        if($user->field('id')->where('us_tell',$form['tell'])->find())
            return $this->error('手机号已经存在,一个手机只能注册一次');
        if(!preg_match('/^1[3456789]\d{9}$/',$form['tell']))
            return $this->error('手机号错误');
        if(!preg_match('/^1[3456789]\d{9}$/',$form['phone']))
            return $this->error('推荐人手机号必须11位');
        if(!preg_match('/\d{6,16}/',$form['password']))
            return $this->error('密码必须6位以上');
        $token = md5($form['tell'].md5($form['password']));
        $user->us_name = $form['name'];
        $user->us_tell = $form['tell'];
        $user->us_pwd = $form['password'];
        $user->referrer = $form['phone'];
        $user->level = $form['form-field-radio'];
        $user->status = $form['form-field-radio1'];
        $user->path = $referrer['path'].','.$referrer['id'];
        $user->token = $token;
        $user->save();
        if($user->id)
            return $this->success('添加成功');
        else
            return $this->error('添加失败');
    }
    //状态修改
    public function listStatus()
    {
        $row = model('User')->where('id',input('id'))->update(['status'=>input('status')]);
        if ($row) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }
    public function listDelete()
    {
        $row = model('User')->destroy(input('id'));
        if ($row) {
            $this->success('删除成功');
        } else {
             $this->error('删除失败');
        }
    }
//------------------查看会员详情------------------------------- 
    public function show()
    {
        function getTree($array, $pid =0, $level = 0){
            //声明静态数组,避免递归调用时,多次声明导致数组覆盖
            static $list = [];
            foreach ($array as $key => $value){
                //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
                if ($value['referrer'] == $pid){
                    //父节点为根节点的节点,级别为0，也就是第一级
                    $value['level'] = $level;
                    //把数组放到list中
                    $list[] = $value;
                    //把这个节点从数组中移除,减少后续递归消耗
                    unset($array[$key]);
                    //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                    getTree($array, $value['id'], $level+1);
                }
            }
            return $list;
        }
        $n = input('id');
        $user = model('User')->order('referrer asc')->where('path','like',"%".$n."%")->select();
        // $user = model('User')->order('referrer asc')->select();
        $user = collection($user)->toArray();
        $array = getTree($user,$n);
        $html = '';
        foreach($array as $value){
            $html.=str_repeat('---->', $value['level']). $value['us_name'].'<br />';
        }
        //---------------
        $user = model('User')->find(input('id'));
        $re = model('User')->where('referrer',$user['id'])->select();
        $all = model('User')->where('path','like','%'.$user['id'].',%')->select();
        $this->assign([
            'user'=>$user,
            're'=>$re,
            'all'=>$all,
            'html'=>$html,
        ]);
        return $this->fetch();
    }

}
