<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Play extends Front{

    //继承
    public function _initialize(){
        parent::_initialize();
    }
    
    //播放页 maccms/play/index?tid=12&id=182&ep=77&from=play0
    public function index(){
        $term  = array();
        $tid   = input('tid/d',0);   //分类ID
        $id    = input('id/d',51930);//视频ID
        $ep    = input('ep/d',51930)-1;//视频集数
        $from  = input('from/s','play0');//播放器组
        //查询已绑定的apiTid
        if($tid){
            $term = categoryId($tid);
        }else{
            $term['term_id'] = 0;
        }
        if($term['term_id']){
            $info = apiDetail($id, $term['term_api_url'], $term['term_api_type'] );
        }else{
            $info = apiDetail($id);
        }
        //play标签
        if($info['play_list'][$from]){
            $info['play_from'] = $from;
            foreach($info['play_list'][$from][$ep] as $key=>$value){
               $info[$key] = $value;
            }
        }
        //播放器
        $info['vod_player'] = DcPlayer(['type'=>$info['play_from'],'url'=>$info['play_url']]);
        if($term){
            $info = array_merge($info, $term);
        }
        $this->assign($info);
        return $this->fetch();
    }
    
    //空操作
    public function _empty($name){
    }
  
}