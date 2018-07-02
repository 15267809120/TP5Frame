<?php
namespace app\admin\model;

use think\Model;

class AdminGroup extends Model
{
	protected $pk = 'group_id';
	protected function initialize(){
		parent::initialize();
	}

	
}