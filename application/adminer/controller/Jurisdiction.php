<?php
namespace app\adminer\controller;

use app\adminer\controller\Base;
use think\Request;
use think\Session;
use think\Cookie;
use app\adminer\logic\Jurisdiction as JurisdictionLogic;

class Jurisdiction extends Base
{
	public function __construct(){
		parent::__construct();
		$this->groupL = new JurisdictionLogic();
	}

    public function groupList(){
        $data = $this->groupL->getGroup();
        $count = $this->groupL->getCountGroup();
        $list_fields = $this->groupL->getFieldsGroup();

		$this->assign('list_fields', $list_fields);
		$this->assign('list',$data['data']);
		$this->assign('page',$data['page']);
		$this->assign('count',$count);
        return $this->fetch();
    }

    public function insert(){
        if(Request::instance()->isAjax()){
            $get_data = Request::instance()->post();
            $result = $this->groupL->insert($get_data);
            return json($result);
        }
        $list_fields = $this->groupL->getFieldsGroup();
        $menu = Cookie::get('menu');
        //剔除掉非文本框的
        unset($list_fields[0]);
        unset($list_fields[2]);
        $this->assign('list_fields', $list_fields);
        $this->assign('menu_list', $menu);
    	return $this->fetch();
    }

    public function update(){
    	if(Request::instance()->isAjax()){
    		$get_data = Request::instance()->post();
            $result = $this->groupL->update($get_data);
            return json($result);
    	}else if(Request::instance()->isGet()){
    		$group_id = Request::instance()->param('group_id');
    		$info = $this->groupL->getInfo($group_id);
	        $menu = Cookie::get('menu');

	        $this->assign('info', $info);
	        $this->assign('menu_list', $menu);
	    	return $this->fetch();
    	}
    }

    public function delete(){
    	if(Request::instance()->isAjax()){
    		$get_data = Request::instance()->post();
    		$result = $this->groupL->delete($get_data);
    		return json($result);
    	}
    }
    
    public function search(){
    	if(Request::instance()->isGet()){
    		$get_data = Request::instance()->param();
    		if(empty($get_data['page'])) $get_data['page'] = 1;
    		$data = $this->groupL->getGroup($get_data, $get_data['page']);
    		$count = $this->groupL->getCountGroup();
        	$list_fields = $this->groupL->getFieldsGroup();
        	
    		$this->assign('list', $data['data']);
    		$this->assign('page', $data['page']);
    		$this->assign('list_fields', $list_fields);
    		$this->assign('count',$count);
    		return $this->fetch();
    	}
    }

}