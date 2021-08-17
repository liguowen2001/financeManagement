<?php
namespace app\index\controller;
use app\common\model\Accountant;
use app\common\model\Subject;
use think\Request;

class AccountantSubjectController extends Index2
{
	public function index()
    {
        try {
            // 获取查询信息
            $name = Request::instance()->get('name');
            $pageSize = 15; // 每页显示5条数据

            // 实例化Teacher
            $Subject = new Subject; 

            // 定制查询信息
            if (!empty($name)) {
                $Subject->where('name', 'like', '%' . $name . '%');
            }
            // 按条件查询数据并调用分页
            $subjects = $Subject->paginate($pageSize, false, [
                'query'=>[
                    'name' => $name,
                    ],
                ]);

            // 向V层传数据
            $this->assign('subjects', $subjects);
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
}