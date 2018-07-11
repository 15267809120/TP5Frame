<?php
namespace app\adminer\validate;

use think\Validate;

class Login extends Validate
{
	protected $rule = [
		'uid' 			=> 'number',
		'username' 		=> 'require|max:20',
        'nickname' 		=> 'require|max:50',
        'password' 		=> 'require|max:50',
        'group' 		=> 'require|max:4',
        'reg_time' 		=> 'require|max:10',
	];

	protected $message = [
		'uid' 					=> '用户ID必须是纯数字',
		'username.require' 		=> '用户名必须填写',
		'username.max' 			=> '用户名长度不能超过20个字符',
		'nickname.require' 		=> '用户昵称必须填写',
		'nickname.max' 			=> '用户昵称长度不能超过50个字符',
		'password.require' 		=> '用户密码必须填写',
		'password.max' 			=> '用户密码长度不能超过50个字符',
		'group.require' 		=> '用户分组不能为空',
		'group.max' 			=> '用户分组长度不能超过4个字符',
		'reg_time.require' 		=> '注册时间不能为空',
		'reg_time.max' 			=> '注册时间长度不能大于10个字符',
	];

	protected $scene = [
		'login' => ['username','password'],
	];
}