<?php
namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

/**
 *
 */
class Shop extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $field = true;
    // 获取不存在的字段转换成想要的值
    public function getShopIdTextAttr($value,$data)
    {
        return model('Product')->where('id', $data['shop_id'])->find();
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
   