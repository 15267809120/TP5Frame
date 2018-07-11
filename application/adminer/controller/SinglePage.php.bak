<?php
namespace app\adminer\controller;

use app\adminer\controller\Base;
use think\Request;
use think\Session;
use think\Cookie;
use app\adminer\logic\User as UserLogic;

class SinglePage extends Base
{
	public $menu = '';
	public function __construct(){
		parent::__construct();
		$this->userL = new UserLogic();
		$this->request = Request::instance();
	}

	public function entrance(){
		echo 1;
	}

    public function insert(){
        if(Request::instance()->isAjax()){
            $get_data = Request::instance()->post();
            $result = $this->userL->insert($get_data);
            return json($result);
        }
        $group = $this->groupL->getTotalGroup();
        $list_fields = $this->userL->getFieldsUser('insert_hidden');
		
		$this->assign('group', $group);
        $this->assign('list_fields', $list_fields);
    	return $this->fetch();
    }

    public function update(){
    	if(Request::instance()->isAjax()){
    		$get_data = Request::instance()->post();
            $result = $this->userL->update($get_data);
            return json($result);
    	}else if(Request::instance()->isGet()){
    		$group = $this->groupL->getTotalGroup();
    		$uid = Request::instance()->param('uid');
    		$list_fields = $this->userL->getFieldsUser('update_hidden');
    		$info = $this->userL->getInfo($uid, 'getData');
	        
			$this->assign('group', $group);
			$this->assign('list_fields', $list_fields);
	        $this->assign('info', $info);
	    	return $this->fetch();
    	}
    }

    public function delete(){
    	if(Request::instance()->isAjax()){
    		$get_data = Request::instance()->post();
    		$result = $this->userL->delete($get_data);
    		return json($result);
    	}
    }
    

}