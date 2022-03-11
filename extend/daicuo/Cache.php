<?php
namespace daicuo;

class Cache 
{
    // 错误信息
    protected static $error = 'error';
    
    /**
     * 获取错误信息
     * @return mixed
     */
    public static function getError()
    {
        return self::$error;
    }
    
    /**
    * APP初始化时动态配置缓存类型
    * @param array $data 数据
    * @return array 配置数组
    */
    public static function appInt()
    {
        if( is_file('./datas/config/cache.php') ){
            \think\Config::load('./datas/config/cache.php','cache');
        }
    }
    
    /**
    * 验证缓存方式是否支持
    * @param array $option 缓存配置
    * @return bool
    */
    public static function check($type='File')
    {
        if($type == 'Sqlite3'){
			if(!class_exists('sqlite3')){
                return false;
			}
		}elseif($type == 'Memcache'){
			if(!class_exists('memcache')){
                return false;
			}
		}elseif($type == 'Memcached'){
			if(!class_exists('memcached')){
                return false;
			}
		}elseif($type == 'Redis'){
			if(!class_exists('redis')){
                return false;
			}
		}elseif($type == 'Wincache'){
			if(!class_exists('wincache_ucache_info')){
                return false;
			}
		}elseif($type == 'Xcache'){
			if(!class_exists('xcache_info')){
                return false;
			}
		}
        return true;
    }
    
    /**
    * 删除数据库保存的缓存配置
    * @param array $data 数据
    * @return array 数据集
    */
    public static function delete()
    {
         return \daicuo\Op::delete([
             'op_name' => 'site_cache'
         ]);
    }

    /**
    * 将缓存配置写入数据库
    * @param array $data 数据
    * @return array 数据集
    */
    public static function save($post=[])
    {
        $op = array();
        $op['op_name']     = 'site_cache';
        $op['op_value']    = $post;
        $op['op_module']   = 'common';
        $op['op_controll'] = 'cache';
        $op['op_action']   = 'system';
        $op['op_order']    = 0;
        $op['op_autoload'] = 'no';
        $op['op_status']   = 'normal';
        return \daicuo\Op::save($op);
    }
}