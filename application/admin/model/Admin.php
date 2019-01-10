<?php
namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

/**
 *
 */
class Admin extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    // 获取器
    public function getUidTextAttr($value, $data)
    {
        return User::where('id', $data['uid'])->value('us_name');
    }
    public function group($value='')
    {
    	return $this->belongsToMany('AuthGroup','AuthGroupAccess','group_id','uid');
    }
}