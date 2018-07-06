<?php
namespace app\adminer\model;

use think\Model;

class ViewAdminUser extends Model
{
	//这里是必须隐藏的字段
	//public $hidden = ['group','group_id','jurisdiction'];
	public $insert_hidden = ['uid','group','group_id','jurisdiction','name','reg_time'];
	public $update_hidden = ['uid','group_id','jurisdiction','name','reg_time'];
    
	protected function initialize(){
		parent::initialize();
	}
	
	public function getRegTimeAttr($value, $data){
		return date('Y-m-d H:i:s', $data['reg_time']);
	}
	

	
}