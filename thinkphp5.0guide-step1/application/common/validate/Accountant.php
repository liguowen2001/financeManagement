<?php
namespace app\common\validate;
use think\Validate;

class Accountant extends Validate
{
    protected $rule = [
    	'name'  => 'require|length:1,25',
    	'username'=>'require|unique:accountant|length:2,20'
    ];
}