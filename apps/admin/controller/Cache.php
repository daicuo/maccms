<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Cache extends Admin
{
	//保存缓存配置
	public function update()
    {
        $options = array();
        $options['type']          = input('post.cache_type/s','File');
        $options['prefix']        = input('post.cache_prefix/s');
        $options['path']          = input('post.cache_path/s');
        $options['db']            = input('post.cache_db/s');
        $options['host']          = input('post.cache_host/s');
        $options['port']          = input('post.cache_port/s');
        $options['expire']        = input('post.cache_expire/d',0);
        $options['expire_detail'] = input('post.cache_expire_detail/d','');
        $options['expire_item']   = input('post.cache_expire_item/d','');
        //验证缓存服务是否支持
        if( \think\Cache::connect($options) ){
            //将配置写入数据库
            if( write_array('./datas/config/cache.php',$options) ){
                $this->success(lang('success'));
            }else{
                $this->error(lang('cache_error_write'));
            }
            //返回结果
            $this->success(lang('success'));
        }
         //连接失败
        $this->error(lang('unSupport').$option['type']);
	}
    
    //缓存配置
    public function index()
    {
        $this->assign('fields', DcFormItems(model('admin/Cache','loglic')->fields()));
        
		return $this->fetch();
    }
}