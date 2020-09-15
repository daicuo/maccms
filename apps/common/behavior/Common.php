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

use think\Controller;

use think\Route;

/**
 * 内置行为扩展
 * @package app\common\behavior
 */
class Common
{
    public function appInit(&$params)
    {
        //加载缓存配置
        DcLoadConfig('./datas/config/cache.php');
        //加载路由配置
        action('common/Route/appInt','','event');
        //Route::rule('new/:id','home/News/read');
    }
    
    public function appBegin(&$params)
    {
        //加载框架默认动态配置
        action('common/Op/appBegin','','event');
        //主域名与移动端域名切换
        action('common/Request/appBegin','','event');
        //注册后台自定义的钩子行为
        action('common/Hook/appBegin','','event');
        //加载框架所有应用配置信息等
        action('common/Apply/appBegin','','event');
    }
    
    public function moduleInit(&$params)
    {
        $module = request()->module();
        if('admin' != $module){
            //插件安装验证
            $applys = config('common.site_applys');
            if(!$applys[$module]){
                halt(lang('unInstalled'));
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
    
    public function actionBegin(&$params){
    }
    
    public function viewFilter(&$params){
    }            
    
    public function appEnd(&$params){
    }
    
    public function logWrite(&$params){
    }
    
    public function logWriteDone(&$params){
    }
    
    public function responseSend(&$params){
    }
    
    public function responseEnd(&$params){
    }
}