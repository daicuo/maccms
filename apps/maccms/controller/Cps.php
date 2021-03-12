<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Cps extends Front{
    
    //获取广告链接
    public function index()
    {
        $url = 'http://hao.daicuo.cc/maccms/cps/?type='.input('type','douyin').'&id='.config("common.site_id").'&host='.input('server.HTTP_HOST');
        
        $json = json_decode(DcCurl('auto',10,$url),true);
        
        if($json['url']){
            $this->redirect($json['url'],302);
        }
        
        return '广告已下线';
    }
    
    //我的佣金
    public function reward()
    {
        $url = 'http://hao.daicuo.cc/maccms/reward/?host='.input('server.HTTP_HOST');
        
        $json = json_decode(DcCurl('auto',10,$url),true);
        
        if($json['url']){
            $this->redirect($json['url'],302);
        }
        
        return '暂未开通';
    }

}