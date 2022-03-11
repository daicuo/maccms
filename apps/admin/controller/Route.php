<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Route extends Admin
{
    //定义表单字段列表
    protected function fields($data=[])
    {
        return model('admin/Route','loglic')->fields($data);
    }
    
    //定义表单初始数据
    protected function formData()
    {
        if( $id = input('id/d',0) ){
            return $this->data = \daicuo\Route::get_id($id, false);
		}
        return [];
    }
    
    //定义表格数据（JSON）
    protected function ajaxJson()
    {
        //查询参数
        $args = array();
        $args['cache']    = false;
        $args['field']    = '*';
        $args['limit']    = 0;
        $args['page']     = 1;
        $args['sort']     = input('get.sortName/s','op_id');
        $args['order']    = input('get.sortOrder/s','desc');
        $args['search']   = input('searchText/s','');
        $args['where']    = DcWhereQuery(['op_module','op_controll','op_action','op_status'], 'eq', $this->query);
        //数据返回
        return DcEmpty(model('common/Route','loglic')->select($args),[]);
    }
	
    //新加一条规则到数据库
	public function save()
    {
        config('common.validate_name', 'common/Route');
        
        $id = \daicuo\Route::save(input('post.'));
        
		if($id < 1){
			$this->error(\daicuo\Route::getError());
		}
        
		$this->success(lang('success'));
	}
    
    //删除路由规则
	public function delete()
    {
        $ids = input('id/a');
		if( !$ids ){
			$this->error(lang('mustIn'));
		}
        
        foreach($ids as $id){
            \daicuo\Route::delete_id($id);
        }
        
        $this->success(lang('success'));
	}
	
	//修改一条规则到数据库
	public function update()
    {
        $data = input('post.');
        
        config('common.validate_name', 'common/Route');
        
        $info = \daicuo\Route::update_id($data['op_id'], $data);
        
        if(is_null($info)){
            $this->error(\daicuo\Route::getError());
        }
    
        $this->success(lang('success'));
	}
    
    //排序（拖拽ajax）
	public function sort()
    {
		if( !\daicuo\Op::sort(input('get.id')) ){
            $this->error(lang('fail'));
        }
        
        DcCache('route_all', NULL);
        
        $this->success(lang('success'));
	}
    
    //预览
	public function preview()
    {
		if( !$id = input('id/d',0) ){
			$this->error(lang('mustIn'));
		}
        
        $data = \daicuo\Route::get_id($id, false);
        
        $this->redirect(DcUrlAdmin($data['address']), 302);
	}
}