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
        //加载缓存配置
        if( !\think\Env::get('cache.type') ){
            DcLoadConfig('./datas/config/cache.php');
        }
        //加载路由配置
        action('common/Route/appInt','','event');
        //Route::rule('new/:id','home/News/read');
    }
    
    //应用开始
    public function appBegin(&$params)
    {
        //加载框架动态配置
        action('common/Op/appBegin','','event');
        //主域名与移动端域名切换
        action('common/Request/appBegin','','event');
        //注册框架动态钩子
        action('common/Hook/appBegin','','event');
    }
    
    //模块初始化
    public function moduleInit(&$params)
    {
        //获取当前模块名
        $module = request()->module();
        //加载框架所有应用插件信息配置函数等
        action('common/Apply/moduleInit', $module, 'event');
        //后台|API应用验证
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
                halt(DcHtml(DcEmpty(config('common.site_close'), lang('close'))));
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