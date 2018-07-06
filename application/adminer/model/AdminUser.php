<?php
namespace app\adminer\model;

use think\Model;

class AdminUser extends Model
{
	protected $pk = 'uid';
	protected $resultSetType = '';
	
	protected function initialize(){
		parent::initialize();
	}
	
	public function getRegTimeAttr($value, $data){
		return date('Y-m-d H:i:s', $data['reg_time']);
	}

	
}