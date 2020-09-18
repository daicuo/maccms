<?php
namespace app\common\model;

use app\maccms\model\Api;

class Xml extends Api{

    //字段转换字典
    protected $fields = [
        'vod_id'    => 'a',
        'vod_title' => 'b',
        'vod_name'  => 'c',
    ];
    
    //列表数据xml接口
	public function item($api, $args){
        //API参数 搜索时只能走list接口
        $url = array();
        $url['ac']  = 'list';//videolist|list
        $url['wd']  = '';//search
		$url['t']   = '';//分类ID
		$url['h']   = '';//时间限制
		$url['rid'] = '';//播放器名称
		$url['ids'] = '';//vodids
		$url['pg']  = 1;//page
        if(is_array($args)){
            $url = array_merge($url, $args);
        }
        //远程读取数据
        //dump($api.'?'.http_build_query($url));
        $xml = DcCurl('auto', 10, $api.'?'.http_build_query($url));
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        //拼装分类列表
        $type = $this->item_data($xml);
        //拼装分页数据
        $xml = json_decode(json_encode($xml), true);
        $page = array();
        $page['total'] = $xml['list']["@attributes"]['recordcount'];
        $page['per_page'] = $xml['list']["@attributes"]['pagesize'];
        $page['current_page'] = $xml['list']["@attributes"]['page'];
        $page['last_page'] = $xml['list']["@attributes"]['pagecount'];
        //无详情页标识pic时直接返回VID
        $ids  = array();
        $item = array();
        foreach($xml['list']['video'] as $key=>$value){
            if(isset($value['pic'])){
                $item[$key] =  $this->detail_data($value);
            }else{
                //$item[$key] =  ['vod_id'=>$value['id']];
                array_push($ids, $value['id']);
            }
        }
        //需要二次采集
        if($ids){
            foreach(array_chunk($ids,10) as $key=>$value){
               $detail = $this->detail($api, ['ids'=>implode(',',$value)]);
               if($detail){
                   $item = array_merge($item, $detail);
               }
            }
        }
        return ['page'=>$page, 'type'=>$type, 'item'=>$item];
        /*ac=list时需要二次抓取
        if($url['ac'] == 'list'){
            $ids = array();
            foreach($xml['list']['video'] as $key=>$value){
               array_push($ids, $value['id']);
            }
            if($ids){
                $item = $this->detail_xml($api, ['ids'=>implode(',',$ids)]);
            }
        }else{
            foreach($xml['list']['video'] as $key=>$value){
               $item[$key] =  $this->detail_data($value);
            }
        }
        return ['page'=>$page, 'item'=>$item];*/
    }
    
    //详情页xml接口
    public function detail($api, $args){
        //API参数
        $url = array();
        $url['ac']  = 'videolist';//videolist|list
		$url['t']   = '';//分类ID
		$url['h']   = '';//时间限制
		$url['rid'] = '';//播放器名称
		$url['ids'] = '';//vodids
        $url['pg']  = 1;//page
        //$url['wd']  = '';//search
        if(is_array($args)){
            $url = array_merge($url, $args);
        }
        //return urldecode($api.'?'.http_build_query($url));
        //远程读取数据
        $xml = DcCurl('auto', 20, urldecode($api.'?'.http_build_query($url)) );
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xml = json_decode(json_encode($xml), true);
        //多个IDS时返回列表
        if($xml['list']["@attributes"]['recordcount'] > 1){
            $item = array();
            foreach($xml['list']['video'] as $key=>$value){
                $item[$key] = $this->detail_data($value);
            }
            return $item;
        }else{
            return $this->detail_data($xml['list']['video']);
        }
    }
    
    //分类字典转化xml
    public function item_data($data){
        $item = array();
        $key = 0;
        foreach($data->class->ty as $list){
            $item[$key]['type_id'] = (int)$data->class->ty[$key]['id'];
            $item[$key]['type_name'] = (string)$list;
            $key++;
        }
        return $item;
    }
    
    //详情字典转换xml
    public function detail_data($data){
        if(!$data){
            return null;
        }
        $this->fields = [
            'type_id'             => 'tid',
            'type_name'           => 'type',
            'vod_id'              => 'id',
            'vod_title'           => 'name',
            'vod_name'            => 'name2',
            'vod_cover'           => 'pic',
            'vod_cover2'          => 'pic2',
            'vod_cover3'          => 'pic3',
            'vod_content'         => 'des',
            'vod_language'        => 'lang',
            'vod_area'            => 'area',
            'vod_year'            => 'year',
            'vod_actor'           => 'actor',
            'vod_director'        => 'director',
            'vod_updatetime'      => 'last',
            'vod_play'            => 'dt',
            'episode_total'       => 'total',
            'episode_status'      => 'state',
            'episode_title'       => 'note',
            'play_list'           => 'dl',
            'down_list'           => '',
        ];
        //字段转化
        $data = $this->data_fields($data);
        $data['vod_year']     = $this->data_explode($data['vod_year']);
        $data['vod_area']     = $this->data_explode($data['vod_area']);
        $data['vod_language'] = $this->data_explode($data['vod_language']);
        $data['vod_actor']    = $this->data_explode($data['vod_actor']);
        $data['vod_director'] = $this->data_explode($data['vod_director']);
        $data['play_list']    = $this->play_list($data['play_list']);
        $data['play_last']    = $this->play_last($data['type_id'], $data['vod_id'], $data['play_list']);
        return $data;
    }
    
    //播放列表xml
    private function play_list($xml){
        if( empty($xml) ){
            return '';
        }
        //只有一组地址
        if( is_string($xml['dd']) ){
           $xml['dd'] = [$xml['dd']] ;
        }
        //$playName = explode('#', $xml['dt']);
        $playList = array();
        foreach($xml['dd'] as $key=>$value){
            $playList['play'.$key] = $this->play_one($value);
        }
        return $playList;
	}
    
    //播放分组xml
    private function play_one($playOne){
        $urlOne = array();
        foreach(explode('#', $playOne) as $key=>$value){
            list($play_name, $play_url) = explode('$',$value);
            $urlOne[$key]['play_index'] = $key+1;
            $urlOne[$key]['play_title'] = DcEmpty($play_name, '第'.($key+1).'集');
            $urlOne[$key]['play_url'] = $play_url;
            $urlOne[$key]['play_cover'] = '';
        }
        return $urlOne;
    }
}
