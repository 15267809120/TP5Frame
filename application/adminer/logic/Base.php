<?php
namespace app\adminer\Logic;

use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;
use think\Request;
use app\adminer\model\AdminMenu as AdminMenuModel;

class Base extends Controller
{
	//$_SERVER['PATH_INFO']，不需要更改
	//没有权限进行的跳转
	public $no_authority = '';
	//判断是否登录的跳转
	public $is_login = '';
	public $path_info = '';
	
	public function __construct(){
		parent::__construct();
		$request = Request::instance();
		//开发的时候，可关闭$this->path_info，取消权限控制
		$this->path_info = strtolower($_SERVER['PATH_INFO']);//dump(Session::get());
		$this->no_authority = url('Index/index');
		$this->is_login = url('Login/login');
	}

	public function isLogin(){
		$user = Session::get('user');
		if(!$user) $this->redirect($this->is_login);
	}
	
	public function isPower(){
		$power = Session::get('power');
		$menu = Cookie::get('menu_list');
		//首页都可以进入
		if(strpos($this->path_info, '/adminer/index/index')) return ;
		foreach($menu as $key => $value){
			if(!empty($value['link']) && strpos($this->path_info, $value['link']) !== false){
				if(!array_search($value['id'], $power)) $this->redirect($this->no_authority);
			}
		}
	}

	public function getMenu(){
		$menuM = new AdminMenuModel();
		$result = $menuM::all(['is_show' => 1]);
		foreach($result as $key => $value){
			$jurisdiction[$value['id']] = $value;
		}
		Cookie::set('power', $jurisdiction);
		foreach($jurisdiction as $key => $value){
			if($value['is_show']) $menu[$key] = $value;
		}
		$menu = toArray($menu);
		Cookie::set('menu_list', $menu);
		$menu = $this->menuClassification($menu);
		Cookie::set('menu', $menu);
	}

	protected function menuClassification($menu, $id = 0, $level = 1){
		foreach($menu as $key => $value){
			if($level === 1){
				if($value['pid'] === 0){
					$data[$value['id']] = $value;
					$temp = 1;
				}else{
					$temp = 0;
				}
			}else if($value['id'] === $id){
				$data[$value['id']] = $value;
				$temp = 1;
			}else{
				$temp = 0;
			}
			if($temp){
				foreach($menu as $k => $val){
					if($val['pid'] == $value['id']){
						$array = $this->menuClassification($menu, $val['id'], 2);
						foreach($array as $k2 => $val2){
							$data[$value['id']]['zlist'][$k] = $val2;
						}
					}
				}
			}
		}
		return isset($data)?$data:null;
	}

	public function getMenuInfo(){
	    $menu = Cookie::get('menu_list');
	    foreach($menu as $key => $value){
	        if($value['link'] && strpos($this->path_info, $value['link']) !== false){
	            Session::set('menu_info', $value);
	            Session::set('p_menu', $menu[$value['pid']]);
	            break;
	        }
	    }
	    
	}
	
	public function getPathInfo($action){
		$menu = Cookie::get('menu_list');
	    foreach($menu as $key => $value){
	        if($value['link'] && strpos($action, $value['link']) !== false){
		        if($menu[$value['pid']]['pid'] === 0){
		        	return $menu[$value['pid']]['id'];
		        }
	        	return $this->getPathInfo($menu[$value['pid']]['link']);
	        }
	    }
	    
	}
	
//	public function getPathInfo(){
//	    $menu = Cookie::get('menu_list');
//	    foreach($menu as $key => $value){
//	        if($value['link'] && strpos($this->action, $value['link']) !== false){
//	            $p_menu_id = $value['pid'];
//	        }
//	    }
//	    return empty($p_menu_id)?'':$p_menu_id;
//	}

}