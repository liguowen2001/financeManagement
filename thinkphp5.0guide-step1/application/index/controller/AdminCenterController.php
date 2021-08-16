<?php
namespace app\index\controller;
use app\common\model\Admin;
use think\Request;

class AdminCenterController extends Index
{
	
	public function center()
	{
		$id=session('id');
		if(is_null($id)){
                return $this->error('未获取到ID信息',1);
            }
		$Admin=Admin::get($id);
		if(is_null($Admin)){
			return $this->error('未找到记录');
		}
		$this->assign('Admin',$Admin);
		return $this->fetch();
	}
	public function edit()
    {
    	try{
            $id=Request::instance()->param('id/d');
            if(is_null($id)){
                throw new\Exception('未获取到ID信息',1);
            }
            if(null===$Admin=Admin::get($id)){
                $this->error('系统未找到ID为' . $id . '的记录');
            }
            $this->assign('Admin',$Admin);
            return $this->fetch();
        }catch(\think\Exception\HttpResponseException $e){
        throw $e;
        }catch(\Exception $e){
        return $e->getMessage();
        }
    }
    public function update()
    {   
        $message='';
        try{
        	$postData=Request::instance()->post();
            $id=Request::instance()->post('id/d');
            //获取当前对象
            $Admin=Admin::get($id);
            if(!is_null($Admin)){
                if($Admin->password!==$postData['oldPassword']){
                    return $this->error('密码错误');
                }
                if($postData['newPassword1']!==$postData['newPassword2']){
                    return $this->error('两次输入密码不一致');
                }
                $Admin->password=$postData['newPassword2'];
                if(false===$Admin->validate(true)->save()){
                    return $this->error('更新失败' . $Admin->getError());
                }
            }else{
                throw new \Exception("所更新的记录不存在", 1);     
            }
        }catch(\think\Exception\HttpResponseException $e){
            throw $e;
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return $this->success('操作成功',url('admin_subject/index'));
    }

}