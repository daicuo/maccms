<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Field extends Admin
{
    //定义表单字段列表
    protected function fields($data=[])
    {
        return model('admin/Field','loglic')->fields($data);
    }
    
    //定义表单初始数据
    protected function formData()
    {
        if( $id = input('id/d',0) ){
            return model('common/Config','loglic')->getId($id, false);
		}
        return [];
    }

    //定义表格数据（JSON）
    public function ajaxJson()
    {
        //查询参数
        $args = array();
        $args['cache']    = false;
        $args['result']   = 'array';
        $args['autoload'] = 'field';
        $args['search']   = $this->query['searchText'];
        $args['limit']    = input('pageSize/d', 50);
        $args['page']     = input('pageNumber/d', 1);
        $args['sort']     = input('sortName/s','op_id');
        $args['order']    = input('sortOrder/s','desc');
        $args['search']   = input('searchText/s','');
        $args['status']   = $this->query['op_status'];
        $args['module']   = $this->query['op_module'];
        $args['controll'] = $this->query['op_controll'];
        $args['action']   = $this->query['op_action'];
        //查询数据
        $list = model('common/Field','loglic')->select( DcArrayEmpty($args) );
        foreach($list['data'] as $key=>$value){
            $list['data'][$key] = model('common/Field','loglic')->dataGet($value);
        }
        //数据返回
        return DcEmpty($list,['total'=>0,'data'=>[]]);
	}
    
	//添加(数据库)
	public function save()
    {
        $term_id = model('common/Field','loglic')->write(input('post.'));
        
		if($term_id < 1){
			$this->error(\daicuo\Op::getError());
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
        
        model('common/Config','loglic')->deleteIds($ids);
        
        $this->success(lang('success'));
	}
	
	//修改（数据库）
	public function update()
    {
		$result = model('common/Field','loglic')->write(input('post.'));
        
        if(is_null($result)){
            $this->error(\daicuo\Op::getError());
        }
        
        $this->success(lang('success'));
	}
    
    //快速修改状态
    public function status()
    {
        if( !$ids = input('post.id/a') ){
            $this->error(lang('errorIds'));
        }
        
        $result = model('common/Config','loglic')->status($ids,input('request.value/s', 'hidden'));
        
        $this->success(lang('success'));
    }
}