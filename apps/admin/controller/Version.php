<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class Version extends Admin{
	
	//比较框架版本
	public function index(){
		if(input('version/s')){
            $version = new \daicuo\Version();
			if( !$version->compare(config('daicuo.version'), input('version/s')) ){
				return json(array('code'=>1,'msg'=>lang('dcUpdate')));
			}
		}
		return json(array('code'=>0));
	}
	
	//客户端两个版本号比较
	public function client(){
		$old = input('old/s');
		$new = input('new/s');
		if(!$old || !$new){
			return json(array('code'=>0));
		}
		$version = new \daicuo\Version();
		if( $version->compare($old, $new) ){
			return json(array('code'=>0));//版本一致
		}else{
			return json(array('code'=>1,'msg'=>lang('dcUpdate').DcHtml($new)));
		}
	}
	
	//服务端版本比较
	public function server(){
		$version = new \daicuo\Version();
        $module = input('module/s','daicuo');
        $url = lang('appServer').'/version/?action=check&module='.$module;
		if(!$url || !$version){
			return json(array('code'=>0));
		}
		$json = json_decode(DcCurl('auto', 10, $url),true);
		if($json['version']){
			if( !$version->compare($version, $json['version']) ){//需要升级
				return json(array('code'=>1,'update'=>remove_xss($json['update']),'msg'=>lang('dcUpdate').DcHtml($json['version'])));
			}
		}
		return json(array('code'=>0));//版本一致
	}	
		
}
