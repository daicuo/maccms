<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Upload extends Admin
{
    //保存配置
	public function update()
    {
        $result = \daicuo\Op::write(input('post.'),'common','config','system','0','yes');
		if( !$result ){
		    $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}
    
    //管理配置
    public function index()
    {
        $this->assign('fields', DcFormItems(model('admin/Upload','loglic')->fields()));
        
        $this->assign('query', $this->query);
        
        return $this->fetch();
    }
    
}