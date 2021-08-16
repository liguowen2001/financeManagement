<?php
namespace app\index\controller;
use app\common\model\Bill;
use app\common\model\Subject;
use app\common\model\Accountant;
use think\Controller;
use think\Request;

class AccountantController extends Index2
{
	public function index()
    {
        try {
            // 获取查询信息
            $time = Request::instance()->get('time');
            $pageSize = 10; // 每页显示5条数据
            if($time==1){
            	$time=7*24*60*60;
            }
            if($time==2){
            	$time=31*24*60*60;
            }
            // 实例化Teacher
            $Bill = new Bill; 
            // 定制查询信息
            if (!empty($time)) {
            	$time=time()-$time;
                $Bill->where('create_time','>',$time);
            }
            // 按条件查询数据并调用分页
            $bills = $Bill->paginate($pageSize);
            // 向V层传数据
            $this->assign([
            	'bills'=>$bills,
            	'Subject'=>new Subject,
            	'Accountant'=>new Accountant
            	]);
            // 取回打包后的数据
            $htmls = $this->fetch();

            // 将数据返回给用户
            return $htmls;

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }
     //编辑数据
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
    //增加数据
    public function add()
    {
    	try {
    		$Subject= new Subject;
    		$this->assign('Subject',$Subject);
            $htmls = $this->fetch();
            return $htmls;
        } catch (\Exception $e) {
            return '系统错误' . $e->getMessage();
        }
    }

    public function save()
    {
         $message = '';  // 提示信息

        try {
            // 接收传入数据
            $postData = Request::instance()->post();    

            // 实例化Teacher空对象
            $Bill = new Bill();
            $Bill->accountant_id=session('id');
            $Bill->borrow_id=$postData['borrow_id'];
            $Bill->credit_id=$postData['credit_id'];
            $Bill->money=$postData['money'];
            $Bill->note=$postData['note'];
            // 新增对象至数据表
            $result = $Bill->validate(true)->save($postData);
            // 反馈结果
            if (false === $result)
            {
                // 验证未通过，发生错误
                $message = '新增失败:' . $Bill->getError();
            } else {
                // 提示操作成功，并跳转至教师管理列表
                //科目资金变化
                $Subject=Subject::get($Bill->borrow_id);
                $Subject->money=$Subject->money+$postData['money'];
                if(!$Subject->validate(true)->save()){
                	return $this->error('资金错误');
                }
                $Subject=Subject::get($Bill->credit_id);
                $Subject->money=$Subject->money-$postData['money'];
                if(!$Subject->validate(true)->save()){
                	return $this->error('资金错误');
                }
                return $this->success('账单新增成功。', url('index'));
            }
        }catch(\think\Exception\HttpResponseException $e){
            throw $e;
        }catch (\Exception $e) {
            // 发生异常
            return $e->getMessage();
        }
        return $this->error($message);
    }
}