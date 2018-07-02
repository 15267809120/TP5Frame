<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\logic\Base as BaseLogic;
use think\Cookie;
use think\Session;

class Base extends Controller
{
	public function __construct(){
		parent::__construct();

		$this->baseL = new BaseLogic();
        //判断是否登录
		$this->baseL->isLogin();
        //将菜单放入cookie
		Cookie::delete('menu');
		$menu = Cookie::get('menu');
		if(empty($menu)){
			$this->menu();
		}
		$this->getMenuInfo();
	}

    public function menu(){
    	$this->baseL->getMenu();
    	$data = Cookie::get('menu');
        $this->assign('menu', $data);
    }

    public function getMenuInfo(){
    	$this->baseL->getMenuInfo();
    	$menu_info = Session::get('menu_info');
    	$p_menu = Session::get('p_menu');
    	$this->assign('menu_info', $menu_info);
    	$this->assign('p_menu', $p_menu);
    }

}