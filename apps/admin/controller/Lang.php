<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Lang extends Admin
{
    //定义表单字段列表
    protected function fields($data=[])
    {
        return model('admin/Lang','loglic')->fields($data);
    }
    
    //定义表单初始数据
    protected function formData()
    {
        if( $id = input('id/d',0) ){
            return \daicuo\Op::data_value( \daicuo\Op::get_id($id, false) );
		}
        return [];
    }

    //定义表格数据（JSON）
    protected function ajaxJson()
    {
        //查询参数
        $args = array();
        $args['cache']    = false;
        $args['field']    = 'op_id,op_name,op_value,op_module,op_controll,op_action,op_order,op_status';
        $args['limit']    = DcEmpty($this->query['pageSize'],50);
        $args['page']     = DcEmpty($this->query['pageNumber'],1);
        $args['sort']     = DcEmpty($this->query['sortName'],'op_id');
        $args['order']    = DcEmpty($this->query['sortOrder'],'desc');
        $args['search']   = input('searchText/s','');
        $args['status']   = $this->query['op_status'];
        $args['module']   = $this->query['op_module'];
        $args['controll'] = 'lang';
        $args['action']   = $this->query['op_action'];
        //格式化数据
        $list = model('common/Lang','loglic')->select(DcArrayEmpty($args));
        //数据返回
        return DcEmpty($list,['total'=>0,'data'=>[]]);
    }
    
	public function save()
    {
		if( !$op_id = model('common/Config','loglic')->write(input('post.')) ){
			$this->error(\daicuo\Op::getError());
		}
        
        DcCache('lang_all', null);
        
		$this->success(lang('success'));
	}
    
	public function delete()
    {
        $ids = input('id/a');
		if(!$ids){
			$this->error(lang('mustIn'));
		}
        
        model('common/Config','loglic')->deleteIds($ids);
        
        DcCache('lang_all', null);
        
        $this->success(lang('success'));
	}
	
	//修改一条规则到数据库
	public function update()
    {
        $result = model('common/Lang','loglic')->write(input('post.'));
        
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