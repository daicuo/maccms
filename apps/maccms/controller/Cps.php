<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Cps extends Front{
    
    public function index()
    {
        $url = 'http://hao.daicuo.cc/1.4/cps/?type='.input('type','douyin').'&id='.config("common.site_id");
        
        $json = json_decode(DcCurl('auto',10,$url),true);
        
        if($json['url']){
            $this->redirect($json['url'],302);
        }
        
        return '广告已下线';
    }

}