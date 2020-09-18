<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Search extends Front{
	
	protected $pageIndex = 1;//当前分页
	protected $pageSize = 15;//每页数
	protected $totalRecord = 0;//总记录数
	protected $totalPage = 0;//总页数
	
	//继承
	public function _initialize(){
		parent::_initialize();
		$this->pageIndex = input('page/d',1);
    }
	
	//搜索
	public function index(){
		$wd = input('wd/s');
        $list = apiItem(['ac'=>'videolist', 'wd'=>$wd, 'pg'=>input('page/d',1)]);
        $this->assign($list['page']);
        $this->assign('item', $list['item']);
        $this->assign('wd', $wd);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
        return $this->fetch();
	}
}