<?php
namespace app\maccms\event;

use think\Controller;

class Client extends Controller
{
    public $cacheName = '';
    
    public $cacheTime = '';
    
    public $apiUrl    = '';
    
    public $apiData   = '';
    
	public function _initialize(){
		parent::_initialize();
	}
    
    //列表采集入口(缓存)
    public function item($api, $args){
        //附带参数
        parse_str(config('maccms.api_params'), $params);
        if($params){
            $args = array_merge($args, $params);
            unset($params);
        }
        //缓存获取
        if( (config('cache.expire_item') > 0) || (config('cache.expire_item')===0) ){
            $this->cacheTime = config('cache.expire_item');
        }
        if($this->cacheTime){
            $this->cacheName = md5($api.http_build_query($args));
            if($list = cache($this->cacheName)){
                return $list;
            }
        }
        //检测资源站接口类型
        $type = $this->check($api);
        if($type == false){
            return null;
        }
        $model = model('maccms/'.$type);
        $list = $model->item($api, $args);
        if($this->cacheName && $list){
            cache($this->cacheName, $list, $this->cacheTime);
        }
        return $list;
	}
    
    //详情采集入口(缓存)
	public function detail($api, $args){
        //附带参数
        parse_str(config('maccms.api_params'), $params);
        if($params){
            $args = array_merge($args, $params);
            unset($params);
        }
        //缓存获取
        if( (config('cache.expire_detail') > 0) || (config('cache.expire_detail')===0) ){
            $this->cacheTime = config('cache.expire_detail');
        }
        if($this->cacheTime){
            $this->cacheName = md5($api.http_build_query($args));
            if($data = cache($this->cacheName)){
                return $data;
            }
        }
        //检测资源站接口类型
        $type = $this->check($api);
        if($type == false){
            return null;
        }
        $model = model('maccms/'.$type);
        $data = $model->detail($api, $args);
        if($this->cacheName && $data){
            cache($this->cacheName, $data, $this->cacheTime);
        }
        return $data;
	}
    
    //检测资源站接口类型
    public function check($api, $bind=false){
        if(!$api){
            return false;
        }
        $url = parse_url($api);
        //缓存获取接口类型
        $apiType = DcCache( md5($url['host']) );
        if( $apiType ){
            return $apiType;
        }
        //远程验证
        $apiUrl = $url['scheme'].'://'.$url['host'].$url['path'];
        $apiData = DcCurl('auto', 30, $apiUrl.'?ac=list&limit=1&action=desc&g=plus&m=api&a=json&'.config('maccms.api_params'));
        //连接API失败
        if( !$apiData ){
           return false;
        }
        //检测是否json接口
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
                $apiType =  'Xml';
            }
        }
        //绑定分类时返回检测数据
        if($bind == true){
            $this->apiUrl  = $apiUrl;
            $this->apiData = $data;
        }
        //永久缓存
        if( $apiType ){
            DcCacheTag('apiCheck', md5($url['host']), $apiType, 0);
            return $apiType;
        }
        //默认返回false
        return false;
    }
    
    //api资源站绑定
    public function bind($apiUrl){
        DcCacheTag('apiCheck', 'clear', 'clear');
        $type = $this->check($apiUrl, true);
        if($type == false){
            return null;
        }
        $list = model('maccms/'.$type)->item_data($this->apiData);
        return ['type'=>$type, 'api'=>$this->apiUrl, 'list'=>$list];
    }
}