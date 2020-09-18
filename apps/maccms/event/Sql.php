<?php
namespace app\maccms\event;

class Sql
{
    /**
    * 安装时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
	public function install(){
        //写入插件配置
        $op_value = [
            'site_title'      =>'2020最新免费电影',
            'site_keywords'   =>'2020最新电影,2020免费电影,2020电视剧',
            'site_description'=>'青苹果API影视系统提供2020年最新好看的免费电影与电视剧。',
            'theme'           =>'default_pc',
            'theme_wap'       =>'default_wap',
        ];
        $result = \daicuo\Op::write($op_value, 'maccms', '', '', '0', 'yes');
        if(!$result){
            return false;
        }
        //批量添加钩子
        $result = \daicuo\Hook::save_all([
            [
                'hook_name'=>'hook_build_form',
                'hook_path'=>'app\maccms\behavior\Hook',
                'hook_overlay'=>'no',
                'op_module'=>'maccms',
            ],
            [
                'hook_name'=>'hook_admin_init',
                'hook_path'=>'app\maccms\behavior\Hook',
                'hook_overlay'=>'no',
                'op_module'=>'maccms',
            ]
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
    public function unInstall(){
        //删除插件配置
        $status = \daicuo\Op::delete_module('maccms');
        /*if(!$status){
            return false;
        }*/
        return true;
	}
	
}