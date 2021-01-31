<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class Upload extends Admin
{
    //保存配置
	public function update()
    {
        $result = \daicuo\Op::write([
            'upload_path'       =>input('post.upload_path/s'),
            'upload_save_rule'  =>input('post.upload_save_rule/s','date'),
            'upload_max_size'   =>input('post.upload_max_size/s'),
            'upload_file_ext'   =>input('post.upload_file_ext/s'),
            'upload_mime_type'  =>input('post.upload_mime_type/s'),
            'upload_referer'    =>input('post.upload_referer/s'),
            'upload_host'       =>input('post.upload_host/s'),
            'upload_cdn'        =>input('post.upload_cdn/s'),
        ],'common','','','0','yes');
		if( !$result ){
		    $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}
    
    //管理配置
    public function index()
    {
        $this->assign('query', $this->query);
        return $this->fetch();
    }
    
}