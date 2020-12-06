<?php
namespace app\maccms\controller;

use app\common\controller\Front;

//播放页 maccms/play/index?tid=12&id=182&ep=77&from=play0

class Play extends Front{

    private $term = [];

    //继承
    public function _initialize(){
        parent::_initialize();
    }
    
    //按本地分类获取数据入口
    public function index(){
        if($tid = input('tid/d',0)){
            return $this->get( categoryId($tid) );
        }else{
            $this->error(lang('mustIn'));
        }
    }
    
    //按远程分类获取数据入口 
    //如果有多个资源站,可能存在多个资源站tid相同的情况
    public function type(){
        if($tid = input('tid/d',0)){
            return $this->get( categoryMeta('term_api_tid', $tid) );
        }else{
            $this->error(lang('mustIn'));
        }
    }
    
    //空操作
    public function _empty($name){
        $term = categorySlug(DcHtml($name));
        if($term){
           return $this->get($term);
        }
        $this->error(lang('mustIn'));
    }
    
    //按本地分类ID获取远程数据
    private function get($term = []){
        $id    = input('id/d',88);//视频ID
        $ep    = input('ep/d',88)-1;//视频集数
        $from  = input('from/s','play0');//播放器组
        //指定分类附加参数
        if($term['term_api_params']){
            $api_params = config('maccms.api_params');
            config('maccms.api_params', $term['term_api_params']);
        }
        $info = apiDetail($id, $term['term_api_url'], $term['term_api_type'] );
        //还原默认附加参数
        if($term['term_api_params']){
            config('maccms.api_params', $api_params);
        }
        //play标签
        if($info['play_list'][$from]){
            $info['play_from'] = $from;
            foreach($info['play_list'][$from][$ep] as $key=>$value){
               $info[$key] = $value;
            }
        }
        //播放器
        $info['vod_player'] = DcPlayer(['type'=>$info['play_from'],'poster'=>$info['vod_cover'],'url'=>$info['play_url']]);
        if($term){
            $info = array_merge($info, $term);
        }
        $this->assign($info);
        return $this->fetch('index');
    }
}