<?php
namespace app\adminer\Logic;

use app\adminer\logic\Base;
use think\Db;
use think\Session;
use think\Cookie;
use think\Config;
use app\adminer\model\ViewAdminUser as ViewAdminGroupModel;
use app\adminer\model\AdminMenu as AdminMenuModel;
use app\adminer\model\AdminGroup as AdminGroupModel;

class Jurisdiction extends Base
{
	public function __construct(){
		parent::__construct();
	}

	//包含了查询
    public function getGroup($where = array(), $operation = 'toArray', $page = 0, $limit = 10){
        $groupM = new AdminGroupModel();
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
        			$count = Db::query('select count(*) as count from '.$prefix.'admin_group where '.$where_str)[0]['count'];
        			if(!$count > 0) return ;
        			
        			$page = (--$page) * $limit;
        			$data = Db::query('select * from '.$prefix.'admin_group where '.$where_str.' order by group_id desc'.' limit '.$page.','.$limit);
        			$total_count = $groupM->count();
        			$data_temp = $data;
        			foreach($data_temp as $key => $value){
        				$data[$key]['jurisdiction'] = $groupM->getJurisdictionAttr($where['search_name'], $value);
        			}
        			if($count > $limit){
        				$total_count = ceil($total_count/ceil($count/$limit));
        			}
        			$data_temp = $groupM->paginate($total_count);
        			return ['data' => $data, 'page' => $data_temp->render()];
        		}
        		return ;
        	}else{
        		$data = $groupM->where($where['search_name'], 'like', '%'.$where['search_value'].'%')->order('group_id desc')->paginate($limit);
        	}
        }else{
        	$data = $groupM->order('group_id desc')->paginate($limit);
        }

        $page = $data->render();
        if($operation == 'toArray')
        	$data = toArray($data);
        else
        	$data = getData($data);

        $result = array('data' => $data, 'page' => $page);
        return $result;
    }

	public function getTotalGroup($operation = 'toArray'){
        $groupM = new AdminGroupModel();
        $data = $groupM::all();
        if($operation == 'toArray'){
        	$data = toArray($data);
        }else{
        	$data = getData($data);
        }
        return $data;
    }

    public function getCountGroup(){
        $groupM = new AdminGroupModel();
        $data = $groupM->count();
        return $data;
    }

    public function getFieldsGroup(){
        $groupM = new AdminGroupModel();
        $data = $groupM->getTableFields();
        return $data;
    }

    public function getInfo($id, $operation){
        $groupM = new AdminGroupModel();
        $data = $groupM::get($id);
        if($operation == 'getData')
        	return $data->getData();
        else
        	return $data->toArray();
    }

    public function insert($data){
        $groupM = new AdminGroupModel();
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
            $groupM->data($insert_data);
            $groupM->save();
            $group_id = $groupM->group_id;
            if($group_id)
                return ['code' => 'success', 'group_id' => $group_id];
            else
                return ['code' => 'error', 'str' => '添加失败'];
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

    public function update($data){
        $groupM = new AdminGroupModel();
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
            $group_id = $insert_data['group_id'];
            unset($insert_data['group_id']);
            $result = $groupM->where('group_id', $group_id)->update($insert_data);

            if($result)
                return ['code' => 'success'];
            else
                return ['code' => 'error', 'str' => '添加失败'];
            // $groupM->data($insert_data);
            // $groupM->save();
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

    public function delete($data){
        $groupM = new AdminGroupModel();
        $result = $this->validate($data, 'Jurisdiction.delete');
        if($result === true){
            $groupM::destroy($data);
            return ['code' => 'success'];
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

}