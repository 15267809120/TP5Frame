<?php
namespace app\adminer\validate;

use think\Validate;

class Menu extends Validate
{
	protected $rule = [
		'id' 			=> 'require|number',
		'pid' 		=> 'require|number',
        'name' 		=> 'require|max:50',
        'link' 		=> 'require|max:250',
	];

	protected $message = [
		'id.require'			=> 'ID必须存在',
		'id.number'				=> 'ID必须是纯数字',
		'pid.require' 			=> '上级ID必须存在',
		'pid.number' 			=> '上级ID必须是纯数字',
		'name.require' 			=> 'name必须填写',
		'name.max' 				=> 'name长度不能超过50个字符',
		'link.require' 			=> '链接必须填写',
		'link.max' 				=> '链接长度不能超过250个字符',
	];

	protected $scene = [
		'insert' => ['pid', 'name'],
		'update' => ['pid', 'name'],
		'delete' => ['id'],
	];
}