<?php
namespace app\adminer\model;

use think\Model;
use think\Cookie;
use app\adminer\model\AdminMenu as AdminMenuModel;

class AdminGroup extends Model
{
	protected $pk = 'group_id';
	protected $resultSetType = '';
	
	//新增
	public $insert_hidden = ['group_id','jurisdiction'];
	public $update_hidden = ['group_id','jurisdiction'];
	
	protected function initialize(){
		parent::initialize();
	}
	
	public function getJurisdictionAttr($value, $data){
		$menu = Cookie::get('menu_list');
		$array = explode(',', rtrim($data['jurisdiction'], ','));
		$str = '';
		foreach($array as $key => $val){
			if(!empty($menu[$val])) $str .= $menu[$val]['name'].',';
		}
		return $str;
	}

	
}