<?php
namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

/**
 *
 */
class AuthGroup extends Model
{
 
    public function getUidTextAttr($value, $data)
    {
        return User::where('id', $data['uid'])->value('us_name');
    }
    public function admin($value='')
    {
    	return $this->belongsToMany('Admin','AuthGroupAccess','uid','group_id');
    }

}