<?php
namespace app\adminer\validate;

use think\Validate;

class User extends Validate
{
	protected $rule = [
		'uid' 			=> 'require|number',
		'username' 		=> 'require|max:20',
        'nickname' 		=> 'require|max:50',
        'password' 		=> 'require|max:50|min:6',
        'group' 		=> 'require|max:4|number',
        'reg_time' 		=> 'require|max:10|number',
	];

	protected $message = [
		'uid.require'			=> '用户ID必须存在',
		'uid.number'			=> '用户ID必须是纯数字',
		'username.require' 		=> '用户名必须填写',
		'username.max' 			=> '用户名长度不能超过20个字符',
		'nickname.require' 		=> '用户昵称必须填写',
		'nickname.max' 			=> '用户昵称长度不能超过50个字符',
		'password.require' 		=> '用户密码必须填写',
		'password.max' 			=> '用户密码长度不能超过50个字符',
		'password.min' 			=> '用户密码长度不能小于6个字符',
		'group.require' 		=> '用户分组不能为空',
		'group.max' 			=> '用户分组长度不能超过4个字符',
		'group.number' 			=> '用户分组信息只能是数字',
		'reg_time.require' 		=> '注册时间不能为空',
		'reg_time.max' 			=> '注册时间长度不能大于10个字符',
		'reg_time.number' 		=> '注册时间只能为数字',
	];

	protected $scene = [
		'insert' => ['username', 'nickname', 'password', 'group', 'reg_time'],
		'update' => ['uid', 'username', 'nickname', 'password', 'group', 'reg_time'],
		'delete' => ['uid'],
	];
}