<?php
namespace app\adminer\Logic;

use app\adminer\logic\Base;
use think\Db;
use think\Session;
use think\Cookie;
use think\Config;
use app\adminer\model\AdminUser as AdminUserModel;
use app\adminer\model\AdminGroup as AdminGroupModel;
use app\adminer\model\ViewAdminUser as ViewAdminUserModel;

class User extends Base
{
	public function __construct(){
		parent::__construct();
	}

	//包含了查询
    public function getUser($where = array(), $operation = 'toArray', $page = 0, $limit = 10){
        $UserM = new ViewAdminUserModel();
        if(!empty($where['search_value'])){
        	//需要二次查表的方法，入口
        	if($where['search_name'] == 'jurisdiction'){

        	}else{
        		$data = $UserM->where($where['search_name'], 'like', '%'.$where['search_value'].'%')->order('uid desc')->paginate($limit);
        	}
        }else{
        	$data = $UserM->order('uid desc')->paginate($limit);
        }

        $page = $data->render();
        if($operation == 'toArray')
        	$data = toArray($data);
        else
        	$data = getData($data);

        $result = array('data' => $data, 'page' => $page);
        return $result;
    }

    public function getCountUser(){
        $UserM = new AdminUserModel();
        $data = $UserM->count();
        return $data;
    }

    public function getFieldsUser($operation = 'all'){
        $UserM = new ViewAdminUserModel();
        $data = $UserM->getTableFields();
        if($operation == 'list_hidden'){
        	$hidden = $UserM->list_hidden;
        }else if($operation == 'insert_hidden'){
        	$hidden = $UserM->insert_hidden;
        }else if($operation == 'update_hidden'){
        	$hidden = $UserM->update_hidden;
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
        $UserM = new AdminUserModel();
        $data = $UserM::get($id);
        if($operation == 'getData')
        	return $data->getData();
        else
        	return $data->toArray();
    }

    public function insert($data){
        $UserM = new AdminUserModel();
        $data['reg_time'] = time();
        $result = $this->validate($data, 'User.insert');
        if($result === true){
            $insert_data = $data;
            $UserM->data($insert_data);
            $UserM->save();
            $uid = $UserM->uid;
            if($uid)
                return ['code' => 'success', 'uid' => $uid];
            else
                return ['code' => 'error', 'str' => '添加失败'];
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

    public function update($data){
        $UserM = new AdminUserModel();
        $result = $this->validate($data, 'User.update');
        if($result === true){
            $insert_data = $data;
            $uid = $insert_data['uid'];
            unset($insert_data['uid']);
            $result = $UserM->where('uid', $uid)->update($insert_data);

            if($result)
                return ['code' => 'success'];
            else
                return ['code' => 'error', 'str' => '添加失败'];
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

    public function delete($data){
        $UserM = new AdminUserModel();
        $result = $this->validate($data, 'User.delete');
        if($result === true){
            $UserM::destroy($data);
            return ['code' => 'success'];
        }else{
            return ['code' => 'error', 'str' => $result];
        }
    }

}