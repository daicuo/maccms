<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Menu extends Admin
{
    //定义表单字段列表
    protected function fields($data=[])
    {
        return model('admin/Menu','loglic')->fields($data);
    }
    
    //定义表单初始数据
    protected function formData()
    {
        if( $id = input('id/d',0) ){
            return model('common/Term','loglic')->getId($id, false);
		}
        return [];
    }

    //定义表格数据（JSON）
    public function ajaxJson()
    {
        //查询参数
        $args = array();
        $args['cache']    = false;
        $args['result']   = 'level';
        $args['controll'] = 'menus';
        $args['search']   = $this->query['searchText'];
        $args['limit']    = 0;
        $args['page']     = 0;
        $args['sort']     = 'term_parent asc,term_order';
        $args['order']    = 'desc';
        $args['status']   = $this->query['term_status'];
        $args['action']   = $this->query['term_action'];
        $args['module']   = $this->query['term_module'];
        //搜索关键字只能返回数组
        if($args['search'] || $args['module'] || $args['action'] || $args['status']){
            $args['result']  = 'array';
        }
        //查询数据
        $list = model('common/Menu','loglic')->select( DcArrayEmpty($args) );
        //返回结果
        return DcEmpty($list,[]);
	}
    
	//添加(数据库)
	public function save()
    {
        $term_id = model('common/Menu','loglic')->write(input('post.'));
        
		if($term_id < 1){
			$this->error(\daicuo\Term::getError());
		}
        
		$this->success(lang('success'));
	}
    
    //删除(数据库)
	public function delete()
    {
		$ids = input('id/a');
		if(!$ids){
			$this->error(lang('mustIn'));
		}

        $result = model('common/Term','loglic')->deleteIds($ids);
        
        $this->success(lang('success'));
	}
	
	//修改（数据库）
	public function update()
    {
		$data = input('post.');
        if(!$data['term_id']){
            $this->error(lang('mustIn'));
        }
        
        $info = model('common/Menu','loglic')->write($data);
        
        if(is_null($info)){
            $this->error(\daicuo\Term::getError());
        }
        
        $this->success(lang('success'));
	}
    
    //快速修改状态
    public function status()
    {
        if( !$ids = input('post.id/a') ){
            $this->error(lang('errorIds'));
        }
        
        $result = model('common/Term','loglic')->status($ids, input('request.value/s', 'hidden'));
        
        $this->success(lang('success'));
    }
}