<?php
namespace app\maccms\controller;

use app\common\controller\Front;

//播放页 maccms/play/index?from=play0&id=182&ep=77

class Play extends Front
{
    //继承
    public function _initialize()
    {
        parent::_initialize();
    }
    
    //按本地分类获取数据入口
    public function index()
    {
        $id    = input('id/d',1);//视频ID
        $ep    = input('ep/d',1)-1;//视频集数
        $from  = input('from/s','play0');//播放器组
        //API数据
        $info = apiDetail($id);
        //play标签
        if($info['play_list'][$from]){
            $info['play_from'] = $from;
            foreach($info['play_list'][$from][$ep] as $key=>$value){
               $info[$key] = $value;
            }
        }
        //播放器
        $info['vod_player'] = DcPlayer(['type'=>$info['play_from'],'poster'=>$info['vod_cover'],'url'=>$info['play_url']]);
        //模板变量
        $this->assign($info);
        //本地分类
        if($info['type_id']){
            $this->assign(categoryMeta('term_api_tid',$info['type_id']));
        }
        //加载模板
        return $this->fetch('index');
    }
}