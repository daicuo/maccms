<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Category extends Front
{
	//继承
	public function _initialize()
    {
		parent::_initialize();
	}
    
    //分类ID
    public function index()
    {
        //数据查询
        if( isset($this->query['id']) ){
            $term = categoryId(input('id/d'));
        }elseif( isset($this->query['slug']) ){
            $term = categorySlug($this->query['slug']);
        }else{
            $this->error(lang('mustIn'),'maccms/index/index');
        }
        //API参数
        $term['term_api_pg'] = $this->site['page'];
        //API数据
        $list = apiTerm($term);
        //公用变量
        $this->assign($term);
        $this->assign($list['page']);
        $this->assign('item', $list['item']);
        //是否AJAX请求
        if($this->request->isAjax()){
            if($this->query['page'] > $list['page']['last_page']){
                return null;
            }
            return $this->fetch('ajax');
        }
        //分页链接
        $this->assign('pages',DcPage(
            $list['page']['current_page'], 
            $list['page']['per_page'],
            $list['page']['total'],
            categoryUrl($term['term_id'], $term['term_slug'], '[PAGE]')
        ));
        return $this->fetch();
    }
}