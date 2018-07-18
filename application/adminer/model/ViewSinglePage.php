<?php
namespace app\adminer\model;

use think\Model;
use think\Cookie;

class ViewSinglePage extends Model
{
	protected $pk = 'id';
	protected $resultSetType = '';
	
	
	public $insert_hidden = ['id','menu_id','content','name'];
	public $update_hidden = ['id','menu_id','content','name'];
	
	protected function initialize(){
		parent::initialize();
	}
	
	
}