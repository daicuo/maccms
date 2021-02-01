<?php
namespace app\database\controller;

use app\common\controller\Front;

class Index extends Front
{

	public function _initialize(){
		parent::_initialize();
	}
		
	public function index(){
        return lang('database_index_index');
		//return $this->fetch();
	}
	
	//资源路由 index create save read edit update delete 
	//请求类型 get   get    post get  get  put    delete
}