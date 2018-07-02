<?php
namespace app\admin\Logic;

use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;

class Base extends Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function isLogin(){
		$user = Session::get('user');
		dump($user);
		if(1){
echo 2;
		}exit;
	}
    


}