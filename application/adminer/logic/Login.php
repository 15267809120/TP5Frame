<?php
namespace app\adminer\Logic;

use app\adminer\logic\Base;
use think\Db;
use think\Session;
use app\adminer\validate\Login as LoginValidate;
use app\adminer\model\ViewAdminUser as ViewAdminUserModel;

class Login extends Base
{
	public function initialize(){
		parent::initialize();
	}

    public function login($where = array()){
        $admin_user = new ViewAdminUserModel();
    	$result = $this->validate($where,'login.login');
    	if($result === true){
    		$result_data = $admin_user::where($where)->find();
            if($result_data){
                $result_data = $result_data->toArray();
                Session::set('user', $result_data);
                Session::set('power', explode(',', rtrim($result_data['jurisdiction'], ',')));
                return ['code' => 'success'];
            }else{
                return ['code' => 'null', 'str' => '账户或密码错误'];
            }
    	}else{
    		return ['code' => 'error', 'str' => $result];
    	}
    }
}