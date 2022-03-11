<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Log extends Admin
{
    //定义表单字段列表
    protected function fields($data=[])
    {
        return model('common/Log','loglic')->fields($data);
    }
    
    //定义表单初始数据
    protected function formData()
    {
        if( $id = input('id/d',0) ){
            return model('common/Log','loglic')->get([
                'cache' => false,
                'where' => ['log_id'=>['eq',$id]],
            ]);
		}
        return [];
    }

    //定义表格数据（JSON）
    protected function ajaxJson()
    {
        //查询参数
        $args = array();
        $args['cache']    = false;
        $args['limit']    = input('pageSize/d', 50);
        $args['page']     = input('pageNumber/d', 1);
        $args['sort']     = input('sortName/s','log_id');
        $args['order']    = input('sortOrder/s','desc');
        $args['search']   = input('searchText/s','');
        if( $where = DcWhereQuery(['log_user_id','log_info_id','log_status','log_module','log_controll','log_action','log_type','log_ip'], 'eq', $this->query) ){
            $args['where'] = $where;
        }
        if( $this->query['searchText'] ){
            $args['where']['log_name|log_info'] = ['like','%'.DcHtml($this->query['searchText'].'%')];
        }
        //数据查询
        $list = model('common/Log','loglic')->all($args);
        //数据返回
        return DcEmpty($list,['total'=>0,'data'=>[]]);
    }
    
    //删除(数据库)
	public function delete()
    {
		$ids = input('id/a');
		if(!$ids){
			$this->error(lang('errorIds'));
		}
        //
        dbDelete('common/Log',['log_id'=>['in',$ids]]);
        $this->success(lang('success'));
	}
    
    //修改（数据库）
	public function update()
    {
		$data = input('post.');
        if(!$data['log_id']){
            $this->error(lang('errorIds'));
        }
        //
        $info = model('common/Log','loglic')->update(['log_id'=>['eq',$data['log_id']]], $data);
        if(is_null($info)){
            $this->error(model('common/Log','loglic')->getError());
        }
        $this->success(lang('success'));
	}
    
    //快速修改状态
    public function status()
    {
        if( !$ids = input('post.id/a') ){
            $this->error(lang('errorIds'));
        }
        //
        $data = [];
        $data['log_status'] = input('request.value/s', 'hidden');
        dbUpdate('common/Log',['log_id'=>['in',$ids]], $data);
        $this->success(lang('success'));
    }
}