<?php
namespace app\maccms\event;

use think\Controller;

class Client extends Controller
{
    public $cacheName = '';
    
    public $cacheTime = '';
    
    //字段转换字典
    public $fields = [
        'vod_id'=>'a',
        'vod_title'=>'b',
        'vod_name'=>'c',
    ];
    
	public function _initialize(){
		parent::_initialize();
        
	}
    
    //api接口添加
    public function api($apiUrl){
        $url = parse_url($apiUrl);
        $api = $url['scheme'].'://'.$url['host'].$url['path'];
        $apiData = DcCurl('auto', 10, $api.'?ac=list&limit=1');
        $apiList = [];
        //抓取失败
        if(!$apiData){
           return ['type'=>'', 'list'=>''];
        }
        //检测是否json接口
        $data = json_decode($apiData, true);
        if($data){
            return ['type'=>'json', 'api'=>$api, 'list'=>$this->item_josn_data($data)];
        }
        //检测是否xml接口
        $data = simplexml_load_string($apiData, 'SimpleXMLElement', LIBXML_NOCDATA);
        if($data){
            return ['type'=>'xml', 'api'=>$api, 'list'=>$this->item_xml_data($data)];
        }
        //默认
        return ['type'=>'', 'api'=>'', 'list'=>''];
    }
    
    //列表采集入口(缓存)
    public function item($api, $args, $type='xml'){
        if( (config('cache.expire_item') > 0) || (config('cache.expire_item')===0) ){
            $this->cacheTime = config('cache.expire_item');
        }
        if($this->cacheTime){
            $this->cacheName = md5($api.http_build_query($args));
            if($list = cache($this->cacheName)){
                return $list;
            }
        }
        if($type == 'xml'){
            $list = $this->item_xml($api, $args);
        }else{
            $list = $this->item_json($api, $args);
        }
        if($this->cacheName && $list){
            cache($this->cacheName, $list, $this->cacheTime);
        }
        return $list;
	}
    
    //详情采集入口(缓存)
	public function detail($api, $args, $type='xml'){
        if( (config('cache.expire_detail') > 0) || (config('cache.expire_detail')===0) ){
            $this->cacheTime = config('cache.expire_detail');
        }
        if($this->cacheTime){
            $this->cacheName = md5($api.http_build_query($args));
            if($data = cache($this->cacheName)){
                return $data;
            }
        }
        if($type == 'xml'){
            $data = $this->detail_xml($api, $args);
        }else{
            $data = $this->data_json($api, $args);
        }
        if($this->cacheName && $data){
            cache($this->cacheName, $data, $this->cacheTime);
        }
        return $data;
	}
    
