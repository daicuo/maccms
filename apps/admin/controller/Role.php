<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Role extends Admin
{
    //定义表单字段列表
    protected function fields($data=[])
    {
        return model('admin/Role','loglic')->fields($data);
    }
    
    //定义表单初始数据
    protected function formData()
    {
        if( $id = input('id/d',0) ){
            return model('common/Config','loglic')->getId($id,false);
		}
        return [];
    }

    //定义表格数据（JSON）
    protected function ajaxJson()
    {
        //查询参数
        $args = array();
        $args['cache']     = false;
        $args['controll']  = 'role';
        $args['field']     = 'op_id,op_name,op_value,op_module,op_controll,op_action,op_order,op_status';
        $args['sort']      = DcEmpty($this->query['sortName'],'op_id');
        $args['order']     = DcEmpty($this->query['sortOrder'],'desc');
        $args['search']    = $this->query['searchText'];
        $args['name']      = $this->query['op_name'];
        $args['status']    = $this->query['op_status'];
        $args['module']    = $this->query['op_module'];
        //格式化数据
        $list = model('config/Role','loglic')->select(DcArrayEmpty($args));
        /*foreach($list as $key=>$value){
            $list[$key]['op_value']  = implode('<br/>',$this->valueGet($value['op_value']));
        }*/
        //数据返回
        return DcEmpty($list,[]);
    }
    
    //保存用户组
	public function save()
    {
		if( !$op_id = model('common/Role','loglic')->write(input('post.')) ){
			$this->error(\daicuo\Op::getError());
		}
		$this->success(lang('success'));
	}
    
	//快速删除数据
    public function delete()
    {
        $ids = input('id/a');
		if(!$ids){
			$this->error(lang('mustIn'));
		}
        
        model('common/Config','loglic')->deleteIds($ids);
        
        $this->success(lang('success'));
	}
	
	//修改一条规则到数据库
	public function update()
    {
        $data = input('post.');
        if(!$data['op_id']){
            $this->error(lang('mustIn'));
        }
        
        $info = model('common/Role','loglic')->write($data);
        if(is_null($info)){
            $this->error(\daicuo\Op::getError());
        }
        $this->success(lang('success'));
	}
}