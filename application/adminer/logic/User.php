<?php
namespace app\adminer\Logic;

use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;
use app\adminer\model\AdminGroup as AdminGroupModel;
use app\adminer\model\AdminMenu as AdminMenuModel;


class User extends Controller
{
	public function initialize(){
		parent::initialize();

	}

    public function getGroup(){
        $groupM = new AdminGroupModel();
        $data = $groupM->order('group_id desc')->paginate(10);
        $page = $data->render();
        $data = toArray($data);

        $menu = Cookie::get('menu_list');
        foreach($menu as $key => $value){
            $temp_menu[$value['id']] = $value;
        }
        foreach($data as $key => $value){
            if(empty($value['jurisdiction'])) continue;
            $array = explode(',', rtrim($value['jurisdiction'], ','));
            $str = '';
            foreach($array as $k => $val){
                
                $str .= $temp_menu[$val]['name'].',';
            }
            $data[$key]['jurisdiction'] = $str;
        }
        $result = array('data' => $data, 'page' => $page);
        return $result;
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

    public function getInfo($id){
        $groupM = new AdminGroupModel();
        $data = $groupM::get($id);
        if($data) return $data->toArray();
    }

    public function insert($data){
        $groupM = new AdminGroupModel();
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
            // $groupM->data($insert_data);
            // $groupM->save();
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

    public function update($data){
        $groupM = new AdminGroupModel();
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

    public function findGroup($where){
        $groupM = new AdminGroupModel();
        $result = $groupM->where($where['search_name'], 'like', '%'.$where['search_value'].'%')->order('group_id desc')->select();
        if($result){
            return ['code' => 'success', 'data' => $result];
        }else{
            return ['code' => 'error'];
        }
    }

}