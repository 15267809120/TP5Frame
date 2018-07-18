<?php
namespace app\adminer\Logic;

use app\adminer\logic\Base;
use think\Db;
use think\Session;
use think\Cookie;
use think\Config;
use app\adminer\model\AdminMenu as AdminMenuModel;
use app\adminer\model\SinglePage as SinglePageModel;
use app\adminer\model\ViewSinglePage as ViewSinglePageModel;

class Singlepage extends Base
{
	public function __construct(){
		parent::__construct();
	}

	//包含了查询
    public function getPage($where = array(), $operation = 'toArray', $page = 0, $limit = 10){
        $pageM = new ViewSinglePageModel();
        if(!empty($where['search_value'])){
        	$data = $pageM->where($where['search_name'], 'like', '%'.$where['search_value'].'%')->order('id desc')->paginate($limit);
        }else{
        	$data = $pageM->order('id desc')->paginate($limit);
        }

        $page = $data->render();
        if($operation == 'toArray')
        	$data = toArray($data);	
        else
        	$data = getData($data);
        
        $data_temp = array();
        if(!empty($data)){
	        foreach($data as $key => $value){
	        	$data_temp[$value['id']] = $value;
	        }
        }

        $result = array('data' => $data_temp, 'page' => $page);
        return $result;
    }

	public function getTotalPage($operation = 'toArray'){
        $pageM = new ViewSinglePageModel();
        $data = $pageM::all();
        if($operation == 'toArray'){
        	$data = toArray($data);
        }else{
        	$data = getData($data);
        }
        return $data;
    }

    public function getCountPage($where = array()){
        $MenuM = new ViewSinglePageModel();
        if(empty($where['search_value'])){
        	$data = $MenuM->count();
        }else{
        	$data = $MenuM->where($where['search_name'], 'like', '%'.$where['search_value'].'%')->count();
        }
        return $data;
    }

    public function getFieldsPage($operation = 'all'){
        $pageM = new ViewSinglePageModel();
        $data = $pageM->getTableFields();
        if($operation == 'insert_hidden'){
        	$hidden = $pageM->insert_hidden;
        }else if($operation == 'update_hidden'){
        	$hidden = $pageM->update_hidden;
        }else if($operation == 'hidden'){
			$hidden = $pageM->hidden;
        }
        if($operation != 'all'){
        	$data = array_flip($data);
	        foreach($hidden as $key => $value){
	        	if(isset($data[$value])) unset($data[$value]);
	        }
	        $data = array_flip($data);
        }

        return $data;
    }

    public function getInfo($id, $operation = 'toArray'){
        $pageM = new ViewSinglePageModel();
        $data = $pageM::get($id);
        if($operation == 'getData')
        	return $data->getData();
        else
        	return $data->toArray();
    }

    public function insert($data){
        $pageM = new SinglePageModel();
        $keys = array_keys($data);
        $result = $this->validate($data, 'SinglePage.insert');
        if($result === true){
            $insert_data = $data;
            $pageM->data($insert_data);
            $pageM->save();
            $id = $pageM->id;
            if($id)
                return ['code' => 'success', 'id' => $id];
            else
                return ['code' => 'error', 'str' => '添加失败'];
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

    public function update($data){
        $pageM = new ViewSinglePageModel();
        $keys = array_keys($data);
        $result = $this->validate($data, 'SinglePage.update');
        if($result === true){
        	$id = $data['id'];
        	unset($data['id']);
            $insert_data = $data;
            $result = $pageM->where('id', $id)->update($insert_data);

            if($result)
                return ['code' => 'success'];
            else
                return ['code' => 'error', 'str' => '修改失败'];
            // $pageM->data($insert_data);
            // $pageM->save();
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

    public function delete($data){
        $pageM = new SinglePageModel();
        $result = $this->validate($data, 'SinglePage.delete');
        if($result === true){
    		$pageM::destroy($data);
        	return ['code' => 'success'];
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }
    
    public function getFielsAndData($info = array()){
    	if(empty($info)) return ;
    	$fields_temp = explode('|', $info['field']);
    	foreach($fields_temp as $key => $value){
    		$temp = explode(':', $value);
    		$fields[$key]['fields'] = $temp[0];
    		$fields[$key]['type'] = $temp[1];
    	}
    	if(!empty($info['content'])){
    		$data_temp = explode('|', $info['content']);
    		foreach($data_temp as $key => $value){
	    		$temp = explode(':', $value);
	    		$data[$temp[0]] = $temp[1];
	    	}
    	}else{
    		$data = '';
    	}
    	
    	return ['fields' => $fields, 'data' => $data];
    }

}