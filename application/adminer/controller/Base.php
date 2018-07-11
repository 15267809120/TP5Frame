<?php
namespace app\adminer\controller;

use think\Request;
use think\Controller;
use app\adminer\logic\Base as BaseLogic;
use think\Cookie;
use think\Session;

class Base extends Controller
{
	public $action = '';
	public function __construct(){
		parent::__construct();
		$this->action = strtolower($_SERVER['PATH_INFO']);

		$this->baseL = new BaseLogic();
        //判断是否登录
		$this->baseL->isLogin();
        //将菜单放入cookie
		Cookie::delete('menu');
		if(empty(Cookie::get('menu'))) $this->menu();
		$this->getMenuInfo();
		//判断是否拥有该内容的权限
		$this->baseL->isPower();
		
	}

    public function menu(){
    	$this->baseL->getMenu();
    	$menu = Cookie::get('menu');
        $this->assign('menu', $menu);
        $menu_id = $this->baseL->getPathInfo($this->action);
        $this->assign('menu_id', $menu_id);
    }

    public function getMenuInfo(){
    	$this->baseL->getMenuInfo();
    	$menu_info = Session::get('menu_info');
    	$p_menu = Session::get('p_menu');
    	$this->assign('menu_info', $menu_info);
    	$this->assign('p_menu', $p_menu);
    }

}