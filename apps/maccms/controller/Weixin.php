<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Weixin extends Front{
	
	private $token = 'NxcbKZdhLtKks12XIRdfpUZ';
  
    private $follow = '你好，欢迎关注<br><br>发送（电影名字、主演名字）就可以免费看啦！';
    
    private $none = '对不起，没有搜索到，请重新输入关键词！';
	
	// 微信消息真实性认证
	public function _initialize()
    {
        //令牌
        if( config('maccms.wx_token') ){
            $this->token = config('maccms.wx_token');
        }
        
        //关注回复
        if( config('maccms.wx_follow') ){
            $this->follow = config('maccms.wx_follow');
        }
        
        //无结果回复
        if( config('maccms.wx_none') ){
            $this->none = config('maccms.wx_none');
        }
        
        /*认证
		if($this->checkSignature() == false){
			exit();
		}else{
			if(isset($_GET['echostr'])){
				exit($_GET['echostr']);
			}
		}*/
	}
	
	// 微信服务器推送
	public function index()
    {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		//$postStr = file_get_contents("php://input");
		if (!empty($postStr)){
			$postObj = simplexml_load_string($postStr);
			$RX_TYPE = trim($postObj->MsgType);
			switch($RX_TYPE){
			case "event": //接收事件
				$resultStr = $this->handleevent($postObj);
				break;
			case "text": //接收文本消息
				$resultStr = $this->handletext($postObj);
				break;	
			default:
				$resultStr = "Unknow msg type: ".$RX_TYPE;
				break;
			}
			echo $resultStr;
		}else {
			echo('success');
		}
	}
	
	// 接收事件推送
	private function handleevent($object){
		switch($object->Event){
			case "subscribe":
				$content = $this->response_text($object, $this->follow);//关注
				break;
			case "unsubscribe":
				$content = $this->response_text($object, "取消关注");
				break;
			case "LOCATION":
				$content = $this->response_text($object, "地理位置");
				break;
			case "CLICK":
				$content = $this->response_text($object, "自定义菜单".$object->EventKey);
				break;
			case "VIEW":
				$content = $this->response_text($object, "自定义菜单".$object->EventKey);
				break;
			case "SCAN":
				$content = $this->event_scan($object);
				break;
			default:
				$content = $this->response_text($object, "待开发事件");
				break;
		}
		return $content;
	}
	
	// 接收文字消息
	private function handletext($object)
    {
    
		// 关键字定义
		$content = trim($object->Content);
        
		// 自定义关键词(图文水息)
        if( config('maccms.wx_keywords') ){
            $wx_item = json_decode(config('maccms.wx_keywords'),true);
            if( $keyword = DcArraySearch($item, ['title'=>$content]) ){
                return $this->response_news($object, $keyword);
            }
        }
        
		// 按关键字搜索（文本消息）
		if($data = $this->search($content)){
            $string = '《'.$content.'》相关的视频<br><br>';
            
			$string.= implode('<br><br>',$data);
            
			return $this->response_text($object, $string);
		}
        
		// 默认原样返回
		return $this->response_text($object, $this->none);	
	}
	
	/*-----------------------------------系统函数-----------------------------------*/
	// 被动回复文字信息格式
	private function response_text($object, $content){
		$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			</xml>";
		if(empty($content)){
			$content = "请先在网站后台配置相关回复信息。";
		}
		$content = str_replace("<br>",chr(13),$content);
		return sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
	}
	
	// 被动回复图文信息格式
	private function response_news($object, $array){
		$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>1</ArticleCount>
			<Articles>
			<item>
			<Title><![CDATA[%s]]></Title> 
			<Description><![CDATA[%s]]></Description>
			<PicUrl><![CDATA[%s]]></PicUrl>
			<Url><![CDATA[%s]]></Url>
			</item>
			</Articles>
			</xml>";
		return sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $array['title'], $array['content'], $array['picurl'], $array['url']);
	}
	
	// 微信接入认证
	private function checkSignature() {
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];		
		$tmpArr = array($this->token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
    
    //测试
    public function test()
    {
        $content = '刘德华';
        
        $data = $this->search($content);
        
        $string = '《'.$content.'》相关的视频<br><br>';
            
        $string.= implode('<br><br>',$data);

        return $this->response_text($object, $string);
    }
	
	//关键字搜索
	private function search($wd)
    {
        $limit = DcEmpty(config('maccms.wx_limit'), 5);//返回多少条结果
        
        $list = apiItem(['wd'=>$wd]);
        
        $item = array();
        
		foreach($list['item'] as $key=>$value){
        
            if( $key >= $limit ){
                break;
            }
            
            array_push($item, '<a href="'.config('maccms.wx_domain').playUrl($value['play_last']).'">《'.$value['vod_title'].'》</a>免费观看');
		}
        
        if( count($list['item']) > $limit){
            array_push($item, '<a href="'.config('maccms.wx_domain').DcUrl('maccms/search/index',['wd'=>$wd],'').'">查看更多...</a>');
        }
        
		return $item;
	}
}