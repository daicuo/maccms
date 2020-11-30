<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Index extends Front
{

	public function _initialize(){
		parent::_initialize();
	}
		
	public function index(){
        //dump(apiField('area','台湾',['t'=>2,'limit'=>6]));
        //exit();
		return $this->fetch();
	}
	
	//资源路由 index create save read edit update delete 
	//请求类型 get   get    post get  get  put    delete
}