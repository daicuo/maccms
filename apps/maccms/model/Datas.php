<?php
namespace app\maccms\model;

class Datas
{
    //批量写入插件初始配置
    public function insertConfig()
    {
        //首页轮播
        $slide_index = [
            ['title'=>'MacCms','image'=>'https://cdn.daicuo.cc/images/slide/01.jpg','url'=>'http://www.maccms.co','describe'=>''],
            ['title'=>'MacCms','image'=>'https://cdn.daicuo.cc/images/slide/02.jpg','url'=>'http://www.maccms.co','describe'=>''],
            ['title'=>'MacCms','image'=>'https://cdn.daicuo.cc/images/slide/03.jpg','url'=>'http://www.maccms.co','describe'=>''],
            ['title'=>'MacCms','image'=>'https://cdn.daicuo.cc/images/slide/04.jpg','url'=>'http://www.maccms.co','describe'=>''],
            ['title'=>'MacCms','image'=>'https://cdn.daicuo.cc/images/slide/05.jpg','url'=>'http://www.maccms.co','describe'=>''],
        ];
        //友情链接
        $link_index = [
            ['title'=>'MacCms','url'=>'https://www.maccms.co','target'=>'_blank','describe'=>''],
            ['title'=>'DaiCuo','url'=>'http://www.daicuo.net','target'=>'_blank','describe'=>''],
        ];
        //底部链接
        $link_footer = [
            ['title'=>'2022免费电影','url'=>'http://www.maccms.co','target'=>'_blank','describe'=>'提供2022年上映的免费电影，即不收费就可观看和下载的电影。'],
            ['title'=>'2022最新电影','url'=>'http://www.maccms.co','target'=>'_blank','describe'=>'提供2022年高清电影，2022年最新电视剧，好看的免费高质量影片。'],
            ['title'=>'2022好看电影','url'=>'http://www.maccms.co','target'=>'_blank','describe'=>'提供2022已上映好看的电影排行榜、电视剧排行榜，动漫排行榜等。'],
        ];
        //广告
        $poster = [
            'footer_pc'=>'<a href="https://daohang.daicuo.cc" target="_blank"><img class="img-fluid w-100" src="http://cdn.daicuo.cc/images/banner/dh960.90.jpg" alt="呆错网址导航系统"></a>',
            'center_wap'=>'<a class="btn btn-block btn-info text-light" href="/maccms/cps/?type=douyin" target="_blank">抖音币充值，最低8折抢购中</a>',
        ];
        return model('common/Config','loglic')->install([
            'api_search'      => 't2s',
            'api_url'         => 'http://cdn.feifeicms.co/api/hao124/index.php',
            'api_type'        => 'feifeicms',
            'api_params'      => '',
            'page_size'       => 30,
            'theme'           => 'default_pc',
            'theme_wap'       => 'default_wap',
            'site_title'      => '2022最新免费电影',
            'site_keywords'   => '2022最新电影,2022免费电影,2022好看电视剧',
            'site_description'=> '青苹果API影视系统提供2022年最新好看的免费电影与电视剧。',
            'limit_index'     => 16,
            'limit_categorys' => 6,
            'slide_index'     => json_encode($slide_index),
            'slide_index_m'   => json_encode($slide_index),
            'link_index'      => json_encode($link_index),
            'link_footer'     => json_encode($link_footer),
            'footer_pc'       => $poster['footer_pc'],
            'center_wap'      => $poster['center_wap'],
            'rewrite_index'   => 'maccms$',
            'rewrite_play'    => 'video/:termSlug/<id>-<ep>-<from>',
            'rewrite_category'=> 'video/:slug/[:pageNumber]',
        ], 'maccms');
    }
    
    //批量添加路由伪静态
    public function insertRoute()
    {
        config('common.validate_name', false);
        
        return model('common/Route','loglic')->install([
            [
                'rule'        =>'maccms$',
                'address'     =>'maccms/index/index',
                'method'      =>'get',
            ],
            [
                'rule'        =>'video/:termSlug/<id>-<ep>-<from>',
                'address'     =>'maccms/play/index',
                'method'      =>'get',
            ],
            [
                'rule'        =>'video/:slug/[:pageNumber]',
                'address'     =>'maccms/category/index',
                'method'      =>'get',
            ],
        ],'maccms');
    }
    
