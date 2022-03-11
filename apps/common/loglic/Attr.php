<?php
namespace app\common\loglic;

class Attr
{
    //状态属性
    public function statusOption()
    {
        return [
            'normal'  => lang('normal'),
            'hidden'  => lang('hidden'),
        ];
    }
    
    //状态属性
    public function status()
    {
        return [
            'normal'  => lang('normal'),
            'hidden'  => lang('hidden'),
            'private' => lang('private'),
            'public'  => lang('public')
        ];
    }
    
    //打开方式
    public function target()
    {
        return [
            '_self'   => lang('target_option_1'),
            '_blank'  => lang('target_option_0'),
            '_parent' => lang('target_option_2'),
            '_top'    => lang('target_option_3'),
            '_new'    => lang('target_option_4')
        ];
    }
    
    //分类属性
    public function categoryType()
    {
        return [
            'common'  => ' ',
            'navbar'  => lang('term_navbar'),
        ];
    }
    
    //导航属性
    public function navsType()
    {
        return [
            'navbar'  => lang('navbar'),
            'sitebar' => lang('sitebar'),
            'nav'     => lang('nav'),
            'link'    => lang('link'),
            'ico'     => lang('ico'),
            'image'   => lang('image'),
            'other'   => lang('other'),
        ];
    }
    
    //排序字段term表
    public function termSort()
    {
        return [
            'term_id',
            'term_parent',
            'term_order',
            'term_count',
            'term_name',
            'term_slug',
        ];
    }
    
    //排序字段info表
    public function infoSort()
    {
        return [
            'info_id',
            'info_order',
            'info_views',
            'info_hits',
            'info_parent',
            'info_slug',
            'info_create_time',
            'info_update_time',
        ];
    }
    
    //排序字段user表
    public function userSort()
    {
        return [
            'user_id',
            'user_order',
            'user_mobile',
            'user_views',
            'user_hits',
            'user_slug',
            'user_create_time',
            'user_update_time',
        ];
    }
}