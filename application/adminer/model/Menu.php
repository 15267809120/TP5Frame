<?php
namespace app\adminer\model;

use think\Model;

class Menu extends Model
{
	protected $pk = 'id';
	protected $resultSetType = '';
	
	public $list_hidden = ['is_show'];
	public $insert_hidden = ['is_show','pid','id'];
	public $update_hidden = ['is_show','pid','id'];
	
	protected function initialize(){
		parent::initialize();
	}
	
	
}