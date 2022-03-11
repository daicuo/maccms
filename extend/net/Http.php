<?php
namespace net;

use files\Dir;

class Http {

    protected static $error = 'error';

    protected static $userAgent = [
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 OPR/26.0.1656.60',
        'Opera/8.0 (Windows NT 5.1; U; en)',
        'Mozilla/5.0 (Windows NT 5.1; U; en; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 9.50',
        'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 9.50',
        'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0',
        'Mozilla/5.0 (X11; U; Linux x86_64; zh-CN; rv:1.9.2.10) Gecko/20100922 Ubuntu/10.10 (maverick) Firefox/3.6.10',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11',
        'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.133 Safari/534.16',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.11 TaoBrowser/2.0 Safari/536.11',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.71 Safari/537.1 LBBROWSER',
        'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; LBBROWSER)',
        'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E; LBBROWSER)',
        'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; QQBrowser/7.0.3698.400)',
        'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E)',
        'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.84 Safari/535.11 SE 2.X MetaSr 1.0',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.4000 Chrome/30.0.1599.101 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 UBrowser/4.0.3214.0 Safari/537.36',
    ];
    
    /**
     * GET请求
     * @param string $url 请求的地址
     * @param mixed $params 传递的参数
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认30秒
     * @param mixed $options CURL的参数
     * @return array|string
     */
    public static function get($url, $params = '', $header = [], $timeout = 30, $options = [])
    {
        return self::send($url, $params, 'GET', $header, $timeout, $options);
    }

    /**
     * POST请求
     * @param string $url 请求的地址
     * @param mixed $params 传递的参数
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认30秒
     * @param mixed $options CURL的参数
     * @return array|string
     */
    public static function post($url, $params = '', $header = [], $timeout = 30, $options = [])
    {
        return self::send($url, $params, 'POST', $header, $timeout, $options);
    }

    /**
     * DELETE请求
     * @param string $url 请求的地址
     * @param mixed $params 传递的参数
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认30秒
     * @param mixed $options CURL的参数
     * @return array|string
     */
    public static function delete($url, $params = '', $header = [], $timeout = 30, $options = [])
    {
        return self::send($url, $params, 'DELETE', $header, $timeout, $options);
    }

    /**
     * PUT请求
     * @param string $url 请求的地址
     * @param mixed $params 传递的参数
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认30秒
     * @param mixed $options CURL的参数
     * @return array|string
     */
    public static function put($url, $params = '', $header = [], $timeout = 30, $options = [])
    {
        return self::send($url, $params, 'PUT', $header, $timeout, $options);
    }

    /**
     * 下载远程文件
     * @param string $url 请求的地址
     * @param string $savePath 本地保存完整路径
     * @param mixed $params 传递的参数
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认3600秒
     * @return bool|string
     */
    public static function downLoad($url, $savePath, $params = '', $header = [], $timeout = 3600)
    {
        if (!is_dir(dirname($savePath))) {
            if( !Dir::create(dirname($savePath)) ){
                self::$error = lang('apply_save_failed');//无创建目录权限(777)
                return false;
            }
        }
        
        $ch = curl_init();
        $fp = fopen($savePath, 'wb');

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header ? : ['Expect:']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 64000);
        //POST提交
        if($params){
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $res        = curl_exec($ch);
        $curlInfo   = curl_getinfo($ch);

        //连接服务器错误
        if (curl_errno($ch) || $curlInfo['http_code'] != 200) {
        
            curl_error($ch);
            
            @unlink($savePath);
            
            self::$error = $curlInfo['content_type'];//$curlInfo['http_code']http错误码
            
            return false;
        } else {
            curl_close($ch);
        }

        fclose($fp);

        return $savePath;
    }
    
    /**
     * 发送文件到客户端
     * @param string $file
     * @param bool   $delaftersend
     * @param bool   $exitaftersend
     */
    public static function sendToBrowser($file, $delaftersend = true, $exitaftersend = true)
    {
        if (file_exists($file) && is_readable($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment;filename = ' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check = 0, pre-check = 0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            if ($delaftersend) {
                unlink($file);
            }
            if ($exitaftersend) {
                exit;
            }
        }
    }
    
