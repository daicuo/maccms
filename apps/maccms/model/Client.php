<?php
namespace app\maccms\model;

class Client
{
    public $cacheName = '';
    
    public $cacheTime = '';
    
    public $apiUrl    = '';
    
    public $apiData   = '';
    
    //列表采集入口(缓存)
    public function item($args=[], $api='', $apiType='')
    {
        //附加参数
        parse_str(config('maccms.api_params'), $params);
        if($params){
            $args = array_merge($args, $params);
            unset($params);
        }
        //缓存获取
        if( (config('cache.expire_item') > 0) || (config('cache.expire_item')===0) ){
            $this->cacheTime = config('cache.expire_item');
            $this->cacheName = md5($api.http_build_query($args));
            if($list = cache($this->cacheName)){
                return $list;
            }
        }
        //资源站接口类型
        $type = DcEmpty($apiType, config('maccms.api_type'));
        if($type == false){
            return null;
        }
        //加载API引擎
        $model = model('maccms/'.ucfirst($type));
        $list = $model->item($api, $args);
        if($this->cacheName && $list){
            //缓存列表数据
            cache($this->cacheName, $list, $this->cacheTime);
            //提前缓存详情页数据
            foreach($list['item'] as $key=>$value){
                $this->detail_cache($api, $value['vod_id'], $value);
            }
        }
        return $list;
	}
    
    //详情采集入口(缓存)
	public function detail($args=[], $api='', $apiType='')
    {
        //附带参数
        parse_str(config('maccms.api_params'), $params);
        if($params){
            $args = array_merge($args, $params);
            unset($params);
        }
        //缓存获取
        if( (config('cache.expire_detail') > 0) || (config('cache.expire_detail')===0) ){
            $this->cacheTime = config('cache.expire_detail');
            $this->cacheName = md5($api.http_build_query($args));
            if($data = cache($this->cacheName)){
                return $data;
            }
        }
        //检测资源站接口类型
        $type = DcEmpty($apiType, config('maccms.api_type'));
        if($type == false){
            return null;
        }
        //加载API引擎
        $model = model('maccms/'.ucfirst($type));
        $data = $model->detail($api, $args);
        if($this->cacheName && $data){
            cache($this->cacheName, $data, $this->cacheTime);
        }
        return $data;
	}
    
    //api资源站绑定与智能分析
    public function bind($url='')
    {
        //远程验证
        $url     = parse_url($url);
        $apiUrl  = $url['scheme'].'://'.$url['host'].$url['path'];
        $apiData = DcCurl('auto', 30, $apiUrl.'?ac=list&limit=1&action=desc&g=plus&m=api&a=json&'.config('maccms.api_params'));
        $apiType = '';
        //连接API失败
        if( !$apiData ){
           return false;
        }
        //检测接口类型(Json|Xml|Feifeicms)
        $data = json_decode($apiData, true);
        if($data){
            if($data['status']){
                $apiType = 'Feifeicms';
            }else{
                $apiType =  'Json';
            }
        }else{
            //检测是否xml接口
            $data = simplexml_load_string($apiData, 'SimpleXMLElement', LIBXML_NOCDATA);
            if($data){
                $apiType = 'Xml';
            }
        }
        if(!$apiType){
            return false;
        }
        //获取资源站分类列表
        $list = model('maccms/'.$apiType)->item_data($data);
        //返回数据
        return ['type'=>$apiType, 'api'=>$apiUrl, 'list'=>$list];
    }
    
    //直接缓存详情页数据 标识IDS
	private function detail_cache($api='', $id=0, $data=[])
    {
        if( (config('cache.expire_detail') > 0) || (config('cache.expire_detail')===0) && $data ){
            //默认参数
            $args['ids'] = $id;
            //附带参数
            parse_str(config('maccms.api_params'), $params);
            if($params){
                $args = array_merge($args, $params);
                unset($params);
            }
            //检测是否已经存在缓存数据
            $cacheName = md5($api.http_build_query($args));
            //不存在缓存时设置缓存
            if(!cache($cacheName)){
                cache($cacheName, $data, config('cache.expire_detail'));
            }
            return true;
        }
        return false;
	}
}