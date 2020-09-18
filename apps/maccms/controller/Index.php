<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Index extends Front
{

	public function _initialize(){
		parent::_initialize();
        header("Content-Type:text/html; charset=utf-8");
	}
		
	public function index(){
        //$abc = apiDetail(64363);//'https://cj.okzy.tv/inc/feifei3s_subname'
        //$abc = apiCategory(6);
        //$abc = apiItem(['t'=>18,'pg'=>1,'limit'=>5]);
        //$abc = apiHour(24);
        //$abc = apiSearch('木兰');
        //$abc = apiDetail(10791,'http://demo.zhiyingtuan.com/feifeicms/index.php');
        //$abc = apiCategory(18,'http://demo.zhiyingtuan.com/feifeicms/index.php');
        //$abc = apiItem(['t'=>18,'pg'=>2,'limit'=>5],'http://demo.zhiyingtuan.com/feifeicms/index.php');
        //$abc = apiHour(1,'http://demo.zhiyingtuan.com/feifeicms/index.php');
        //$abc = apiSearch('木兰');
        dump($abc);
        exit();
        //dump(categoryItem());
        //dump(navItem());
		return $this->fetch();
	}
	
	//资源路由 index create save read edit update delete 
	//请求类型 get   get    post get  get  put    delete
}