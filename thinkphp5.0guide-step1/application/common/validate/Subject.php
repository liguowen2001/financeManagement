<?php
namespace app\common\validate;
use think\Validate;

class Subject extends Validate
{
    protected $rule = [
    	'name'  => 'require|unique:subject|length:1,25'
    ];
}