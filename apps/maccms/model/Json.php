<?php
namespace app\common\model;

use app\maccms\model\Api;

class Json extends Api
{
    //列表页接口
	public function item($api='', $args=[])
    {
        return $this->apis($api, $args, 'item');
    }
    
    //详情页接口
    public function detail($api='', $args=[])
    {
        return $this->apis($api, $args, 'detail');
    }
    
    //通用采集接口
    private function apis($api='', $args=[], $action='detail')
    {
        unset($args['ac']);
        //API初始参数
        $url = array();
        $url['ac']  = 'detail';//list|detail
        $url['wd']  = '';//search
		$url['t']   = '';//分类ID
		$url['h']   = '';//时间限制
		$url['rid'] = '';//播放器名称
		$url['ids'] = '';//vodids
		$url['pg']  = 1;//page
        $url['at']  = 'json';//page
        //合并参数
        $url = DcArrayArgs($args, $url);
        //远程读取数据
        $data = DcCurl('auto', 20, $api.'?'.http_build_query($url));
        $data = json_decode($data, true);
        //数据字典转换
        $item = array();
        foreach($data['list'] as $key=>$value){
            $item[$key] = $this->detail_data($value);
        }
        //详情页只返回详情数据
        if($action == 'detail'){
            if($data['total'] > 1){
               return $item; 
            }
            return $item[0];
        }
        //拼装分页数据
        $page = array();
        $page['total']        = $data['total'];
        $page['per_page']     = $data['limit'];
        $page['current_page'] = $data['page'];
        $page['last_page']    = $data['pagecount'];
        //返回数据
        return ['page'=>$page, 'item'=>$item];
    }
    
    //获取远程分类列表
    private function apiList($api='')
    {
        $url = array();
        $url['ac']  = 'list';//list|detail
		$url['pg']  = 1;//page
        $url['at']  = 'json';//page
        $data = DcCurl('auto', 20, $api.'?'.http_build_query($url));
        $data = json_decode($data, true);
        return $data['class'];
    }
    
    //分类字典转化xml
    public function item_data($data=[]){
        return $data['class'];
    }
    
    //详情字典转换xml
    public function detail_data($data=[]){
        if(!$data){
            return null;
        }
        $this->fields = [
            'type_id'             => 'type_id',
            'type_name'           => 'type_name',
            'vod_id'              => 'vod_id',
            'vod_title'           => 'vod_name',
            'vod_name'            => 'vod_sub',
            'vod_cover'           => 'vod_pic',
            'vod_cover2'          => 'vod_pic_slide',
            'vod_cover3'          => 'vod_pic_thumb',
            'vod_content'         => 'vod_content',
            'vod_language'        => 'vod_lang',
            'vod_area'            => 'vod_area',
            'vod_year'            => 'vod_year',
            'vod_actor'           => 'vod_actor',
            'vod_director'        => 'vod_director',
            'vod_updatetime'      => 'vod_time',
            'vod_play'            => 'vod_play_from',
            'episode_total'       => 'vod_total',
            'episode_status'      => 'vod_serial',
            'episode_title'       => 'vod_remarks',
            'play_list'           => 'vod_play_url',
            'down_list'           => 'vod_down_url',
        ];
        //字段转化
        $data = $this->data_fields($data);
        $data['vod_year']     = $this->data_explode($data['vod_year']);
        $data['vod_area']     = $this->data_explode($data['vod_area']);
        $data['vod_language'] = $this->data_explode($data['vod_language']);
        $data['vod_actor']    = $this->data_explode($data['vod_actor']);
        $data['vod_director'] = $this->data_explode($data['vod_director']);
        $data['vod_content']  = maccmsTrim(strip_tags($data['vod_content'],'<p>,<br>'));
        $data['vod_play']     = explode('$$$',$data['vod_play']);
        $data['play_list']    = $this->play_list($data['vod_play'], $data['play_list']);
        $data['play_last']    = $this->play_last($data['type_id'], $data['vod_id'], $data['play_list']);
        return $data;
    }
    
    //播放列表
    private function play_list($vod_play=[], $vod_url=''){
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
    private function play_one($playOne='')
    {
        $urlItem = explode('#', $playOne);
        $urlOne = array();
        foreach($urlItem as $key=>$value){
            list($play_name, $play_url, $logo) = explode('$',$value);
            $urlOne[$key]['play_index'] = $key+1;
            if($play_url){
                $urlOne[$key]['play_title'] = DcEmpty($play_name, '第'.($key+1).'集');
                $urlOne[$key]['play_url']   = $play_url;  
            }else{
                $urlOne[$key]['play_title'] = '第'.($key+1).'集';
                $urlOne[$key]['play_url']   = $play_name;
            }
            $urlOne[$key]['play_cover'] = $logo;
        }
        return $urlOne;
    }
}