    /**
     * 显示HTTP Header 信息
     * @return string
     */
    function getHeaderInfo($header='',$echo=true) 
    {
        ob_start();
        $headers   	= getallheaders();
        if(!empty($header)) {
            $info 	= $headers[$header];
            echo($header.':'.$info."\n"); ;
        }else {
            foreach($headers as $key=>$val) {
                echo("$key:$val\n");
            }
        }
        $output 	= ob_get_clean();
        if ($echo) {
            echo (nl2br($output));
        }else {
            return $output;
        }

    }

    /**
     * HTTP Protocol defined status codes
     * @param int $num
     */
	function sendHttpStatus($code) 
    {
		static $_status = array(
			// Informational 1xx
			100 => 'Continue',
			101 => 'Switching Protocols',

			// Success 2xx
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',

			// Redirection 3xx
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',  // 1.1
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			// 306 is deprecated but reserved
			307 => 'Temporary Redirect',

			// Client Error 4xx
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',

			// Server Error 5xx
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			509 => 'Bandwidth Limit Exceeded'
		);
		if(isset($_status[$code])) {
			header('HTTP/1.1 '.$code.' '.$_status[$code]);
		}
	}
    
    /**
     * 返回错误信息
     * @return string
     */
    public function getError()
    {
        return self::$error;
    }

    /**
     * CURL发送Request请求,支持GET、POST、PUT、DELETE
     * @param string $url 请求的地址
     * @param mixed $params 传递的参数
     * @param string $method 请求的方法
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认30秒
     * @param mixed $options CURL的参数
     * @return array|string
     */
    private static function send($url, $params = '', $method = 'GET', $header = [], $timeout = 30, $options = [])
    {
        $userAgent = self::$userAgent[array_rand(self::$userAgent, 1)];
        $ch = curl_init();
        $opt                            = [];
        $opt[CURLOPT_USERAGENT]         = $userAgent;
        $opt[CURLOPT_CONNECTTIMEOUT]    = $timeout;
        $opt[CURLOPT_TIMEOUT]           = $timeout;
        $opt[CURLOPT_RETURNTRANSFER]    = true;
        $opt[CURLOPT_HTTPHEADER]        = $header ? : ['Expect:'];
        $opt[CURLOPT_FOLLOWLOCATION]    = true;

        if (substr($url, 0, 8) == 'https://') {
            $opt[CURLOPT_SSL_VERIFYPEER] = false;
            $opt[CURLOPT_SSL_VERIFYHOST] = 2;
        }

        if (is_array($params)) {
            $params = http_build_query($params);
        }

        switch (strtoupper($method)) {
            case 'GET':
                $extStr             = (strpos($url, '?') !== false) ? '&' : '?';
                $opt[CURLOPT_URL]   = $url . (($params) ? $extStr . $params : '');
                break;

            case 'POST':
                $opt[CURLOPT_POST]          = true;
                $opt[CURLOPT_POSTFIELDS]    = $params;
                $opt[CURLOPT_URL]           = $url;
                break;

            case 'PUT':
                $opt[CURLOPT_CUSTOMREQUEST] = 'PUT';
                $opt[CURLOPT_POSTFIELDS]    = $params;
                $opt[CURLOPT_URL]           = $url;
                break;

            case 'DELETE':
                $opt[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                $opt[CURLOPT_POSTFIELDS]    = $params;
                $opt[CURLOPT_URL]           = $url;
                break;

            default:
                return ['error' => 0, 'msg' => '请求的方法不存在', 'info' => []];
                break;
        }

        curl_setopt_array($ch, (array) $opt + $options);
        $result = curl_exec($ch);
        $error  = curl_error($ch);

        if ($result == false || !empty($error)) {
            $errno  = curl_errno($ch);
            $info   = curl_getinfo($ch);
            curl_close($ch);
            return [
                'errno' => $errno,
                'msg'   => $error,
                'info'  => $info,
            ];
        }

        curl_close($ch);

        return $result;
    }
}
