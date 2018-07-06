<?php
namespace app\adminer\Logic;

use app\adminer\logic\Base;
use think\Db;
use think\Session;
use app\adminer\validate\Config as ConfigValidate;
use app\adminer\model\Config as ConfigModel;

class Config extends Base
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

    public function updateConfig($where = array()){
        $configM = new ConfigModel();
        foreach($where as $key => $value){
            $id = ltrim($key, 'id-');
            $update_data[$id]['id'] = $id;
            $update_data[$id]['value'] = $value;
        }
        $result = $configM->saveAll($update_data);
        return $result;
    }

}