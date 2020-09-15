<?php

// 模块公用函数
function DcAdminApply(){
	$dirs = array();
	foreach(glob(APP_PATH.'*',GLOB_ONLYDIR) as $key=>$value){
		if(!in_array(basename($value),array('admin','api','common','extra','lang','user'))){
			array_push($dirs, basename($value));
		}
	}
	return $dirs;
}

// 后台生成前台路径
function DcAdminUrl($url = '', $vars = '', $suffix = true, $domain = false){
	$baseFile = request()->baseFile();
	return str_replace($baseFile,'',url($url, $vars, $suffix, $domain));
}