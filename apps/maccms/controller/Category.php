<?php
namespace app\maccms\controller;

use app\common\controller\Front;

/*
** MacCms分类页
*/

class Category extends Front
{
	protected $cateId = 0;//分类ID
	
	//继承
	public function _initialize(){
		parent::_initialize();
	}
    
    //分类ID
    public function index(){
        $term = categoryId(input('id/d'));
        $term['term_api_pg'] = input('page/d',1);
        $list = apiTerm($term);
        $this->assign($this->query);
        $this->assign($term);
        $this->assign($list['page']);
        $this->assign('item', $list['item']);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
        $this->assign('pages',DcPage($list['page']['current_page'], $list['page']['per_page'], $list['page']['total'],
			DcUrl('maccms/category/index',['id'=>$term['term_id'], 'page'=>'[PAGE]'],'')));
        return $this->fetch();
    }
    
    //空操作
	public function _empty($name){
        $term = categorySlug(DcHtml($name));
        $term['term_api_pg'] = input('page/d',1);
        $list = apiTerm($term);
        $this->assign($this->query);
        $this->assign($term);
        $this->assign($list['page']);
        $this->assign('item', $list['item']);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
        $this->assign('pages',DcPage($list['page']['current_page'], $list['page']['per_page'], $list['page']['total'],
			DcUrl('maccms/category/'.$name,['page'=>'[PAGE]'],'')));
        return $this->fetch('index');
	}
}