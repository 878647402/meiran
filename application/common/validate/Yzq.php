<?php
namespace app\common\validate;

use think\Validate;
//验证器
class Yzq extends Validate
{
    protected $rule =   [
        'name|用户名'  => 'length:4,12',
        'password|密码'   => 'length:4,12',
        'yzm|验证码'=>'require|captcha',
        'us_name' => 'length:2,10|require',
        'us_tel'  => 'require|length:11',
        'us_pwd'  => 'require|length:6,12|confirm',
        'tname'   => 'length:11',
    ];
    protected $message  =   [
        'us_pwd.confirm' => '两次密码输入的不一致!',
        'us_pwd.length' => '密码必须6-12位!',
        'us_pwd.require' => '密码不能为空!',
        'us_tel.length' => '手机号必须11位!',
        'us_name.length' => '用户名不能为空!!',
        'tname.length' => '推荐人手机号必须11位!!',
    ];
    protected $scene = [
        'edit'  =>  ['name','password','yzm'],
        'register'  =>  ['us_name','us_tel','us_pwd','tname'],
        'login'  =>  ['name','password'],
    ];

	 
}
// 助手函数调用验证器
//          $validate = validate('Yzq');
//         if(!$validate->scene('edit')->check($data)){
//             dump($validate->getError());
//         }