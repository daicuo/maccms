<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Navs extends Admin
{
    //定义表单字段列表
    protected function fields($data=[])
    {
        return model('admin/Navs','loglic')->fields($data);
    }
    
    //定义表单初始数据
    protected function formData()
    {
        if( $id = input('id/d',0) ){
            return model('common/Navs','loglic')->getId($id, false);
		}
        return [];
    }

    //定义表格数据（JSON）
    protected function ajaxJson()
    {
        //查询参数
        $args = array();
        $args['cache']    = false;
        $args['result']   = 'level';
        $args['controll'] = 'navs';
        $args['limit']    = 0;
        $args['page']     = 0;
        $args['sort']     = 'term_parent asc,term_order';//
        $args['order']    = 'desc';
        $args['status']   = $this->query['navs_status'];
        $args['module']   = $this->query['navs_module'];
        //$args['action']   = $this->query['navs_action'];
        $args['parent']   = $this->query['navs_parent'];
        $args['search']   = $this->query['searchText'];
        //动态扩展字段
        $args['meta_query'] = DcMetaQuery(model('common/Term','loglic')->metaList($args['module'],$args['controll']), $this->query);
        //搜索关键字只能返回数组
        if($args['search']){
            $args['result']  = 'array';
        }
        //数据查询
        $list = model('common/Term','loglic')->select(DcArrayEmpty($args));
        if(is_null($list)){
            return [];
        }
        //数据获取器
        foreach($list as $key=>$value){
             $list[$key] = model('common/Navs','loglic')->dataGet($value);
        }
        return $list;
    }
    
	//添加(数据库)
	public function save()
    {
        $post = model('common/Navs','loglic')->dataSet(input('post.'));
        
        $term_id = model('common/Navs','loglic')->write($post);
        
		if($term_id < 1){
			$this->error(\daicuo\Term::getError());
		}
        
		$this->success(lang('success'));
	}
    
    //删除(数据库)
	public function delete()
    {
		if(!$ids = input('id/a')){
			$this->error(lang('mustIn'));
		}
        
        model('common/Term','loglic')->deleteIds($ids);
        
        $this->success(lang('success'));
	}
	
	//修改（数据库）
	public function update()
    {
		$data = model('common/Navs','loglic')->dataSet(input('post.'));
        if(!$data['term_id']){
            $this->error(lang('mustIn'));
        }
        
        $info = model('common/Navs','loglic')->write($data);
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
        //
        $result = model('common/Term','loglic')->status($ids,input('request.value/s', 'hidden'));
        //
        $this->success(lang('success'));
    }
    
    public function preview()
    {
        if(!$info= model('common/Navs','loglic')->getId(input('id/d',0), false)){
            $this->error(lang('empty'));
        }
        //去掉后台入口文件
        $url = str_replace($this->request->baseFile(), '', $info['navs_link']);
        //跳转至前台
        $this->redirect($url,302);
    }
}