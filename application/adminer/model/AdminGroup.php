<?php
namespace app\adminer\model;

use think\Model;
use think\Cookie;
use app\adminer\model\AdminMenu as AdminMenuModel;

class AdminGroup extends Model
{
	protected $pk = 'group_id';
	protected $resultSetType = '';
	
	protected function initialize(){
		parent::initialize();
	}
	
	public function getJurisdictionAttr($value, $data){
		$menu = Cookie::get('menu_list');
		$array = explode(',', rtrim($data['jurisdiction'], ','));
		$str = '';
		foreach($array as $key => $val){
			$str .= $menu[$val]['name'].',';
		}
		return $str;
	}

	
}