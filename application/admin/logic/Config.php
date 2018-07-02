<?php
namespace app\admin\Logic;

use think\Controller;
use think\Db;
use think\Session;
use app\admin\validate\Config as ConfigValidate;
use app\admin\model\Config as ConfigModel;

class Config extends Controller
{
	public function initialize(){
		parent::initialize();

	}

    public function getConfig($identification = ''){
        $configM = new ConfigModel();
        if(empty($identification)){
            $result = $configM->select();
        }else{
            $result = $configM->where('identification', "$identification")->select();
        }
        $data = toArray($result);
        return $data;
    }

    public function updateConfig($array = array()){
        $configM = new ConfigModel();
        foreach($array as $key => $value){
            $id = ltrim($key, 'id-');
            $update_data[$id]['id'] = $id;
            $update_data[$id]['value'] = $value;
        }
        $result = $configM->saveAll($update_data);
        return $result;
    }

}