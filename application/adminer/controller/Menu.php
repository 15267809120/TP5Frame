<?php
namespace app\adminer\controller;

use app\adminer\controller\Base;
use think\Request;
use think\Session;
use think\Cookie;
use app\adminer\logic\Menu as MenuLogic;

class Menu extends Base
{
	public $menu = '';
	public function __construct(){
		parent::__construct();
		$this->menuL = new MenuLogic();
	}

    public function menuList(){
        $data = $this->menuL->getMenu();
        //取出某些字段数据
        $get_data = $this->menuL->getMenuAttr($data['data'], ['pid']);
        //更改某些字段数据
        $data['data'] = $this->menuL->updateMenuAttr($data['data'], ['pid']);
        $count = $this->menuL->getCountMenu();
        $list_fields = $this->menuL->getFieldsMenu('list_hidden');
		
		$this->assign('list_fields', $list_fields);
		$this->assign('list',$data['data']);
		$this->assign('page',$data['page']);
		$this->assign('count',$count);
        return $this->fetch();
    }

    public function insert(){
        if(Request::instance()->isAjax()){
            $get_data = Request::instance()->post();
            $result = $this->menuL->insert($get_data);
            return json($result);
        }
        $data_list = $this->menuL->getTotalMenu();
        $list_fields = $this->menuL->getFieldsMenu('insert_hidden');
		
		$this->assign('data_list', $data_list);
        $this->assign('list_fields', $list_fields);
    	return $this->fetch();
    }

    public function update(){
    	if(Request::instance()->isAjax()){
    		$get_data = Request::instance()->post();
            $result = $this->menuL->update($get_data);
            return json($result);
    	}else if(Request::instance()->isGet()){
    		$data_list = $this->menuL->getTotalMenu();
    		$id = Request::instance()->param('id');
    		$list_fields = $this->menuL->getFieldsMenu('update_hidden');
    		$info = $this->menuL->getInfo($id, 'getData');
	        
			$this->assign('data_list', $data_list);
			$this->assign('list_fields', $list_fields);
	        $this->assign('info', $info);
	    	return $this->fetch();
    	}
    }

    public function delete(){
    	if(Request::instance()->isAjax()){
    		$get_data = Request::instance()->post();
    		$result = $this->menuL->delete($get_data);
    		return json($result);
    	}
    }
    
    public function search(){
    	if(Request::instance()->isGet()){
    		$get_data = Request::instance()->param();
    		if(empty($get_data['page'])) $get_data['page'] = 1;
    		$data = $this->menuL->getMenu($get_data, 'toArray', $get_data['page']);
    		//取出某些字段数据
	        $get_data = $this->menuL->getMenuAttr($data['data'], ['pid']);
	        //更改某些字段数据
	        $data['data'] = $this->menuL->updateMenuAttr($data['data'], ['pid']);
    		$count = $this->menuL->getCountMenu();
        	$list_fields = $this->menuL->getFieldsMenu('list_hidden');
        	
    		$this->assign('list', $data['data']);
    		$this->assign('page', $data['page']);
    		$this->assign('list_fields', $list_fields);
    		$this->assign('count',$count);
    		return $this->fetch();
    	}
    }

}