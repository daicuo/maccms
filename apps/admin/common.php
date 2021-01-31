<?php
// 模块公用函数
function DcAdminApply(){
	$dirs = array();
	foreach(glob(APP_PATH.'*',GLOB_ONLYDIR) as $key=>$value){
		if(!in_array(basename($value),array('admin','api','common','extra','lang'))){
			array_push($dirs, basename($value));
		}
	}
	return $dirs;
}