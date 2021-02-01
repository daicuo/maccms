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
            ['title'=>'MacCms','image'=>'http://kanp0.123.sogoucdn.com/imgu/2021/01/20210122183151_464.jpg','url'=>'http://www.maccms.co','describe'=>''],
            ['title'=>'MacCms','image'=>'http://kanp0.123.sogoucdn.com/imgu/2021/01/20210112170802_817.jpg','url'=>'http://www.maccms.co','describe'=>''],
            ['title'=>'MacCms','image'=>'http://kanp6.123.sogoucdn.com/imgu/2021/01/20210108165106_509.jpg','url'=>'http://www.maccms.co','describe'=>''],
        ];
        //友情链接
        $link_index = [
            ['title'=>'DaiCuo','url'=>'http://www.daicuo.net','target'=>'_blank','describe'=>''],
            ['title'=>'MacCms','url'=>'http://www.maccms.co','target'=>'_blank','describe'=>''],
        ];
        //底部链接
        $link_footer = [
            ['title'=>'2021免费电影','url'=>'http://www.maccms.co','target'=>'_blank','describe'=>'提供2021年上映的免费电影，即不收费就可观看和下载的电影。'],
            ['title'=>'2021最新电影','url'=>'http://www.maccms.co','target'=>'_blank','describe'=>'提供2021年高清电影，2021年最新电视剧，好看的免费高质量影片。'],
            ['title'=>'2020好看电影','url'=>'http://www.maccms.co','target'=>'_blank','describe'=>'提供2021已上映好看的电影排行榜、电视剧排行榜，动漫排行榜等。'],
        ];
        //写入插件配置
        $op_value = [
            'site_title'      => '2021最新免费电影－MacCms',
            'site_keywords'   => '2021最新电影,2021免费电影,2021好看电视剧',
            'site_description'=> '青苹果API影视系统提供2020年最新好看的免费电影与电视剧。',
            'theme'           => 'default_pc',
            'theme_wap'       => 'default_wap',
            'page_size'       => 30,
            'slide_index'     => json_encode($slide_index),
            'slide_index_m'   => json_encode($slide_index),
            'link_index'      => json_encode($link_index),
            'link_footer'     => json_encode($link_footer),
        ];
        $result = \daicuo\Op::write($op_value, 'maccms', '', '', '0', 'yes');
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
        ]);
        if(!$result){
            return false;
        }
        return true;
	}
    
    /**
    * 升级时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
    public function upgrade()
    {
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
        return true;
	}
	
}