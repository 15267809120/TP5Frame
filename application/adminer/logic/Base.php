<?php
namespace app\adminer\Logic;

use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;
use app\adminer\model\AdminMenu as AdminMenuModel;

class Base extends Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function isLogin(){
		$user = Session::get('user');
		if(!$user) $this->redirect(url('Login/login'));
	}
	
	public function isPower(){
		$power = Session::get('power');
		$menu = Cookie::get('menu_list');
		$action = $_SERVER['PATH_INFO'];
		/*foreach($menu as $key => $value){
			if(!empty($value['link']) && strpos($action, $value['link']) !== false){
				if(!array_search($value['id'], $power)) $this->redirect(Url('Index/index'));
			}
		}*/
		
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
	        if($value['link'] && strpos($_SERVER['PATH_INFO'], $value['link']) !== false){
	            Session::set('menu_info', $value);
	            Session::set('p_menu', $menu[$value['pid']]);
	            break;
	        }
	    }
	    
	}
	
	public function getPathInfo(){
	    $menu = Cookie::get('menu_list');
	    foreach($menu as $key => $value){
	        if($value['link'] && strpos($_SERVER['PATH_INFO'], $value['link']) !== false){
	            $p_menu_id = $value['pid'];
	        }
	    }
	    return empty($p_menu_id)?'':$p_menu_id;
	}

}