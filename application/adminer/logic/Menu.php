<?php
namespace app\adminer\Logic;

use app\adminer\logic\Base;
use think\Db;
use think\Session;
use think\Cookie;
use think\Config;
use app\adminer\model\Menu as MenuModel;

class Menu extends Base
{
	public function __construct(){
		parent::__construct();
	}

	//包含了查询
    public function getMenu($where = array(), $operation = 'toArray', $page = 0, $limit = 10){
        $MenuM = new MenuModel();
        if(!empty($where['search_value'])){
        	$data = $MenuM->where($where['search_name'], 'like', '%'.$where['search_value'].'%')->where('is_show',1)->order('id desc')->paginate($limit);
        }else{
        	$data = $MenuM->where('is_show',1)->order('id desc')->paginate($limit);
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
    
    public function getTotalMenu($operation = 'toArray'){
        $menuM = new MenuModel();
        $data = $menuM->where('is_show',1)->select();
        if($operation == 'toArray'){
        	$data = toArray($data);
        }else{
        	$data = getData($data);
        }
        $data_temp = array();
        if(!empty($data)){
	        foreach($data as $key => $value){
	        	$data_temp[$value['id']] = $value;
	        }
        }
        
        return $data_temp;
    }

    public function getCountMenu(){
        $MenuM = new MenuModel();
        $data = $MenuM->count();
        return $data;
    }

    public function getFieldsMenu($operation = 'all'){
        $MenuM = new MenuModel();
        $data = $MenuM->getTableFields();
        if($operation == 'list_hidden'){
        	$hidden = $MenuM->list_hidden;
        }else if($operation == 'insert_hidden'){
        	$hidden = $MenuM->insert_hidden;
        }else if($operation == 'update_hidden'){
        	$hidden = $MenuM->update_hidden;
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
    
    public function getMenuAttr($data, $attr = array()){
    	if($attr == '') return $data;
    	foreach($data as $key => $value){
    		foreach($attr as $k){
    			$data_temp[$key][$k] = $value[$k];
    		}
    	}
    	return $data_temp;
    }    

    public function updateMenuAttr($data, $attr = array()){
    	if($attr == '') return $data;
    	$data_temp = $data;
    	$total_menu = $this->getTotalMenu();
    	foreach($attr as $key){
    		if($key == 'pid'){
	    		foreach($data as $k => $val){
	    			if($val['pid']){
	    				$data_temp[$k]['pid'] = $total_menu[$val['pid']]['name'];
	    			}else if($val['pid'] === 0){
	    				$data_temp[$k]['pid'] = '';
	    			}
	    		}
	    	}
    	}
    	
    	return $data_temp;
    }

    public function getInfo($id, $operation = 'toArray'){
        $MenuM = new MenuModel();
        $data = $MenuM::get($id);
        if($operation == 'getData')
        	return $data->getData();
        else
        	return $data->toArray();
    }

    public function insert($data){
        $MenuM = new MenuModel();
        $result = $this->validate($data, 'Menu.insert');
        if($result === true){
            $insert_data = $data;
            $MenuM->data($insert_data);
            $MenuM->save();
            $id = $MenuM->id;
            if($id)
                return ['code' => 'success', 'id' => $id];
            else
                return ['code' => 'error', 'str' => '添加失败'];
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

    public function update($data){
        $MenuM = new MenuModel();
        $result = $this->validate($data, 'Menu.update');
        if($result === true){
            $insert_data = $data;
            $id = $insert_data['id'];
            unset($insert_data['id']);
            $result = $MenuM->where('id', $id)->update($insert_data);

            if($result)
                return ['code' => 'success'];
            else
                return ['code' => 'error', 'str' => '添加失败'];
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

    public function delete($data){
        $MenuM = new MenuModel();
        $result = $this->validate($data, 'Menu.delete');
        if($result === true){
        	$result = $MenuM->where('pid',$data['id'])->find();
        	if($result) return ['code' => 'error', 'str' => '该菜单下还有子菜'];
            $MenuM::destroy($data);
            return ['code' => 'success'];
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

}