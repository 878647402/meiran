<?php
namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

/**
 *
 */
class User extends Model
{
    use SoftDelete;         //软删除
    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $field = true; //过滤字段
/*----------- 获取不存在的字段转换成想要的值--------------*/
    public function getReferrerNameAttr($value,$data)
    {
        return User::where('id', $data['referrer'])->value('us_name');
    }
    // 获取不存在的字段转换成想要的值
    public function getReferrerTellAttr($value,$data)
    {
        return User::where('id', $data['referrer'])->value('us_tell');
    }
    // 获取不存在的字段转换成想要的值
    public function getLevelTextAttr($value,$data)
    {
        $level = [
            0 => '普通用户',
            1 => '铁牌用户',
            2 => '青铜用户',
            3 => '银牌用户',
            4 => '金牌用户',
        ];
        return $level[$data['level']];
    }
    //设置器
    public function setUsPwdAttr($value)
    {
         return md5($value);
    }
    //设置器  获取推荐人电话转换成id存到mysql
    public function setReferrerAttr($value)
    {
        return User::where('us_tell',$value)->value('id');
    }
    public function getReferrerTextAttr($value,$data)
    {
        return User::where('id',$data['referrer'])->value('us_tell');
    }
/*--------------1 to many-----------------------------*/
    public function orders()
    {
        return $this->hasMany('Order','us_id');
    }
    public function shop()
    {
        return $this->hasMany('Shop','us_id');
    }
    public function pro()
    {
        return $this->belongsToMany('Product','Order','pd_id','us_id');
    }
}