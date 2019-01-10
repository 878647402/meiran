<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\AdminBase;
/**
 * Make one feild rule
 *
 * @param object $field
 * @return array|string
 * @throws Exception
 */
//管理员权限
class Admin extends AdminBase
{
/*----------@throws-----------管理员权限分配---------------------------*/
    public function powerChange()
    {

        $auth = model('AuthRule')->order('id asc')->select();
        $auth =  collection($auth)->toArray();
        $k = -1;
        foreach ($auth as $key => $value) {
            if ($value['cate']==1 ) {
                $k++;
                $data[$k] = $value;
                $data[$k]['arr']=[];
            }
            if ($value['cate']==0 || $value['cate']==1) {
                $data[$k]['arr'][] = $value;
            }
        }
        $group = model('AuthGroup')->all();
        $list = model('Admin')->all();
        $this->assign([
            'list'=>$list,
            'data'=>$data,
            'group'=>$group,
        ]);
        return $this->fetch();
    }   
    //添加权限
    public function powerChangeAdd()
    {
        $sel = input('sel');
        $user = json_decode(input('user'));
        $power = json_decode(input('power'));
        $p = '1';
        foreach ($power as $key => $value) {
            $p .= ','.$value;
        }
        $group = model('AuthGroup')->where('id',$sel)->find();
        if ($group->rules != $p) {
            $g = model('AuthGroup')->where('id',$sel)->update(['rules'=>$p]);
            if (!$g) 
                return $this->error('权限表修改失败');
        }
        foreach ($user as $key => $value) {
             $acc = model('AuthGroupAccess')->where('uid',$value)->find();
             if ($acc) {
                if( $acc->group_id != $sel){
                    $update = model('AuthGroupAccess')->where('uid',$value)->update(['group_id'=>$sel]);
                    if(!$update)
                        return $this->error('节点表修改失败');
                }
             } else {
                 $add = db('AuthGroupAccess')->insert(['uid'=>$value,'group_id'=>$sel]);
                 if(!$add)
                    return $this->error('节点表添加失败');
             }
        }
        return $this->success('分配完成');
    }
/*---------------------管理员权限---------------------------*/  
    public function power()
    {
        $list = model('AuthGroup')->all();
        $acc = model('AuthGroupAccess')->all();
        foreach ($list as $key => $value) {
            //统计各个管理员人数
            $value['count'] =  model('AuthGroupAccess')->where('group_id',$value['id'])->count();
            $data[$key] = $value;
        }
         $this->assign(['list'=>$data]);
         return $this->fetch();
    }
    public function powerDelete()
    {
        $row = model('AuthGroup')->where('id',input('id'))->delete();
        if ($row) {
            $this->success('删除成功');
        } else {
             $this->error('删除失败');
        }
    }
/*-----------------------管理员列表-------------------------*/
    public function list()
    {
        $list = model('AuthGroupAccess')->select();
        $group = model('AuthGroup')->select();
        $list = collection($list)->toArray();
 
        $this->assign([
                    'list'=>$list,
                    'group'=>$group,
                ]);
        return $this->fetch();
    }
    public function listStatus()
    {
        $row = model('Admin')->where('id',input('id'))->update(['status'=>input('status')]);
        if ($row) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }
    public function listDelete()
    {
        $row = model('Admin')->destroy(input('id'));
        if ($row) {
            $this->success('删除成功');
        } else {
             $this->error('删除失败');
        }
    }
    public function listDeleteAll()
    {
        $v = json_decode(input('v'));
        $str = '';
        foreach ($v as $key => $value) {
            $str .= $value.',';
        }
        $row = model('Admin')->destroy($str);
        if ($row) {
            $this->success('删除成功');
        } else {
             $this->error('删除失败');
        }
    }
    //添加
    public function listAdd(Request $req)
    {
        $data = $req->post();
        $user = db('admin')->where('tell',$data['user-tel'])->find();
        if ($user) {
            return $this->error('手机号已经存在');
        } else {
            //实例化admin 添加操作
            $admin = model('Admin');
            $admin->name = $data['user-name'];
            $admin->tell = $data['user-tel'];
            $admin->pass_word = $data['userpassword'];
            $admin->save();
            $id = $admin->id;
            model('AuthGroupAccess')->data(['uid'=>$id,'group_id'=>$data['admin-role']])->save();
            return $this->success('添加成功');
        }
    }
/*----------------------管理员个人信息--------------------------*/
    public function info()
    {
        $list = model('Admin')->find(session('admin')['id']);
        $list1 = model('AuthGroup')->find(session('admin')['id']);
        $this->assign(['list'=>$list,'list1'=>$list1]);
        return $this->fetch();
    }
    //修改个人信息
    public function infoChange()
    {
        $data = model('Admin')->where('id', session('admin')['id'])->find();
        if (!preg_match ( '/^\d{11}/', input('p1'))) {
            return $this->error('手机11位');
        }
         if( model('Admin')->save(['name'=>input('p'),'tell'=>input('p1')] ,['id'=>$data->id]) )
            return $this->success('修改成功');
         else
            return $this->error('修改失败');
    }
    //修改密码
    public function infoChangePwd()
    {
        $data = model('Admin')->where('id', session('admin')['id'])->find();
        if (!preg_match ( '/^\d{6,12}/', input('p1'))) {
            return $this->error('密码6-12');
        }
        if ($data->pass_word == input('p')) {
             if( model('Admin')->save(['pass_word'=>input('p1')] ,['id'=>$data->id]) )
                return $this->success('修改成功');
             else
                return $this->error('密码错误');
        }else{
            return $this->error('密码错误');
        }
    }
}
