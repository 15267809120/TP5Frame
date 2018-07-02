<?php
namespace app\admin\Logic;

use app\admin\logic\Base;
use think\Db;
use think\Session;
use app\admin\validate\Login as LoginValidate;
use app\admin\model\ViewAdminUser as ViewAdminUserModel;

class Login extends Base
{
	public function initialize(){
		parent::initialize();
	}

    public function login($data = array()){
        $admin_user = new ViewAdminUserModel();
    	$result = $this->validate($data,'login.login');
    	if($result === true){
    		$result_data = $admin_user::where($data)->find();
            if($result_data){
                $result_data = $result_data->toArray();
                Session::set('user', $result_data);
                return ['code' => 'success'];
            }else{
                return ['code' => 'null', 'str' => '账户或密码错误'];
            }
    	}else{
    		return ['code' => 'error', 'str' => $result];
    	}
    }
}