<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Request;
use think\Session;
use think\Cookie;
use app\admin\logic\Config as ConfigLogic;

class Config extends Base
{
	public function __construct(){
		parent::__construct();
	}

    public function siteConfig(){
        $configL = new ConfigLogic();
        if(Request::instance()->isAjax()){
            $get_data = Request::instance()->post();
            $result = $configL->updateConfig($get_data);
            return json($result);
        }
        $data = $configL->getConfig('site');
        $this->assign('config_list', $data);
    	return $this->fetch();
    }

    public function smtpConfig(){
        $configL = new ConfigLogic();
        if(Request::instance()->isAjax()){
            $get_data = Request::instance()->post();
            $result = $configL->updateConfig($get_data);
            return json($result);
        }
        $data = $configL->getConfig('smtp');
        $this->assign('config_list', $data);
        return $this->fetch();
    }

}
