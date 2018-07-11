<?php
namespace app\adminer\validate;

use think\Validate;

class Login extends Validate
{
	protected $rule = [
		'id' 					=> 'require|number',
		'name' 					=> 'require|max:50',
        'value' 				=> 'require|max:250',
        'remark' 				=> 'require|max:50',
        'identification' 		=> 'require|max:50',
	];

	protected $message = [
		'id.require' 					=> 'ID必须必须存在',
		'id.number' 					=> 'ID必须是数字',
		'name.require' 					=> '名称必须存在',
		'name.max' 						=> '名称长度不能超过50个字符',
		'value.require' 				=> '内容必须存在',
		'value.max' 					=> '内容长度不能超过250个字符',
		'remark.require' 				=> '注释必须存在',
		'remark.max' 					=> '注释长度不能超过50个字符',
		'identification.require' 		=> '标识必须存在',
		'identification.max' 			=> '标识长度不能超过50个字符',
	];

	protected $scene = [
		'update' => ['id','value'],
	];
}