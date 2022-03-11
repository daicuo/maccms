<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Auth extends Admin
{
    //定义表单字段列表
    protected function fields($data=[])
    {
        return model('admin/Auth','loglic')->fields($data);
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
        $args['search']   = $this->query['searchText'];
        $args['name']     = $this->query['op_name'];
        $args['status']   = $this->query['op_status'];
        $args['module']   = $this->query['op_module'];
        $args['action']   = $this->query['op_action'];
        $args['controll'] = 'auth';
        //格式化数据
        $list = model('common/Config','loglic')->select(DcArrayEmpty($args));
        $role = model('common/Role','loglic')->option();
        foreach($list['data'] as $key=>$value){
            $list['data'][$key]['role_info'] = $role[$value['op_name']];
            $list['data'][$key]['auth_info'] = lang(DcParseUrl($value['op_value'],'path'));
        }
        //数据返回
        return DcEmpty($list,['total'=>0,'data'=>[]]);
    }
    
	public function save()
    {
        config('common.validate_name', 'common/Op');
        
		if( !$op_id = \daicuo\Op::save(input('post.')) ){
			$this->error(\daicuo\Op::getError());
		}
        
        DcCache('auth_all', null);
        
		$this->success(lang('success'));
	}
    
	//快速删除数据
    public function delete()
    {
        $ids = input('id/a');
		if(!$ids){
			$this->error(lang('mustIn'));
		}
        
        \daicuo\Op::delete_id($ids);
        
        DcCache('auth_all', null);
        
        $this->success(lang('success'));
	}
	
	//修改一条规则到数据库
	public function update()
    {
        $data = input('post.');
        if(!$data['op_id']){
            $this->error(lang('mustIn'));
        }
        
        config('common.validate_name', 'common/Op');
        $info = \daicuo\Op::update_id($data['op_id'], $data);
        if(is_null($info)){
            $this->error(\daicuo\Op::getError());
        }
        
        DcCache('auth_all', null);
        
        $this->success(lang('success'));
	}
}