<?php
namespace app\common\controller;
use \think\Controller;
 
/**
 * app跨域base
 */
 
class Cors extends Controller
{
    public function _initialize()
    {	 
         header('Access-Control-Allow-Origin:*');     
         header('Access-Control-Allow-Methods:*');  
         header('Access-Control-Allow-Headers:*');
         header('Access-Control-Allow-Credentials:false');
    }
}