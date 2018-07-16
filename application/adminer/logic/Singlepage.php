<?php
namespace app\adminer\Logic;

use app\adminer\logic\Base;
use think\Db;
use think\Session;
use think\Cookie;
use think\Config;
use app\adminer\model\SinglePage as SinglePageModel;
use app\adminer\model\AdminMenu as AdminMenuModel;

class Singlepage extends Base
{
	public function __construct(){
		parent::__construct();
	}

	//包含了查询
    public function getPage($where = array(), $operation = 'toArray', $page = 0, $limit = 10){
        $pageM = new SinglePageModel();
        if(!empty($where['search_value'])){
        	//需要二次查表的方法，入口
        	if($where['search_name'] == 'jurisdiction'){
        		$menuM = new AdminMenuModel();
        		$result = $menuM->where('name', 'like', '%'.$where['search_value'].'%')->select();
        		$result = toArray($result);
        		if($result){
        			$where_str = '';
        			foreach($result as $key => $value){
        				$temp_array[$key] =$value['id'];
        				$where_str .= ' or '.$where['search_name'].' like "%,'.$value['id'].'" or '.$where['search_name'].' like "%,'.$value['id'].',%" or '.$where['search_name'].' like "'.$value['id'].',%"';
        			}
        			$where_str = ltrim($where_str, ' or');
        			$prefix = Config::get('database.prefix');
        			$count = Db::query('select count(*) as count from '.$prefix.'admin_page where '.$where_str)[0]['count'];
        			if(!$count > 0) return ;
        			
        			$page = (--$page) * $limit;
        			$data = Db::query('select * from '.$prefix.'admin_page where '.$where_str.' order by id desc'.' limit '.$page.','.$limit);
        			$total_count = $pageM->count();
        			$data_temp = $data;
        			foreach($data_temp as $key => $value){
        				$data[$key]['jurisdiction'] = $pageM->getJurisdictionAttr($where['search_name'], $value);
        			}
        			if($count > $limit){
        				$total_count = ceil($total_count/ceil($count/$limit));
        			}
        			$data_temp = $pageM->paginate($total_count);
        			return ['data' => $data, 'page' => $data_temp->render(), 'count' => $count];
        		}
        		return ;
        	}else{
        		$data = $pageM->where($where['search_name'], 'like', '%'.$where['search_value'].'%')->order('id desc')->paginate($limit);
        		$count = $pageM->where($where['search_name'], 'like', '%'.$where['search_value'].'%')->count();
        	}
        }else{
        	$data = $pageM->order('id desc')->paginate($limit);
        	$count = $pageM->count();
        }

        $page = $data->render();
        if($operation == 'toArray')
        	$data = toArray($data);
        else
        	$data = getData($data);

        $result = array('data' => $data, 'page' => $page, 'count' => $count);
        return $result;
    }

	public function getTotalPage($operation = 'toArray'){
        $pageM = new SinglePageModel();
        $data = $pageM::all();
        if($operation == 'toArray'){
        	$data = toArray($data);
        }else{
        	$data = getData($data);
        }
        return $data;
    }

    public function getCountPage(){
        $pageM = new SinglePageModel();
        $data = $pageM->count();
        return $data;
    }

    public function getFieldsPage($operation = 'all'){
        $pageM = new SinglePageModel();
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
        $pageM = new SinglePageModel();
        $data = $pageM::get($id);
        if($operation == 'getData')
        	return $data->getData();
        else
        	return $data->toArray();
    }

    public function insert($data){
        $pageM = new SinglePageModel();
        $keys = array_keys($data);
        foreach($keys as $key => $value){
        	if(strpos($value, 'id-') !== false){
        		$data['jurisdiction'] = 1;
        		break;
        	}
        }
        $result = $this->validate($data, 'Jurisdiction.insert');
        if($result === true){
            $insert_data = $data;
            $insert_data['jurisdiction'] = '';
            foreach($data as $key => $value){
                if(strpos($key, 'id-') !== false){
                    $insert_data['jurisdiction'] .= ltrim($key, 'id-') . ',';
                    unset($insert_data[$key]);
                }
            }
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
        $pageM = new SinglePageModel();
        $keys = array_keys($data);
        foreach($keys as $key => $value){
        	if(strpos($value, 'id-') !== false){
        		$data['jurisdiction'] = 1;
        		break;
        	}
        }
        $result = $this->validate($data, 'Jurisdiction.update');
        if($result === true){
            $insert_data = $data;
            $insert_data['jurisdiction'] = '';
            foreach($data as $key => $value){
                if(strpos($key, 'id-') !== false){
                    $insert_data['jurisdiction'] .= ltrim($key, 'id-') . ',';
                    unset($insert_data[$key]);
                }
            }
            $id = $insert_data['id'];
            unset($insert_data['id']);
            $result = $pageM->where('id', $id)->update($insert_data);

            if($result)
                return ['code' => 'success'];
            else
                return ['code' => 'error', 'str' => '添加失败'];
            // $pageM->data($insert_data);
            // $pageM->save();
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

    public function delete($data){
        $pageM = new SinglePageModel();
        $userM = new AdminUserModel();
        $result = $this->validate($data, 'Jurisdiction.delete');
        if($result === true){
        	//先查询是否有管理员是该分组的
        	$isSet = $userM::get(['page', $data['id']]);
        	if($isSet){
        		return ['code' => 'error', 'str' => '该分组下有管理员，无法删除'];
        	}else{
        		$pageM::destroy($data);
            	return ['code' => 'success'];
        	}
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

}