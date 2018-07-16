<?php
namespace app\adminer\model;

use think\Model;
use think\Cookie;

class SinglePage extends Model
{
	protected $pk = 'id';
	protected $resultSetType = '';
	
	
	public $insert_hidden = ['id','menu_id'];
	public $update_hidden = ['id','menu_id'];
	
	protected function initialize(){
		parent::initialize();
	}
	
	
}