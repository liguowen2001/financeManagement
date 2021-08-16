<?php
namespace app\common\model;
use think\Model;

class Accountant extends Model
{   
	 static public function logOut()
    {
        // 销毁session中数据
        session('id', null);
        return true;
    }
     static public function isLogin()
    {
        $id = session('id');
        $tag=session('tag'); 
        // isset()和is_null()是一对反义词
        if (isset($id)&&$tag==2) {
            return true;
        } else {
            return false;
        }
    }  
   
}