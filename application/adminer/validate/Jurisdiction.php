<?php
namespace app\adminer\validate;

use think\Validate;

class Jurisdiction extends Validate
{
	protected $rule = [
		'group_id' 			=> 'number',
		'name' 				=> 'require|max:20',
		'jurisdiction' 		=> 'require',
	];

	protected $message = [
		'uid' 					=> '用户ID必须是纯数字',
		'name.require'	 		=> '权限组名称必须填写',
		'name.max' 				=> '权限组名称长度不能超过20个字符',
		'jurisdiction.require' 	=> '权限必须选择',
	];

	protected $scene = [
		'insert' => ['name', 'jurisdiction'],
		'update' => ['group_id', 'name', 'jurisdiction'],
		'delete' => ['group_id'],
	];
}