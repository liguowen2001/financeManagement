<?php
namespace app\common\validate;
use think\Validate;

class Bill extends Validate
{
    protected $rule = [
    	'money'  => 'require|length:0,30'
    ];
}