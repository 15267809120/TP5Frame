<?php
namespace app\adminer\controller;

use app\adminer\controller\Base;
use think\Request;
use think\Session;
use think\Cookie;
use app\adminer\logic\Singlepage as SinglepageLogic;
use app\adminer\logic\Menu as MenuLogic;

class Singlepage extends Base
{
	public function __construct(){
		parent::__construct();
		$this->pageL = new SinglepageLogic();
		$this->menuL = new MenuLogic();
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
        $data_list = $this->menuL->getTotalMenu();
        $data_list = $this->menuL->getClassList($data_list);
        $list_fields = $this->pageL->getFieldsPage('insert_hidden');
        
		$this->assign('data_list', $data_list['data']);
		$this->assign('max_level', ++$data_list['max_level']);
        $this->assign('list_fields', $list_fields);
    	return $this->fetch();
    }

    public function update(){
    	if(Request::instance()->isAjax()){
    		$get_data = Request::instance()->post();
            $result = $this->pageL->update($get_data);
            return json($result);
    	}else if(Request::instance()->isGet()){
    		$id = Request::instance()->param('id');
    		$data_list = $this->menuL->getTotalMenu();
        	$data_list = $this->menuL->getClassList($data_list);
    		$list_fields = $this->pageL->getFieldsPage('update_hidden');
    		$info = $this->pageL->getInfo($id, 'getData');
	        
	        $this->assign('data_list', $data_list['data']);
			$this->assign('max_level', ++$data_list['max_level']);
			$this->assign('now_level', $data_list['data'][$info['menu_id']]['level']);
			$this->assign('list_fields', $list_fields);
	        $this->assign('info', $info);
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
    		$count = $this->pageL->getCountPage($get_data);
    		if(empty($get_data['page'])) $get_data['page'] = 1;
    		$data = $this->pageL->getPage($get_data, 'toArray', $get_data['page']);
        	$list_fields = $this->pageL->getFieldsPage();
        	
    		$this->assign('list', $data['data']);
    		$this->assign('page', $data['page']);
    		$this->assign('list_fields', $list_fields);
    		$this->assign('count',$count);
    		return $this->fetch();
    	}
    }
    
    public function edit(){
    	if(Request::instance()->isAjax()){
    		
    	}else if(Request::instance()->isGet()){
    		$id = Request::instance()->param('id');
    		
    		$info = $this->pageL->getInfo($id);
    		//获取字段跟数据
    		$data = $this->pageL->getFielsAndData($info);
    		
    		$this->assign('fields', $data['fields']);
    		$this->assign('data', $data['data']);
    		return $this->fetch();
    	}
    }

}