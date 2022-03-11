<?php
namespace app\admin\widget;

use think\Controller;

class Common extends Controller
{
	public function welcome()
    {
        echo $this->fetch(APP_PATH.'admin'.DS.'view'.DS.'index'.DS.'welcome.tpl');
        //远程赞助广告
        if(config('common.apply_name')){
            $url = [];
            $url['module']  = config('common.apply_module');
            $url['version'] = config('common.apply_version');
            $url['host']    = input('server.HTTP_HOST/s','127.0.0.1');
            echo( DcCurl('auto','2',\daicuo\Service::apiUrl().'/welcome/?'.http_build_query($url)) );
        }
    }
}