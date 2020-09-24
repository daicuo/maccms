<?php
namespace app\index\event;

class Sql
{
    /**
    * 安装时触发/通常用于数据库操作或调用接口
    * @return bool 只有返回true时才会往下执行
    */
	public function install(){
        
        //批量添加钩子
        $result = \daicuo\Hook::save_all([
            [
                'hook_name'=>'hook_build_form',
                'hook_path'=>'app\index\behavior\Hook',
                'hook_overlay'=>'no',
                'hook_info'=>'首页模块自定义表单字段',
                'op_module'=>'index',
            ],
            [
                'hook_name'=>'hook_base_init',
                'hook_path'=>'app\index\behavior\Hook',
                'hook_overlay'=>'no',
                'hook_info'=>'首页模块初始化扩展表单字段列表',
                'op_module'=>'index',
            ]
        ]);
        //钩子不正确将影响系统正常运行
        if(!$result){
            return false;
        }
        
        //批量写入插件配置
        $result = \daicuo\Op::write([
            'site_title'      =>'大错开发框架',
            'site_keywords'   =>'欢迎使用大错（DaiCuo）开发您的项目',
            'site_description'=>'基于ThinkPHP、Bootstrap、Jquery的极速后台开发框架',
            'theme'           =>'default_pc',
            'theme_wap'       =>'default_wap',
        ],'index','','','0','yes');
        
        //批量添加路由伪静态
        $result = \daicuo\Route::save_all([
            [
                'rule'        =>'/indexabc',
                'address'     =>'index/index/abc',
                'method'      =>'get',
                'op_module'   =>'index',
            ],
            [
                'rule'        =>'/indextest',
                'address'     =>'index/index/test',
                'method'      =>'get',
                'op_module'   =>'index',
            ],
        ]);
        
        //批量添加导航
        $result = \daicuo\Nav::save_all([
            [
                'nav_text'        =>'导航1',
                'nav_type'        =>'addon',
                'nav_module'      =>'index',
                'nav_controll'    =>'index',
                'nav_action'      =>'index',
                'nav_params'      =>'a=1',
                'op_module'       =>'index',
                'op_controll'     =>'header',
                'op_action'       =>'',
            ],
            [
                'nav_text'        =>'导航2',
                'nav_type'        =>'addon',
                'nav_module'      =>'index',
                'nav_controll'    =>'index',
                'nav_action'      =>'index',
                'nav_params'      =>'a=2',
                'op_module'       =>'index',
                'op_controll'     =>'footer',
                'op_action'       =>'',
            ],
        ]);
        
        //批量添加分类
        $result = \daicuo\Term::save_all([
            [
                'term_name'       =>'首页分类1',
                'term_module'     =>'index',
                'term_weight'     =>1,
                'term_much_type'  =>'category',
                'term_much_info'  =>'首页分类1的说明',
                'term_tpl'        =>'index',
                'term_hook'       =>'扩展属性term_hook',
            ],
            [
                'term_name'       =>'首页分类2',
                'term_module'     =>'index',
                'term_weight'     =>2,
                'term_much_type'  =>'category',
                'term_much_info'  =>'首页分类2的说明',
                'term_tpl'        =>'index',
                'term_hook'       =>'扩展属性term_hook',
            ],
        ]);
        
        //批量添加用户
        $result = \daicuo\User::save_all([
            [
                'user_name'       =>'index_1',
                'user_pass'       =>'index_1',
                'user_email'      =>'1@qq.com',
                'user_mobile'     =>'13800138001',
                'user_module'     =>'index',
                'user_status'     =>'normal',
            ],
            [
                'user_name'       =>'index_2',
                'user_pass'       =>'index_2',
                'user_email'      =>'2@qq.com',
                'user_mobile'     =>'13800138002',
                'user_module'     =>'index',
                'user_status'     =>'normal',
            ],
        ]);
        
        //返回结果
        return true;
	}
    
    /**
    * 卸载时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
    public function unInstall(){
        //删除插件配置
        \daicuo\Op::delete_module('index');
        //删除插件分类
        \daicuo\Term::delete_module('index');
        //删除插件用户
        \daicuo\User::delete_module('index');
        //直接返回结果
        return true;
	}
	
}