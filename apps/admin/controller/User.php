<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class User extends Admin
{
    //定义表单字段列表
    protected function fields($data=[])
    {
        return model('admin/User','loglic')->fields($data);
    }
    
    //定义表单初始数据
    protected function formData()
    {
        if( $id = input('id/d',0) ){
            $data = model('common/User','loglic')->getId($id, false);
            unset($data['user_pass']);
            return $data;
		}
        return [];
    }

    //定义表格数据（JSON）
    //user/index/?pageNumber=1&pageSize=20&sortName=user_id&sortOrder=desc&user_capabilities=admin
    protected function ajaxJson()
    {
        $args = array();
        $args['cache']  = false;
        $args['limit']  = DcEmpty($this->query['pageSize'],50);
        $args['page']   = DcEmpty($this->query['pageNumber'],1);
        $args['sort']   = DcEmpty($this->query['sortName'],'user_id');
        $args['order']  = DcEmpty($this->query['sortOrder'],'desc');
        $args['search'] = $this->query['searchText'];
        //初始字段条件
        $args['where']  = DcWhereFilter($this->query, ['user_token','user_status','user_create_ip','user_update_ip'], 'eq');
        //扩展字段查询条件
        $args['meta_query'] = DcMetaQuery(model('common/User','loglic')->metaList(), $this->query);
        //自定义字段排序
        if( !in_array($args['sort'],model('common/Attr','loglic')->userSort()) ){
            $args['meta_key'] = $args['sort'];
            $args['sort']     = 'meta_value_num';
        }
        //查询数据
        $list = model('common/User','loglic')->select(DcArrayEmpty($args));
        //返回数据
        return DcEmpty($list,['total'=>0,'data'=>[]]);
    }
    
    //添加(数据库)
	public function save()
    {
        $user_id = model('common/User','loglic')->write(input('post.'), 'common/User', 'auto', false);
		if($user_id < 1){
			$this->error(\daicuo\User::getError());
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
        
        foreach($ids as $user_id){
            \daicuo\User::delete_user_by('user_id', $user_id);
        }
        
        $this->success(lang('success'));
	}
	
	//修改（数据库）
	public function update()
    {  
		$data = input('post.');
        if(!$data['user_id']){
            $this->error(lang('mustIn'));
        }
        
        $info = model('common/User','loglic')->write($data, 'common/User', 'empty', false);
        if(is_null($info)){
            $this->error(\daicuo\User::getError());
        }
        $this->success(lang('success'));
	}
    
    //快速修改状态
    public function status()
    {
        if( !$ids = input('post.id/a') ){
            $this->error(lang('errorIds'));
        }
        
        $result = model('common/User','loglic')->status($ids,input('request.value/s', 'hidden'));

        $this->success(lang('success'));
    }
}