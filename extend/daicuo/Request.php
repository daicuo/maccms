<?php
namespace daicuo;

class Request
{
	
	//主域名自动跳转到移动端子域名
	public static function appBegin()
    {
		if($jumpUrl = self::wapUrl()){
			header('HTTP/1.1 302 Moved Permanently');
			header('Location: '.$jumpUrl);
			exit();
		}
	}
	
	//移动端访问时，检测网址是否为移动端设置的子域名 返回需跳转的地址/false
	public static function wapUrl()
    {
		if( config('common.wap_domain') && request()->isMobile()){
			$domain = str_replace(['https://','http://'],'',request()->domain());
			if(config('common.wap_domain') != $domain){
				$url = request()->url(true);
				return str_replace($domain, config('common.wap_domain'), $url);
			}
		}
		return false;
	}

}