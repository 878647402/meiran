<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\AdminBase;
class Product extends AdminBase
{
/*---------------------产品管理--------------------------*/
    public function list()
    {
        if(input('post.')){
            $id = model('Product')->where('id',input('id'))->find();
            $pic = model('ProductPic')->where('pd_id',input('id'))->select();
            $data['id'] = $id;
            $data['pic'] = $pic;
            // dump($data);die;
            return $data;
        }
        $count = model('Product')->count();
        $list = model('Product')->select();
        $this->assign([
            'count'=>$count,
            'list'=>$list,
        ]);
        return $this->fetch();
    }
      //修改信息
    public function listUpdate()
    {
        $form = request()->param();
        $row = model('Product')->save($form,['id'=>$form['id']]);
        if ($row) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }
      //状态修改
    public function listStatus()
    {
        $row = model('Product')->where('id',input('id'))->update(['status'=>input('status')]);
        if ($row) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }
    public function listDelete()
    {
        $row = model('Product')->destroy(input('id'));
        if ($row) {
            $this->success('删除成功');
        } else {
             $this->error('删除失败');
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
        $row = model('Product')->destroy($str);
        if ($row) {
            $this->success('删除成功');
        } else {
             $this->error('删除失败');
        }
    }
/*---------------------产品添加--------------------------*/
    public function listAdd()
    {
        session('pid',null);
        return $this->fetch();
    }
    public function listAddProduct()
    {
        if(!session('pid')){
            $pro = model('Product');
            $pro->save();
            session('pid',$pro->id);
         }
        $product = model('Product')->where('id',session('pid'))->find();
        if ($this->request->file('file')) {
            //file是传文件的名称，这是webloader插件固定写入的。因为webloader插件会写入隐藏input，
            $file = $this->request->file('file');
            $info = $file->move(ROOT_PATH . 'public/static/index/upload/product/');
            $path = '/static/index/upload/product/'.$info->getSaveName();
            model('ProductPic')->save(['pd_id'=>session('pid'),'pic'=>$path]);
            if(!$product['pd_pic'])
                model('Product')->save(['pd_pic'=>$path],['id'=>session('pid')]);
            return ;
        }
        if(!$product['pd_pic'])
            return $this->error('请添加主图');
        $form = request()->param();
        //Product添加
        $product = model('Product');
        $row = $product->save($form,['id'=>session('pid')]);
        if ($row) {
            session('pid',null);
            return $this->success('添加成功','/admin/product/list.html');
        } else {
            session('pid',null);
            return $this->error('添加失败');
        }
    }
/*---------------------品牌管理--------------------------*/
    public function manage()
    {
        return $this->fetch();
    }
/*---------------------分类管理--------------------------*/
    public function cate()
    {
        return $this->fetch();
    }
    public function cateAdd()
    {
        return $this->fetch();
    }
}