    //批量写入插件动态字段
    public function insertField()
    {
        return model('common/Field','loglic')->install([
            [
                'op_name'     => 'term_limit',
                'op_value'    => json_encode([
                    'type'         => 'text',
                    'relation'     => 'eq',
                    'data-visible' => false,
                    'data-filter'  => false,
                ]),
                'op_module'   => 'maccms',
                'op_controll' => 'category',
                'op_action'   => 'index',
            ],
            [
                'op_name'     => 'term_api_tid',
                'op_value'    => json_encode([
                    'type'         => 'text',
                    'relation'     => 'eq',
                    'data-visible' => false,
                    'data-filter'  => false,
                ]),
                'op_module'   => 'maccms',
                'op_controll' => 'category',
                'op_action'   => 'index',
            ]
        ]);
    }
    
    //批量添加后台菜单
    public function insertMenu()
    {
        $result = model('common/Menu','loglic')->install([
            [
                'term_name'   => '影视',
                'term_slug'   => 'maccms',
                'term_info'   => 'fa-apple',
                'term_module' => 'maccms',
            ],
        ]);
        
        $result = model('common/Menu','loglic')->install([
            [
                'term_name'   => '接口配置',
                'term_slug'   => 'maccms/config/index',
                'term_info'   => 'fa-gear',
                'term_module' => 'maccms',
                'term_order'  => 10,
            ],
            [
                'term_name'   => '频道设置',
                'term_slug'   => 'maccms/admin/index',
                'term_info'   => 'fa-gear',
                'term_module' => 'maccms',
                'term_order'  => 9,
            ],
            [
                'term_name'   => '分类管理',
                'term_slug'   => 'admin/category/index?parent=maccms&term_module=maccms',
                'term_info'   => 'fa-list',
                'term_module' => 'maccms',
                'term_order'  => 8,
            ],
            [
                'term_name'   => '微信配置',
                'term_slug'   => 'maccms/weixin/index',
                'term_info'   => 'fa-weixin',
                'term_module' => 'maccms',
                'term_order'  => 7,
            ],
            [
                'term_name'   => '轮播配置',
                'term_slug'   => 'maccms/slide/index',
                'term_info'   => 'fa-image',
                'term_module' => 'maccms',
                'term_order'  => 6,
            ],
            [
                'term_name'   => '网站广告',
                'term_slug'   => 'maccms/adsense/index',
                'term_info'   => 'fa-desktop',
                'term_module' => 'maccms',
                'term_order'  => 5,
            ],
            [
                'term_name'   => '手机广告',
                'term_slug'   => 'maccms/adsense/wap',
                'term_info'   => 'fa-btc',
                'term_module' => 'maccms',
                'term_order'  => 4,
            ],
            [
                'term_name'   => '友情链接',
                'term_slug'   => 'maccms/link/index',
                'term_info'   => 'fa-link',
                'term_module' => 'maccms',
                'term_order'  => 3,
            ],
            [
                'term_name'   => '同步升级',
                'term_slug'   => 'maccms/config/upgrade',
                'term_info'   => 'fa-arrow-circle-o-up',
                'term_module' => 'maccms',
                'term_order'  => 1,
            ],
            [
                'term_name'   => '字段管理',
                'term_slug'   => 'admin/field/index?parent=maccms&op_module=maccms',
                'term_info'   => 'fa-cube',
                'term_module' => 'maccms',
                'term_order'  => 0,
            ],
        ],'影视');
    }
    
    //批量添加分类
    public function insertCategory()
    {
        return model('common/Category','loglic')->install([
            [
                'term_name'       => '电影',
                'term_slug'       => 'dianying',
                'term_type'       => 'navbar',
                'term_module'     => 'maccms',
                'term_order'      => 9,
                'term_limit'      => 30,
                'term_api_tid'    => 1,
            ],
            [
                'term_name'       => '电视剧',
                'term_slug'       => 'dianshiju',
                'term_type'       => 'navbar',
                'term_module'     => 'maccms',
                'term_order'      => 8,
                'term_limit'      => 30,
                'term_api_tid'    => 2,
            ],
            [
                'term_name'       => '动漫',
                'term_slug'       => 'dongman',
                'term_type'       => 'navbar',
                'term_module'     => 'maccms',
                'term_order'      => 7,
                'term_limit'      => 30,
                'term_api_tid'    => 3,
            ],
            [
                'term_name'       => '综艺',
                'term_slug'       => 'zongyi',
                'term_type'       => 'navbar',
                'term_module'     => 'maccms',
                'term_order'      => 6,
                'term_limit'      => 30,
                'term_api_tid'    => 4,
            ],
        ]);
    }
}