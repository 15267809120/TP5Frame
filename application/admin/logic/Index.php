<?php
namespace app\admin\Logic;

use think\Controller;
use think\Db;
use think\Session;
use app\admin\model\AdminUserLog as AdminUserLogModel;

class Index extends Controller
{
	public function __construct(){
		parent::__construct();
	}

    public function isLogin(){
        $user = Session('user');
        $path_info = strtolower($_SERVER['PATH_INFO']);
        if($path_info != '/admin/login/login'){
            if(empty($user)) $this->redirect(url('Login/login'));
        }
    }

}