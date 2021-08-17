<?php
namespace app\index\controller;
use app\common\model\Admin;
use think\Controller; 
class AdminController extends Controller
{
	public function index()
	{
		$Admin=Admin::get(1);
		var_dump($Admin->name);
	}
}