<?php
namespace app\common\model;

use app\maccms\model\Api;

class Feifeicms extends Api{

    //字段转换字典
    protected $fields = [
        'vod_id'    => 'a',
        'vod_title' => 'b',
        'vod_name'  => 'c',
    ];
    
    //列表页接口
	public function item($api, $args){
        return $this->apis($api, $args, 'item');
    }
    
    //详情页接口
    public function detail($api, $args){
        return $this->apis($api, $args, 'detail');
    }
    
    //通用采集接口
    private function apis($api, $args, $action='detail'){
        //API参数
        $url = array();
        $url['g']      = 'plus';
        $url['m']      = 'api';
        $url['a']      = 'json';
        $url['action'] = 'desc';//
        $url['limit']  = 30;//
		$url['cid']    = $args['t'];//t 分类ID
		$url['h']      = '';//h 时间限制
		$url['vodids'] = $args['ids'];//ids vodids
        $url['p']      = $args['pg'];//pg 分页
        $url['wd']     = '';//wd search
        $url['play']   = $args['rid'];//rid 播放器名称
        //feifeicms增加
        $url['area']      = $args['area'];
        $url['year']      = $args['year'];
        $url['language']  = $args['language'];
        $url['actor']     = $args['actor'];
        $url['director']  = $args['director'];
        $url['wirter']    = $args['wirter'];
        $url['name']      = $args['name'];
        $url['ename']     = $args['ename'];
        $url['state']     = $args['state'];
        $url['letter']    = $args['letter'];
        $url['order']     = $args['order'];
        $url['sort']      = $args['sort'];
        //参数合并
        if(is_array($args)){
            $url = array_merge($url, $args);
        }
        //return urldecode($api.'?'.http_build_query($url));
        //远程读取数据
        $data = DcCurl('auto', 20, urldecode($api.'?'.http_build_query($url)) );
        $data = json_decode($data, true);
        //数据字典转换
        $item = array();
        foreach($data['data'] as $key=>$value){
            $item[$key] = $this->detail_data($value);
        }
        //详情页只返回详情数据
        if($action == 'detail'){
            if($data['page']['recordcount'] > 1){
               return $item; 
            }
            return $item[0];
        }
        //拼装分页数据
        $page = array();
        $page['total'] = $data['page']['recordcount'];
        $page['per_page'] = $data['page']['pagesize'];
        $page['current_page'] = $data['page']['pageindex'];
        $page['last_page'] = $data['page']['pagecount'];
        //拼装分类数据
        $type = $this->item_data($data);
        //返回数据
        return ['page'=>$page, 'type'=>$type, 'item'=>$item];
    }
    
    //分类字典转化xml
    public function item_data($data){
        $type = array();
        foreach($data['list'] as $key=>$value){
            $type[$key]['type_id'] = $value['list_id'];
            $type[$key]['type_name'] = $value['list_name'];
        }
        return $type;
    }
    
    //详情字典转换xml
    public function detail_data($data){
        if(!$data){
            return null;
        }
        $this->fields = [
            'type_id'             => 'vod_cid',
            'type_name'           => 'list_name',
            'vod_id'              => 'vod_id',
            'vod_title'           => 'vod_name',
            'vod_name'            => 'vod_title',
            'vod_cover'           => 'vod_pic',
            'vod_cover2'          => 'vod_pic_slide',
            'vod_cover3'          => 'vod_pic_bg',
            'vod_content'         => 'vod_content',
            'vod_language'        => 'vod_language',
            'vod_area'            => 'vod_area',
            'vod_year'            => 'vod_year',
            'vod_actor'           => 'vod_actor',
            'vod_director'        => 'vod_director',
            'vod_updatetime'      => 'vod_addtime',
            'vod_play'            => 'vod_play',
            'episode_total'       => 'vod_total',
            'episode_status'      => 'vod_state',
            'episode_title'       => 'vod_continu',
            'play_list'           => 'vod_url',
            'down_list'           => '',
        ];
        //字段转化
        $data = $this->data_fields($data);
        $data['vod_year']     = $this->data_explode($data['vod_year']);
        $data['vod_area']     = $this->data_explode($data['vod_area']);
        $data['vod_language'] = $this->data_explode($data['vod_language']);
        $data['vod_actor']    = $this->data_explode($data['vod_actor']);
        $data['vod_director'] = $this->data_explode($data['vod_director']);
        $data['vod_play']     = explode('$$$',$data['vod_play']);
        $data['play_list']    = $this->play_list($data['vod_play'], $data['play_list']);
        $data['play_last']    = $this->play_last($data['type_id'], $data['vod_id'], $data['play_list']);
        return $data;
    }
    
    //播放列表
    private function play_list($vod_play, $vod_url){
        if( empty($vod_url) ){
            return [];
        }
		$old_url   = explode('$$$', $vod_url);
        $playList = array();//定义播放列表
        $playFilter = explode(',', config('maccms.filter_play'));//待过滤的播放器组
        foreach($old_url as $key=>$value){
            $playFrom = $vod_play[$key];//定义播放来源
            if(!$playFrom){
                //没有找到来源时
                $playFrom = 'play'.$key;
            }else{
                //两组PPTV的情况
                if( $playList[$playFrom] ){
                    $playFrom = $playFrom.$key;
                }
            }
            //过滤播放器组
            if($playFilter){
                if( in_array($playFrom,$playFilter) ){
                    continue;
                }
            }
            //未被过滤的添加至播放列表
            $playList[$playFrom] = $this->play_one($value);
        }
        return $playList;
	}
    
    //播放分组
    private function play_one($playOne){
        $urlItem = explode( chr(13), str_replace(array("\r\n", "\n", "\r"), chr(13), $playOne) );
        $urlOne = array();
        foreach($urlItem as $key=>$value){
            list($play_name, $play_url, $logo) = explode('$',$value);
            //list($title, $url, $logo, $title_rc, $player) = explode('$', $val);
            $urlOne[$key]['play_index'] = $key+1;
            $urlOne[$key]['play_title'] = DcEmpty($play_name, '第'.($key+1).'集');
            $urlOne[$key]['play_url']   = $play_url;
            $urlOne[$key]['play_cover'] = $logo;
        }
        return $urlOne;
    }
}
