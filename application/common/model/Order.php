<?php
namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

/**
 *
 */
class Order extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $field = true;
    // 获取product
    public function getPdIdTextAttr($value,$data)
    {   
        return model('Product')->where('id', $data['pd_id'])->find();
    }
    // 获取user
    public function getUsIdTextAttr($value,$data)
    {
        return model('User')->where('id', $data['us_id'])->find();
    }
/*-------------------- 1 to 1 ------------------------------------*/
    public function user()
    {
        return $this->hasOne('User','id','us_id');
    }
    public function product()
    {
        return $this->hasOne('Product','id','pd_id');
    }
}
   