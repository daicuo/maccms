<?php
namespace app\index\event;

class Sql
{
    /**
    * 安装时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
	public function install(){
        //写入插件配置
        $op_value = [
            'site_title'      =>'大错开发框架',
            'site_keywords'   =>'欢迎使用大错（DaiCuo）开发您的项目',
            'site_description'=>'基于ThinkPHP、Bootstrap、Jquery的极速后台开发框架',
            'theme'           =>'default_pc',
            'theme_wap'       =>'default_wap',
        ];
        $result = \daicuo\Op::write($op_value,'index','','','0','yes');
        if(!$result){
            return false;
        }
        //批量添加钩子
        $result = \daicuo\Hook::save_all([
            [
                'hook_name'=>'hook_build_form',
                'hook_path'=>'app\index\behavior\Hook',
                'hook_overlay'=>'no',
                'op_module'=>'index',
            ],
            [
                'hook_name'=>'hook_admin_init',
                'hook_path'=>'app\index\behavior\Hook',
                'hook_overlay'=>'no',
                'op_module'=>'index',
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
        \daicuo\Op::delete_module('index');
        //直接返回结果
        return true;
	}
	
}