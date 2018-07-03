<?php
namespace app\admin\Logic;

use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;
use app\admin\model\AdminMenu as AdminMenuModel;

class Base extends Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function isLogin(){
		$user = Session::get('user');
		if(!$user){
			$this->redirect(url('Login/login'));
		}
	}
    
	public function getMenu(){
		$menuM = new AdminMenuModel();
		$menu = $menuM::all();
		$menu = toArray($menu);
		dump($menu);
		$menu = $this->menuClassification($menu);
		dump($menu);
		
	}

	protected function menuClassification($menu, $id = 0, $level = 1){
		foreach($menu as $key => $value){
			if($level === 1){
				if($value['pid'] === 0){
					$data[$value['id']] = $value;
					$temp = 1;
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
						$data[$value['id']]['zlist'] = $this->menuClassification($menu, $val['id'], 2);
					}
				}
			}
			
			
		}
		
		if(isset($data)){
			return $data;
		}else{
			return ;
		}
	}

}