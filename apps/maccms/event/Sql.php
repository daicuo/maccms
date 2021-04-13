<?php
namespace app\maccms\event;

class Sql
{
    /**
    * 安装时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
	public function install()
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
            ['title'=>'2021免费电影','url'=>'http://www.maccms.co','target'=>'_blank','describe'=>'提供2021年上映的免费电影，即不收费就可观看和下载的电影。'],
            ['title'=>'2021最新电影','url'=>'http://www.maccms.co','target'=>'_blank','describe'=>'提供2021年高清电影，2021年最新电视剧，好看的免费高质量影片。'],
            ['title'=>'2020好看电影','url'=>'http://www.maccms.co','target'=>'_blank','describe'=>'提供2021已上映好看的电影排行榜、电视剧排行榜，动漫排行榜等。'],
        ];
        //广告
        $poster = [
            'footer_pc'=>'<a href="/maccms/cps/?type=douyin" target="_blank"><img class="img-fluid" src="https://cdn.daicuo.cc/images/cps/douyin97090.png" alt="doubi"></a>',
            'center_wap'=>'<a class="btn btn-block btn-info text-light" href="/maccms/cps/?type=douyin" target="_blank">抖音币充值，最低8折抢购中</a>',
        ];
        
        //写入插件配置
        $result = \daicuo\Op::write([
            'site_title'      => '2021最新免费电影－MacCms',
            'site_keywords'   => '2021最新电影,2021免费电影,2021好看电视剧',
            'site_description'=> '青苹果API影视系统提供2020年最新好看的免费电影与电视剧。',
            'theme'           => 'default_pc',
            'theme_wap'       => 'default_wap',
            'page_size'       => 30,
            'api_search'      => 't2s',
            'api_url'         => 'http://cdn.feifeicms.co/api/hao124/index.php',
            'api_params'      => '',
            'slide_index'     => json_encode($slide_index),
            'slide_index_m'   => json_encode($slide_index),
            'link_index'      => json_encode($link_index),
            'link_footer'     => json_encode($link_footer),
            'footer_pc'       => $poster['footer_pc'],
            'center_wap'      => $poster['center_wap'],
        ], 'maccms', '', '', '0', 'yes');
        if(!$result){
            return false;
        }
        
        //批量添加钩子
        $result = \daicuo\Hook::save_all([
            [
                'hook_name'=>'form_build',
                'hook_path'=>'app\maccms\behavior\Hook',
                'hook_overlay'=>'no',
                'op_module'=>'maccms',
            ],
            [
                'hook_name'=>'admin_index_header',
                'hook_path'=>'app\maccms\behavior\Hook',
                'hook_overlay'=>'yes',
                'op_module'=>'maccms',
            ],
        ]);
        if(!$result){
            return false;
        }
        
        //批量添加路由伪静态
        $result = \daicuo\Route::save_all([
            [
                'rule'        =>'/',
                'address'     =>'maccms/index/index',
                'method'      =>'get',
                'op_module'   =>'maccms',
            ],
            [
                'rule'        =>'dianying$',
                'address'     =>'maccms/category/dianying',
                'method'      =>'get',
                'op_module'   =>'maccms',
            ],
            [
                'rule'        =>'dianshiju$',
                'address'     =>'maccms/category/dianshiju',
                'method'      =>'get',
                'op_module'   =>'maccms',
            ],
            [
                'rule'        =>'dongman$',
                'address'     =>'maccms/category/dongman',
                'method'      =>'get',
                'op_module'   =>'maccms',
            ],
            [
                'rule'        =>'zongyi$',
                'address'     =>'maccms/category/zongyi',
                'method'      =>'get',
                'op_module'   =>'maccms',
            ],
        ]);
        
        //批量添加导航
        $result = \daicuo\Nav::save_all([
            [
                'nav_text'        =>'电影',
                'nav_type'        =>'addon',
                'nav_module'      =>'maccms',
                'nav_controll'    =>'category',
                'nav_action'      =>'dianying',
                'nav_params'      =>'',
                'nav_target'      =>'_self',
                'nav_ico'         =>'fa fa-fw fa-list',
                'nav_active'      =>'categorydianying',
                'op_module'       =>'maccms',
                'op_controll'     =>'header',
                'op_action'       =>'',
            ],
            [
                'nav_text'        =>'电视剧',
                'nav_type'        =>'addon',
                'nav_module'      =>'maccms',
                'nav_controll'    =>'category',
                'nav_action'      =>'dianshiju',
                'nav_params'      =>'',
                'nav_target'      =>'_self',
                'nav_ico'         =>'fa fa-fw fa-list',
                'nav_active'      =>'categorydianshiju',
                'op_module'       =>'maccms',
                'op_controll'     =>'header',
                'op_action'       =>'',
            ],
            [
                'nav_text'        =>'动漫',
                'nav_type'        =>'addon',
                'nav_module'      =>'maccms',
                'nav_controll'    =>'category',
                'nav_action'      =>'dongman',
                'nav_params'      =>'',
                'nav_target'      =>'_self',
                'nav_ico'         =>'fa fa-fw fa-list',
                'nav_active'      =>'categorydongman',
                'op_module'       =>'maccms',
                'op_controll'     =>'header',
                'op_action'       =>'',
            ],
            [
                'nav_text'        =>'综艺',
                'nav_type'        =>'addon',
                'nav_module'      =>'maccms',
                'nav_controll'    =>'category',
                'nav_action'      =>'zongyi',
                'nav_params'      =>'',
                'nav_target'      =>'_self',
                'nav_ico'         =>'fa fa-fw fa-list',
                'nav_active'      =>'categoryzongyi',
                'op_module'       =>'maccms',
                'op_controll'     =>'header',
                'op_action'       =>'',
            ],
        ]);
        
        //扩展分类表字段
        DcConfigMerge('custom_fields.term_meta', [
            'term_api_url',
            'term_api_params',
            'term_api_tid',
            'term_api_type'
        ]);
        
        //批量添加分类
        $result = \daicuo\Term::save_all([
            [
                'term_name'       => '电影',
                'term_module'     => 'maccms',
                'term_slug'       => 'dianying',
                'term_order'      => 8,
                'term_much_type'  => 'category',
                'term_much_info'  => '电影的说明',
                'term_tpl'        => 'index',
                'term_api_url'    => 'http://cdn.feifeicms.co/api/hao124/index.php',
                'term_api_params' => '',
                'term_api_tid'    => 1,
                'term_api_type'   => 'Feifeicms',
            ],
            [
                'term_name'       => '电视剧',
                'term_module'     => 'maccms',
                'term_slug'       => 'dianshiju',
                'term_order'      => 9,
                'term_much_type'  => 'category',
                'term_much_info'  => '电视剧的说明',
                'term_tpl'        => 'index',
                'term_api_url'    => 'http://cdn.feifeicms.co/api/hao124/index.php',
                'term_api_params' => '',
                'term_api_tid'    => 2,
                'term_api_type'   => 'Feifeicms',
            ],
            [
                'term_name'       => '动漫',
                'term_module'     => 'maccms',
                'term_slug'       => 'dongman',
                'term_order'      => 7,
                'term_much_type'  => 'category',
                'term_much_info'  => '电视剧的说明',
                'term_tpl'        => 'index',
                'term_api_url'    => 'http://cdn.feifeicms.co/api/hao124/index.php',
                'term_api_params' => '',
                'term_api_tid'    => 3,
                'term_api_type'   => 'Feifeicms',
            ],
            [
                'term_name'       => '综艺',
                'term_module'     => 'maccms',
                'term_slug'       => 'zongyi',
                'term_order'      => 6,
                'term_much_type'  => 'category',
                'term_much_info'  => '电视剧的说明',
                'term_tpl'        => 'index',
                'term_api_url'    => 'http://cdn.feifeicms.co/api/hao124/index.php',
                'term_api_params' => '',
                'term_api_tid'    => 4,
                'term_api_type'   => 'Feifeicms',
            ],
        ]);
        
        //清空缓存
        \think\Cache::clear();
        
        return true;
	}
    
    /**
    * 升级时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
    public function upgrade()
    {
        //更新应用配置信息
        $event = controller('common/Apply','event');
        $result = $event->updateStatus('maccms', 'enable');
        if(!$result){
            return false;
        }
        
        //更新应用打包配置
        $result = \daicuo\Op::write([
            'apply_version'   => '1.3.2',
        ]);
        if(!$result){
            return false;
        }
        
        return true;
	}
    
    /**
    * 卸载时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
    public function unInstall()
    {
        //删除插件配置
        $status = \daicuo\Op::delete_module('maccms');
        //删除插件分类
        \daicuo\Term::delete_module('maccms');
        return true;
	}
	
}