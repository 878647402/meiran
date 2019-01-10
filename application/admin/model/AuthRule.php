<?php
namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

/**
 *
 */
class AuthRule extends Model
{
  
    public function getUidTextAttr($value, $data)
    {
        return User::where('id', $data['uid'])->value('us_name');
    }
}