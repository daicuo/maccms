{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("cache_index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("cache_index")}
    <small class="text-muted">{:lang("cache_tips")}</small>
</h6>
{:DcBuildForm([
    'name'     => 'cache_index',
    'class'    => 'bg-white py-2 px-2',
    'action'   => DcUrl('admin/cache/update', '', ''),
    'method'   => 'post',
    'disabled' => false,
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'ajax'     => true,
    'callback' => '',
    'items'    => [
        [
            'type'=>'radio',
            'name'=>'cache_type',
            'id'=>'cache_type',
            'title'=>lang('cache_type'),
            'tips'=>'',
            'value'=>config('cache.type'),
            'option'=>['File'=>lang('cache_type_option_0'),'Sqlite3'=>lang('cache_type_option_1'),'Memcache'=>lang('cache_type_option_2'),'Memcached'=>lang('cache_type_option_3'),'Redis'=>lang('cache_type_option_4'),'Wincache'=>lang('cache_type_option_5'),'Xcache'=>lang('cache_type_option_6')],
            'readonly'=>false,
            'disabled'=>false,
            'required'=>true,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-auto',
            'class_right_control'=>'',
            'class_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'cache_prefix',
            'id'=>'cache_prefix',
            'title'=>lang('cache_prefix'),
            'placeholder'=>lang('cache_prefix_placeholder'),
            'tips'=>'',
            'value'=>config('cache.prefix'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'cache_path',
            'id'=>'cache_path',
            'title'=>lang('cache_path'),
            'placeholder'=>lang('cache_path_placeholder'),
            'tips'=>'',
            'value'=>DcEmpty(config('cache.path'),'datas/cache/'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'cache_db',
            'id'=>'cache_db',
            'title'=>lang('cache_db'),
            'placeholder'=>lang('cache_db_placeholder'),
            'tips'=>'',
            'value'=>DcEmpty(config('cache.db'),'datas/db/#cache.s3db'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'cache_host',
            'id'=>'cache_host',
            'title'=>lang('cache_host'),
            'placeholder'=>lang('cache_host_placeholder'),
            'tips'=>'',
            'value'=>DcEmpty(config('cache.host'),'127.0.0.1'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_tips'=>'',
        ],		
        [
            'type'=>'text',
            'name'=>'cache_port',
            'id'=>'cache_port',
            'title'=>lang('cache_port'),
            'placeholder'=>lang('cache_port_placeholder'),
            'tips'=>'',
            'value'=>DcEmpty(config('cache.port'),'6379'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'cache_expire',
            'id'=>'cache_expire',
            'title'=>lang('cache_expire'),
            'placeholder'=>lang('cache_expire_placeholder'),
            'tips'=>'',
            'value'=>config('cache.expire'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'cache_expire_detail',
            'id'=>'cache_expire_detail',
            'title'=>lang('cache_expire_detail'),
            'placeholder'=>lang('cache_expire_detail_placeholder'),
            'tips'=>'',
            'value'=>config('cache.expire_detail'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'cache_expire_item',
            'id'=>'cache_expire_item',
            'title'=>lang('cache_expire_item'),
            'placeholder'=>lang('cache_expire_item_placeholder'),
            'tips'=>'',
            'value'=>config('cache.expire_item'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_tips'=>'',
        ],
    ]
])}
{/block}
<!-- -->
{block name="js"}
{/block}