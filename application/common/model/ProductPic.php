<?php
namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

/**
 *
 */
class ProductPic extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $field = true;
/*-------------------- 1 to 1 ------------------------------------*/
    public function product()
    {
        return $this->hasOne('Product','id','pd_id');
    }
}