<?php
namespace app\adminer\Logic;

use app\adminer\logic\Base;
use think\Db;
use think\Session;
use app\adminer\model\AdminUserLog as AdminUserLogModel;

class Index extends Base
{
	public function __construct(){
		parent::__construct();
	}

    public function isLogin(){
        $user = Session('user');
        if($this->action != '/adminer/login/login' || $this->action != '/adminer/login/login.html'){
            if(empty($user)) $this->redirect(url('Login/login'));
        }
    }

}