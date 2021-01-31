<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Weixin extends Front{
	
	private $token='NxcbKZdhLtKks12XIRdfpUZ';
	
	//private $follow='你好，欢迎关注【佳琳小剧场】<br><br>发送（电影名字、主演名字）就可以免费看啦！<br><br>如能帮助到您，请推荐给好朋友，谢谢！';
  
    private $follow='你好，欢迎关注<br><br>发送（电影名字、主演名字）就可以免费看啦！<br><br>喜欢网购的朋友，试一试发送【优惠券】有惊喜!';
	
	private $jiexi = 'https://api.sigujx.com/?url=';
	
	private $item = array(
        'keyword' => 
            array (
              0 => '福利电影',
              1 => '黄色电影',
              2 => '福利',
            ),
        'title' => 
            array (
              0 => '福利电影免费看',
              1 => '黄色电影免费看',
              2 => '福利电影免费看',
            ),
        'content' => 
            array (
              0 => '老司机你好，福利电影网址如果被微信屏蔽了，请点击右上角...然后选择使用（手机浏览器）打开就可以观看了。',
              1 => '老司机你好，福利电影网址如果被微信屏蔽了，请点击右上角...然后选择使用（手机浏览器）打开就可以观看了。',
              2 => '老司机你好，福利电影网址如果被微信屏蔽了，请点击右上角...然后选择使用（手机浏览器）打开就可以观看了。',
            ),
        'pic' => 
            array (
              0 => 'https://upload-images.jianshu.io/upload_images/10998030-6d4bcf8ee93d47e6.png',
              1 => 'https://upload-images.jianshu.io/upload_images/10998030-6d4bcf8ee93d47e6.png',
              2 => 'https://upload-images.jianshu.io/upload_images/10998030-6d4bcf8ee93d47e6.png',
            ),
        'link' => 
            array (
              0 => 'https://weixin.xxgxw.com/?vip1',
              1 => 'https://weixin.xxgxw.com/?vip2',
              2 => 'https://weixin.xxgxw.com/?vip3',
            ),
	);
	
	// 微信消息真实性认证
	public function _initialize(){
		if($this->checkSignature() == false){
			exit();
		}else{
			if(isset($_GET['echostr'])){
				exit($_GET['echostr']);
			}
		}
	}
	
	// 微信服务器推送
	public function index(){
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
	private function handletext($object){
		// 关键字定义
		$content = trim($object->Content);
        // 是否返回淘客京客
        if( in_array($content,array('优惠券','折扣','淘宝')) ){
          return $this->response_text($object, $this->jduinion());
          //return $this->response_text($object, $this->taobaopwd());
        }
		// 自定义关键词
		$wx_item = $this->item;
		if($wx_item){
			$key = array_search($content,$wx_item['keyword']);
			if($key !== false){
				$array = array();
				$array['title'] = $wx_item['title'][$key];
				$array['content'] = $wx_item['content'][$key];
				$array['picurl'] = $wx_item['pic'][$key];
				$array['url'] = $wx_item['link'][$key];
				return $this->response_news($object, $array);
			}
		}
		// 字符串长度
		preg_match_all("/./us", $content, $match);
		if(count($match[0]) > 8){
			return $this->response_text($object, "请输入2~8个字符搜索!");
		}
		// 按关键字搜索（文本消息）
		if($data = $this->search($content)){
			//$string = '《'.$content.'》相关的视频，更多搜索结果请点击<a href="http://weixin.xxgxw.com/search?wd='.$content.'">^这里^</a><br><br>';
            //$string = $this->jduinion().'<br><br>';
            $string = '《'.$content.'》相关的视频<br><br>';
			$string.= implode('<br><br>',$data);
			return $this->response_text($object, $string);
		}
		// 默认原样返回
		return $this->response_text($object, '对不起，没有搜索到，请重新输入关键词！');	
		/*// 按关键字搜索（图文消息）
		if($data = $this->search($content)){
			$array = array();
			$array['title'] = $data[0]["vod_name"];
			$array['content'] = "为您找到一个关于（".$content."）的视频，可免费观看，请点击浏览...";
			$array['picurl'] = 'https://upload-images.jianshu.io/upload_images/10998030-6d4bcf8ee93d47e6.png';
			$array['url'] =  'http://www.yy091.com/'.$data[0]["link"].'?vip';
			//多条结果搜索页
			if( count($data) > 1){
				$array['title'] = "搜索→ ".$content;
				$array['content'] = "为您找到多个包含（".$content."）的视频，可免费观看，请点击浏览...";
				$array['url'] =  'http://www.yy091.com/index.php?g=home&m=vod&a=search&ch=vip&wd='.urlencode($content);
			}
			return $this->response_news($object, $array);
		}*/
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
	
	// 被动回复多图文格式
	private function response_item($object, $xml, $limit){
		$textTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<ArticleCount>".$limit."</ArticleCount>
		<Articles>%s</Articles>
		</xml>";
		return sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $xml);
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
	
	public function test(){
		$content = '李沁';
		$data = $this->search($content);
		$string = '已为您找到与（'.$content.'）相关的多个视频，请点击浏览......<br>';
		$string.= implode('<br>',$data);
		return $this->response_text($object, $string);
	}
	
	// 关键字搜索
	private function search($keyword){
        $json = DcCurl('iphone', 10, 'http://hao125.daicuo.cc/index.php?g=plus&m=api&a=json&limit=15&wd='.urldecode($keyword));
		$json = json_decode($json, true);
		$item = array();
		foreach($json['data'] as $key=>$value){
            array_push($item, '<a href="http://weixin.xxgxw.com/play/'.$value['vod_cid'].'/'.$value['vod_id'].'/1/kkm3u8">《'.$value['vod_name'].'》</a>免费观看');
		}
        array_push($item, '①、<a href="http://weixin.xxgxw.com/search?wd='.$keyword.'">更多搜索结果请点击^这里^</a>');
        array_push($item, '②、<a href="https://u.jd.com/tzcl9Gp">~~京东秒杀爆品 限时抢购</a>');
        array_push($item, '③、<a href="https://u.jd.com/tOcUBMd">~~京东实时热销 超火榜单</a>');
        array_push($item, '④、<a href="https://u.jd.com/tAclhCQ">~~京东大额优惠券 买到赚到</a>');
		return $item;
	}
	
	// 片名搜索
	private function searchFeiFei($keyword){
		$json = DcCurl('auto', 10, 'http://hao125.daicuo.cc/index.php?g=home&m=search&a=api&sid=1&limit=2&wd='.$keyword);
		$json = json_decode($json,true);
		return $json["data"];
	} 
  
    // 京东推广
    private function jduinion(){
        return '<a href="https://u.jd.com/9X2wnV">~~京东限量大额优惠券~~</a>';
    }
  
    // 淘口令推广
    private function taobaopwd(){
        $html = file_get_contents('http://code.qxwk.net/jquery.plus/taobao/wx.php');
        return 'fu植这行话'.$html.'至网购APP【大额券折扣榜】';
    }
}