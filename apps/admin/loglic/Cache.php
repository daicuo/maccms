<?php
namespace app\admin\loglic;

class Cache
{
    public function fields()
    {
        return [
            'cache_type' => [
                'type'   => 'radio',
                'value'  => config('cache.type'),
                'option' => [
                    'File'      => 'File',
                    'Sqlite3'   => 'Sqlite3',
                    'Memcache'  => 'Memcache',
                    'Memcached' => 'Memcached',
                    'Redis'     => 'Redis',
                    'Wincache'  => 'Wincache',
                    'Xcache'    => 'Xcache',
                ],
                'required'      => true,
            ],
            'cache_prefix' => [
                'type'        => 'text',
                'value'       => config('cache.prefix'),
                'placeholder' => lang('cache_prefix_placeholder'),
                
            ],
            'cache_path' => [
                'type'        => 'text',
                'value'       => DcEmpty(config('cache.path'),'./datas/cache/'),
                'placeholder' => lang('cache_path_placeholder'),
            ],
            'cache_db' => [
                'type'        => 'text',
                'value'       => DcEmpty(config('cache.db'),'./datas/db/#cache.s3db'),
                'placeholder' => lang('cache_db_placeholder'),
            ],
            'cache_host' => [
                'type'        => 'text',
                'value'       => DcEmpty(config('cache.host'),'127.0.0.1'),
                'placeholder' => lang('cache_host_placeholder'),
            ],		
            'cache_port' => [
                'type'        => 'text',
                'value'       => DcEmpty(config('cache.port'),'6379'),
                'placeholder' => lang('cache_port_placeholder'),
            ],
            'cache_expire' => [
                'type'        => 'text',
                'value'       => config('cache.expire'),
                'placeholder' => lang('cache_expire_placeholder'),
            ],
            'cache_expire_detail' => [
                'type'        => 'text',
                'value'       => config('cache.expire_detail'),
                'placeholder' => lang('cache_expire_detail_placeholder'),
            ],
            'cache_expire_item' => [
                'type'        => 'text',
                'value'       => config('cache.expire_item'),
                'placeholder' => lang('cache_expire_item_placeholder'),
            ],
        ];
    }
}