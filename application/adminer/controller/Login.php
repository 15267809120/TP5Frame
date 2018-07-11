<?php
namespace app\adminer\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\adminer\logic\Login as LoginLogic;

class Login extends Controller
{
	//登出的地址
	public $logout = '';
	public function __construct(){
		parent::__construct();
		$this->logout = url('Login/login');
	}

    public function Login(){
        if(Request::instance()->isAjax()){
            $get_data = Request::instance()->post();
            
            $loginL = new LoginLogic();
            $data = $loginL->login($get_data);
            return json($data);
        }
    	return $this->fetch();
    }

    public function Logout(){
        Session::clear();
        $this->redirect($this->logout);
    }
}
