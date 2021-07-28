<?php
namespace app\index\controller;
use think\Controller;
use think\Request; 
use think\Db; 
use app\common\model\Accountant;
use app\common\model\Admin;
class LoginController extends Controller
{
    public function index()
    {   
        return $this->fetch();
    }
    
    // 处理用户提交的登录数据  
    public function login()
    {
        
        // 接收post信息
        $postData = Request::instance()->post();

        // 验证用户名是否存在
        $map = array('username'  => $postData['username']);
        $User = Admin::get($map);
        $tag=1;
        if(is_null($User)){
            $User = Accountant::get($map);
            $tag=2;
        }
        // $User要么是一个对象，要么是null。
        if (!is_null($User) && $User->getData('password') === $postData['password']) {
            // 用户名密码正确，将userId存session，并跳转至用户界面
            session('userId', $User->getData('id'));
            if($tag===1){
                        session('tag',1);
                        session('id', $User->getData('id'));
                        return $this->success('登录成功', url('admin_subject/index?id='.$User->getData('id')));
                    }
            if($tag===2){
                        session('tag',2);
                        session('id', $User->getData('id'));
                        return $this->success('登录成功', url('accountant/index'));
                    }
        }
        // 用户名不存在，跳转到登录界面。
        return $this->error('用户名或密码错误', url('index'));
    }
    //$tag===1管理员，$tag===会计
    // 验证用户名是否存在
    // 验证密码是否正确
    // 用户名密码错误，跳转到登录界面 
    public function logout(){
        if(Admin::logout()){
            return $this->success('注销成功',url('login/index'));
        }
        else{
            return $this->error('注销失败',url(''));
        }
    }
}