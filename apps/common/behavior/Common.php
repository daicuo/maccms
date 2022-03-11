<?php
// +----------------------------------------------------------------------
// | DaiCuo框架[基于ThinkPHP5.0开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2020 http://www.daicuo.net
// +----------------------------------------------------------------------
// | DaiCuo承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 老谭 <271513820@qq.com>
// +----------------------------------------------------------------------

namespace app\common\behavior;

class Common
{
    //应用初始化
    public function appInit(&$params)
    {
        //加载缓存文件配置
        if(!\think\Env::get('cache.type')){
            \daicuo\Cache::appInt();
        }
        
        //加载路由动态配置cache(route_all)
        \daicuo\Route::appInt();
    }
    
    //应用开始
    public function appBegin(&$params)
    {
        //所有设为自动加载的配置（公共模块）cache(config_common)
        \daicuo\Op::config('common');
        
        //主域名与移动端域名切换
        \daicuo\Request::appBegin();
        
        //是否开启调试模式显示错误信息
        if(config('common.app_debug')=='on'){
            config('show_error_msg',true);
        }
    }
    
    //模块初始化
    public function moduleInit(&$params)
    {
        //获取当前模块名
        $module = $params->module();//request()->module()
        
        //加载框架所有插件的初始配置与动态配置
        \daicuo\Apply::moduleInit($module);
        
        //加载框架所有动态语言(数据库里面的语言包)
        \daicuo\Lang::menuInit();
        
        //应用验证（admin|API）
        if( !in_array($module, ['admin','api']) ){
            //插件安装验证
            $applys = config('common.site_applys');
            if(!$applys[$module]){
                halt(lang('unInstalled'));
            }
            //插件是否禁用
            if($applys[$module]['disable']){
                halt(lang('apply_fail_disable'));
            }
            unset($applys);
            //网站开关验证
            if(config('common.site_status') == 'off'){
                halt(DcEmpty(config('common.site_close'), lang('close')));
            }
            //多模板主题路径
            config('template.view_path', DcViewPath($module, request()->isMobile()));
        }
    }
    
    /**
     * 表单验证
     * @param array $data 表单数据（一维数组）
     * @param string $error 出错信息 (空)
     * @param string $validate_name 验证名称
     * @param string $validate_scene 验证场景(save/update/空)
     * @param bool $result 验证结果(false|true)
     */
    public function formValidate(&$params)
    {
        //是否需要数据验证
        if( config('common.validate_name') ){
            if(false === DcCheck($params['data'], config('common.validate_name'), config('common.validate_scene') )){
                $params['error'] = config('daicuo.error');
                $params['result'] = false;
            }
        }
        //是否需要token验证
        if( config('common.validate_token') ){
           if( !\think\Validate::is($params['data']['__token__'], "token", ['__token__' => $params['data']['__token__']]) ){
               $params['error'] = lang('form_token_error');
               $params['result'] = false;
           }
        }
    }

}