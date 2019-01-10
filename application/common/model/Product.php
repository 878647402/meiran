<?php
namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

/**
 *
 */
class Product extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $field = true;
    public function getNewTextAttr($value,$data)
    {
        $arr=[
            '0' => '关',
            '1' => '开',
        ];
        return $arr[$data['new']];
    }
    public function getHotTextAttr($value,$data)
    {
        $arr=[
            '0' => '关',
            '1' => '开',
        ];
        return $arr[$data['hot']];
    }
 /*--------------1 to many-----------------------------*/
    public function productPic()
    {
        return $this->hasMany('ProductPic','pd_id');
    }
    public function order()
    {
        return $this->hasMany('Order','pd_id');
    }
}