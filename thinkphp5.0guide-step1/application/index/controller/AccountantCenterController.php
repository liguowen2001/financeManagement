<?php
namespace app\index\controller;
use app\common\model\Accountant;
use think\Request;
class AccountantCenterController extends Index2
{
	public function center()
	{
		$id=session('id');
		if(is_null($id)){
                return $this->error('未获取到ID信息',1);
            }
		$Accountant=Accountant::get($id);
		if(is_null($Accountant)){
			return $this->error('未找到记录');
		}
		$this->assign('Accountant',$Accountant);
		return $this->fetch();
	}
	public function edit()
    {
    	try{
            $id=Request::instance()->param('id/d');
            if(is_null($id)){
                throw new\Exception('未获取到ID信息',1);
            }
            if(null===$Accountant=Accountant::get($id)){
                $this->error('系统未找到ID为' . $id . '的记录');
            }
            $this->assign('Accountant',$Accountant);
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
            $Accountant=Accountant::get($id);
            if(!is_null($Accountant)){
                if($Accountant->password!==$postData['oldPassword']){
                	return $this->error('密码错误');
                }
            	if($postData['newPassword1']!==$postData['newPassword2']){
             		return $this->error('两次输入密码不一致');
                }
                $Accountant->password=$postData['newPassword2'];
                if(false===$Accountant->validate(true)->save()){
                    return $this->error('更新失败' . $Accountant->getError());
                }
            }else{
                throw new \Exception("所更新的记录不存在", 1);
                
            }
        }catch(\think\Exception\HttpResponseException $e){
            throw $e;
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return $this->success('操作成功',url('accountant/index'));
    }
}