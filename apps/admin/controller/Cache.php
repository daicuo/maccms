<?php
//系统基础配置
namespace app\admin\controller;

use app\common\controller\Admin;

class Cache extends Admin
{
	//保存缓存配置
	public function update()
    {
        $config = array();
        $config['type'] = input('post.cache_type/s','File');
        $config['prefix'] = input('post.cache_prefix/s');
        $config['path'] = input('post.cache_path/s');
        $config['db'] = input('post.cache_db/s');
        $config['host'] = input('post.cache_host/s');
        $config['port'] = input('post.cache_port/s');
        $config['expire'] = input('post.cache_expire/d',0);
        $config['expire_detail'] = input('post.cache_expire_detail/d','');
        $config['expire_item'] = input('post.cache_expire_item/d','');
        //
		if($config['type'] == 'Sqlite3'){
			if(!class_exists('sqlite3')){
				$this->error(lang('unSupport').$config['type']);
			}
		}elseif($config['type'] == 'Memcache'){
			if(!class_exists('memcache')){
				$this->error(lang('unSupport').$config['type']);
			}
		}elseif($config['type'] == 'Memcached'){
			if(!class_exists('memcached')){
				$this->error(lang('unSupport').$config['type']);
			}
		}elseif($config['type'] == 'Redis'){
			if(!class_exists('redis')){
				$this->error(lang('unSupport').$config['type']);
			}
		}elseif($config['type'] == 'Wincache'){
			if(!class_exists('wincache_ucache_info')){
				$this->error(lang('unSupport').$config['type']);
			}
		}elseif($config['type'] == 'Xcache'){
			if(!class_exists('xcache_info')){
				$this->error(lang('unSupport').$config['type']);
			}
		}
		write_arr2file('./datas/config/cache.php',['cache'=>$config]);
		$this->success(lang('success'));
	}
}