<?php
namespace app\adminer\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\adminer\logic\Login as LoginLogic;

class Login extends Controller
{
	public function __construct(){
		parent::__construct();
	}

    public function Login()
    {
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
        $this->redirect(url('Login/Login'));
    }
}
