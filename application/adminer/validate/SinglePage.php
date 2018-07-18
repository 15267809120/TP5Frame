<?php
namespace app\adminer\validate;

use think\Validate;

class SinglePage extends Validate
{
	protected $rule = [
		'id' 					=> 'require|number',
		'menu_id' 				=> 'require|number',
		'field' 				=> 'require|min:3',
		'remarks' 				=> '',
		'content' 				=> '',
	];

	protected $message = [
		'id.require' 						=> 'ID必须存在',
		'id.number' 						=> 'ID必须是纯数字',
		'menu_id.require' 					=> '导航栏信息必须存在',
		'menu_id.number' 					=> '导航栏ID必须是纯数字',
		'field.require' 					=> '字段必须存在',
		'field.min' 						=> '字段的字符数不得小于3个字符',
	];

	protected $scene = [
		'insert' => ['menu_id', 'field'],
		'update' => ['id', 'menu_id', 'field'],
		'delete' => ['id'],
	];
}