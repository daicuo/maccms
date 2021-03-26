<?php
\think\Lang::load(APP_PATH.'admin/lang/'.config('default_lang').'.php');
// 应用配置
return [
    //'controller_auto_search' => true,
    
	'url_common_param' => true,
    
    'url_html_suffix'  => '',

    'user_roles'       => array_merge(config('user_roles'),[
        'editor' => [
            'admin/index/index',
            'admin/index/logout',
            'admin/version/index',
            'admin/op/index',
            'admin/video/index',
            'admin/tool/index',
            'admin/route/index',
            'admin/hook/index',
            'admin/nav/index',
            'admin/category/index',
            'admin/tag/index',
            'admin/user/index',
            'admin/apply/index',
            'admin/apply/create',
            'admin/store/index',
            'admin/addon/index?action=index',
        ]
    ]),
    
    'admin_menu' => [
        //插件
        'addon' => [],
        //顶部
        'top' => [
            [
                'ico'     => 'fa fa-fw fa-gear',
                'title'   => lang('admin_home'),
                'controll'=> 'index',
                'action'  => 'index',
                'url'     => DcUrl('admin/index/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-home',
                'title'   => lang('front_home'),
                'controll'=> 'index',
                'action'  => 'index',
                'target'  => '_blank',
                'url'     => '../../',
            ],
            [
                'ico'     => 'fa fa-fw fa-sign-out',
                'title'   => lang('admin_logout'),
                'controll'=> 'index',
                'action'  => 'index',
                'url'     => DcUrl('admin/index/logout','',''),
            ],
        ],
        //配置
        'config' => [
            [
                'ico'     => 'fa fa-fw fa-gear',
                'title'   => lang('op_index'),
                'controll'=> 'op',
                'action'  => 'index',
                'url'     => DcUrl('admin/op/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-folder',
                'title'   => lang('cache_index'),
                'controll'=> 'cache',
                'action'  => 'index',
                'url'     => DcUrl('admin/cache/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-file-movie-o',
                'title'   => lang('video_index'),
                'controll'=> 'video',
                'action'  => 'index',
                'url'     => DcUrl('admin/video/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-upload',
                'title'   => lang('upload_config'),
                'controll'=> 'upload',
                'action'  => 'index',
                'url'     => DcUrl('admin/upload/index','',''),
            ],
        ],
        //系统
        'system' => [
            [
                'ico'     => 'fa fa-fw fa-wrench',
                'title'   => lang('tool_index'),
                'controll'=> 'tool',
                'action'  => 'index',
                'url'     => DcUrl('admin/tool/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-cogs',
                'title'   => lang('route_index'),
                'controll'=> 'route',
                'action'  => 'index',
                'url'     => DcUrl('admin/route/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-anchor',
                'title'   => lang('hook_index'),
                'controll'=> 'hook',
                'action'  => 'index',
                'url'     => DcUrl('admin/hook/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-navicon',
                'title'   => lang('nav_index'),
                'controll'=> 'nav',
                'action'  => 'index',
                'url'     => DcUrl('admin/nav/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-leaf',
                'title'   => lang('category_index'),
                'controll'=> 'category',
                'action'  => 'index',
                'url'     => DcUrl('admin/category/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-tag',
                'title'   => lang('tag_index'),
                'controll'=> 'tag',
                'action'  => 'index',
                'url'     => DcUrl('admin/tag/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-user',
                'title'   => lang('user_index'),
                'controll'=> 'user',
                'action'  => 'index',
                'url'     => DcUrl('admin/user/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-clone',
                'title'   => lang('index_index'),
                'controll'=> 'index',
                'action'  => 'index',
                'url'     => DcUrl('admin/index/index','',''),
            ],
        ],
        //应用
        'apply' => [
            [
                'ico'     => 'fa fa-fw fa-cloud',
                'title'   => lang('apply_store'),
                'controll'=> 'store',
                'action'  => 'index',
                'url'     => DcUrl('admin/store/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-archive',
                'title'   => lang('apply_index'),
                'controll'=> 'apply',
                'action'  => 'index',
                'url'     => DcUrl('admin/apply/index','',''),
            ],
            [
                'ico'     => 'fa fa-fw fa-gear',
                'title'   => lang('apply_create'),
                'controll'=> 'apply',
                'action'  => 'create',
                'url'     => DcUrl('admin/apply/create','',''),
            ],
        ],
    ]
];