<?php
namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

/**
 *
 */
class AuthGroupAccess extends Model
{

    
    // 获取器
    public function getUidAttr($value)
    {
        return model('Admin')->where('id', $value)->find();
    }
    // 获取器
    public function getGroupIdAttr($value)
    {
        return model('AuthGroup')->where('id', $value)->find();
    }
 
}

 