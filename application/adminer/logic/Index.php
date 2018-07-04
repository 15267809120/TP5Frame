<?php
namespace app\adminer\Logic;

use think\Controller;
use think\Db;
use think\Session;
use app\adminer\model\AdminUserLog as AdminUserLogModel;

class Index extends Controller
{
	public function __construct(){
		parent::__construct();
	}

    public function isLogin(){
        $user = Session('user');
        $path_info = strtolower($_SERVER['PATH_INFO']);
        if($path_info != '/adminer/login/login' || $path_info != '/adminer/login/login.html'){
            if(empty($user)) $this->redirect(url('Login/login'));
        }
    }

}