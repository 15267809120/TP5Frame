<?php
namespace app\adminer\controller;

use app\adminer\controller\Base;
use think\Request;
use think\Session;
use think\Cookie;
use app\adminer\logic\Singlepage as SinglepageLogic;

class Singlepage extends Base
{
	public function __construct(){
		parent::__construct();
		$this->pageL = new SinglepageLogic();
	}

	public function pageList(){
        $data = $this->pageL->getPage();
        $count = $this->pageL->getCountPage();
        $list_fields = $this->pageL->getFieldsPage();

		$this->assign('list_fields', $list_fields);
		$this->assign('list',$data['data']);
		$this->assign('page',$data['page']);
		$this->assign('count',$count);
        return $this->fetch();
    }

    public function insert(){
        if(Request::instance()->isAjax()){
            $get_data = Request::instance()->post();
            $result = $this->pageL->insert($get_data);
            return json($result);
        }
        $list_fields = $this->pageL->getFieldsPage('insert_hidden');
        $menu = Cookie::get('menu');
        
        $this->assign('list_fields', $list_fields);
        $this->assign('menu_list', $menu);
    	return $this->fetch();
    }

    public function update(){
    	if(Request::instance()->isAjax()){
    		$get_data = Request::instance()->post();
            $result = $this->pageL->update($get_data);
            return json($result);
    	}else if(Request::instance()->isGet()){
    		$group_id = Request::instance()->param('group_id');
    		$list_fields = $this->pageL->getFieldsPage('update_hidden');
    		$info = $this->pageL->getInfo($group_id, 'getData');
	        $menu = Cookie::get('menu');

			$this->assign('list_fields', $list_fields);
	        $this->assign('info', $info);
	        $this->assign('menu_list', $menu);
	    	return $this->fetch();
    	}
    }

    public function delete(){
    	if(Request::instance()->isAjax()){
    		$get_data = Request::instance()->post();
    		$result = $this->pageL->delete($get_data);
    		return json($result);
    	}
    }
    
    public function search(){
    	if(Request::instance()->isGet()){
    		$get_data = Request::instance()->param();
    		if(empty($get_data['page'])) $get_data['page'] = 1;
    		$data = $this->pageL->getPage($get_data, 'toArray', $get_data['page']);
        	$list_fields = $this->pageL->getFieldsPage();
        	
    		$this->assign('list', $data['data']);
    		$this->assign('page', $data['page']);
    		$this->assign('list_fields', $list_fields);
    		$this->assign('count',$data['count']);
    		return $this->fetch();
    	}
    }

}