    //列表数据xml接口
    public function item_xml($api, $args){
        //API参数
        $url = array();
        $url['ac']  = 'videolist';//videolist|list
        $url['wd']  = '';//search
		$url['t']   = '';//分类ID
		$url['h']   = '';//时间限制
		$url['rid'] = '';//播放器名称
		$url['ids'] = '';//vodids
		$url['pg']  = 1;//page
        if(is_array($args)){
            $url = array_merge($url, $args);
        }
        //搜索时只能走list
        if($url['wd']){
            $url['ac']  = 'list';
        }
        //远程读取数据
        $xml = DcCurl('auto', 10, $api.'?'.http_build_query($url));
        //dump($api.'?'.http_build_query($url));
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xml = json_decode(json_encode($xml), true);
        //拼装返回数据
        $page = array();
        $page['total'] = $xml['list']["@attributes"]['recordcount'];
        $page['per_page'] = $xml['list']["@attributes"]['pagesize'];
        $page['current_page'] = $xml['list']["@attributes"]['page'];
        $page['last_page'] = $xml['list']["@attributes"]['pagecount'];
        //无详情页标识pic时直接返回VID
        $item = array();
        $ids = array();
        foreach($xml['list']['video'] as $key=>$value){
            if(isset($value['pic'])){
                $item[$key] =  $this->detail_xml_data($value);
            }else{
                //$item[$key] =  ['vod_id'=>$value['id']];
                array_push($ids, $value['id']);
            }
        }
        //需要二次采集
        if($ids){
            foreach(array_chunk($ids,10) as $key=>$value){
               $detail = $this->detail_xml($api, ['ids'=>implode(',',$value)]);
               if($detail){
                   $item = array_merge($item, $detail);
               }
            }
        }
        return ['page'=>$page, 'item'=>$item];
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
               $item[$key] =  $this->detail_xml_data($value);
            }
        }
        return ['page'=>$page, 'item'=>$item];*/
    }
    
    //详情页xml接口
    public function detail_xml($api, $args){
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
                $item[$key] = $this->detail_xml_data($value);
            }
            return $item;
        }else{
            return $this->detail_xml_data($xml['list']['video']);
        }
    }
    
    //分类字典转化xml
    private function item_xml_data($data){
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
    private function detail_xml_data($data){
        if(!$data){
            return '';
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
            'episode_total'       => 'total',
            'episode_status'      => 'state',
            'episode_title'       => 'note',
            'play_list'           => 'dl',
            'down_list'           => '',
        ];
        //字段转化
        $data = $this->dataFields($data);
        $data['vod_year']     = $this->dataExplode($data['vod_year']);
        $data['vod_area']     = $this->dataExplode($data['vod_area']);
        $data['vod_language'] = $this->dataExplode($data['vod_language']);
        $data['vod_actor']    = $this->dataExplode($data['vod_actor']);
        $data['vod_director'] = $this->dataExplode($data['vod_director']);
        $data['play_list']    = $this->play_list_xml($data['play_list']);
        $data['play_last']    = $this->playLast($data['type_id'], $data['vod_id'], $data['play_list']);
        return $data;
    }
    
    //播放列表xml
    private function play_list_xml($xml){
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
            $playList['play'.$key] = $this->play_one_xml($value);
        }
        return $playList;
	}
    
    //播放分组xml
    private function play_one_xml($playOne){
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
    
   /*------------------------------------------------------------------------------------------------------------*/
   
    //分类字典转化json
    private function item_josn_data($data){
        //maccms
        if($data['class']){
            return $data['class'];
        }
        //feifeicms
        $item = array();
        foreach($data['list'] as $key=>$value){
            $item[$key]['type_id'] = $value['list_id'];
            $item[$key]['type_name'] = $value['list_name'];
        }
        return $item;
    }
    
    private function detail_json_data($json){
        if(!$json){
            return false;
        }
        $data = array();
        $data['episode_statustext'] = $json['vod_remarks'];
        $data['episode_status'] = $json['vod_id'];
        $data['episode_total'] = $json['vod_total'];
        $data['id'] = $json['vod_id'];
        $data['type_id'] = $json['type_id'];
        $data['type_name'] = $json['type_name'];
        $data['title'] = $json['vod_name'];
        $data['subtitle'] = $json['vod_sub'];
        $data['intro'] = $json['vod_content'];
        $data['coverpic'] = $json['vod_pic'];
        $data['coverpic2'] = $json['vod_pic_thumb'];
        $data['coverpic3'] = $json['vod_pic_slide'];
        $data['play_list']=$this->play_list_json($json);
        $data['down_list']=$this->down_list_json($json);
        dump($data);
        dump($json);
    }
    
    //播放列表json
	private function play_list_json($json){
        $playNote = DcEmpty($json["vod_play_note"], '$$$');
        $playName = explode($playNote, $json['vod_play_from']);
        $playServer = explode($playNote, $json['vod_play_server']);
        $playUrl = explode($playNote, $json['vod_play_url']);
        $downName = explode($playNote, $json['vod_down_from']);
        $downUrl = explode($playNote, $json['vod_down_url']);
        $playList = array();
        foreach($playName as $key=>$value){
            $playList[$value] = $this->play_one($playUrl[$key]);
        }
        return $playList;
	}
    
    //下载地址json
    private function down_list_json($json){
        $playNote = DcEmpty($json["vod_play_note"], '$$$');
        $downName = explode($playNote, $json['vod_down_from']);
        $downUrl = explode($playNote, $json['vod_down_url']);
        $downList = array();
        foreach($downName as $key=>$value){
            $downList[$value] = $this->play_one($downUrl[$key]);
        }
        return $downList;
	}
    
    //播放地址分组
	private function play_one_json($playOne){
        $urlOne = array();
        foreach(explode('#',$playOne) as $key=>$value){
            list($play_name, $play_url) = explode('$',$value);
            $urlOne[$key]['play_index'] = $key+1;
            $urlOne[$key]['play_name'] = DcEmpty(DcHtml($play_name), '第'.($key+1).'集');
            $urlOne[$key]['play_url'] = $play_url;
            $urlOne[$key]['play_cover'] = '';
        }
        return $urlOne;
    }
    
    //获取最后一集播放地址
    private function playLast($tid, $id, $array){
        $i = 1;
        foreach($array as $key=>$value){
            if($i == 1){
                $maxKey = $key;
                $max = 1;
            }
            $i++;
            if( count($value) > $max){
                $max = count($value);
                $maxKey = $key;
            }
        }
        return ['tid'=>intval($tid), 'id'=>$id, 'ep'=>$max, 'from'=>$maxKey];
        //return DcUrl('maccms/api/play', ['tid'=>intval($tid), 'id'=>$id, 'ep'=>$max, 'from'=>$maxKey]);
    }
    
    //字典转换
    private function dataFields($array){
        //$array = ['a'=>1,'b'=>2,'c'=>3];
        $fields = array_flip($this->fields);
        $data = array();
        foreach($array as $key=>$value){
            if( isset($fields[$key]) ){
                $data[$fields[$key]] = $value;
            }
        }
        //安全过滤
        foreach($data as $key=>$value){
            if(!is_array($value)){
                $data[$key] = DcHtml($value);
            }
        }
        return $data;
    }
    
    //格式化分隔符
    private function dataExplode($string){
        $string = str_replace(array('/','，','|','、',',,,',',,',';'), ',', $string);
        return explode(',', $string);
    }
}