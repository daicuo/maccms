<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Search extends Front{
	
	//继承
	public function _initialize()
    {
		parent::_initialize();
    }
    
    //空操作
	public function index()
    {
        $wd = maccmsSearch(urldecode($this->query['wd']));
        
        $list = apiItem(['wd'=>$wd,'pg'=>$this->site['page']]);
        
        if($this->site['page'] > $list['page']['last_page']){
          return '';
        }
        $this->assign($this->query);
        $this->assign($list['page']);
        $this->assign('type', $list['type']);
        $this->assign('item', $list['item']);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
        $this->assign('pages',DcPage(
            $list['page']['current_page'],
            $list['page']['per_page'],
            $list['page']['total'],
			DcUrl('maccms/search/index',['wd'=>DcHtml($this->query['wd']), 'pageNumber'=>'[PAGE]'])
        ));
        return $this->fetch('index');
	}
    
    //空操作
    public function _empty($name)
    {
        return DcHtml($name);
    }
}