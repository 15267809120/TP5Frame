<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function toArray($array){
	$count = count($array);
	if($count > 0){
		foreach($array as $key => $value){
			$data[$key] = $value->toArray();
		}
	}else{
		$data = '';
	}
	return $data;
}

function getData($array){
	$count = count($array);
	if($count > 0){
		foreach($array as $key => $value){
			$data[$key] = $value->getData();
		}
	}else{
		$data = '';
	}
	return $data;
}
//移除不需要的字段
function removeFields($get_data, $fields){
	foreach($fields as $key => $value){
		if(isset($get_data[$value])) $data[$value] = $get_data[$value];
	}
	return $data;
}
//获取需要的字段
function getAttr($data, $attr = array()){
	if($attr == '' || empty($data)) return $data;
	foreach($data as $key => $value){
		foreach($attr as $k){
			$data_temp[$key][$k] = $value[$k];
		}
	}
	return $data_temp;
}