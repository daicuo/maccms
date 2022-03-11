<?php
namespace daicuo;

class Service
{
    private static $error = '';

    // 下载路径
    private static $path = './datas/';
    
    // 服务端URL
    private static $api_url = 'http://hao.daicuo.cc/1.7';
    
    /**
     * 架构函数
     * @param string $path 临时目录路径
     */
    public function __construct($path = './datas/temp/') 
    {
        self::$path = $path;
    }
    
    /**
     * 从开放平台下载应用
     * @param array $args 参数['name'=>'demo','version'=>'1.0.2','event'=>'install|update']
     * @return bool|fileName
     */
    public function applyDownLoad($args)
    {
        // 应用名称
        if( !$args['module'] ){
            self::$error = 'daicuo_module_empty';
            return false;
        }
        
        //ApiKey
        if( !config('common.site_token')|| (config('common.site_token')=='6fc79c072a4500b749b5b11b9f969c8c') ){
            self::$error = 'daicuo_token_empty';
            return false;
        }
        
        //临时文件保存名称
        $saveFile = self::$path.time().'.zip';
        
        //开始下载
        $http = new \net\Http();
        $header = ['DAICUO:1','TOKEN:'.config('common.site_token')];
        $args['daicuoVersion'] = config('daicuo.version');
        $status = $http->downLoad(self::$api_url.'/apply/?'.http_build_query($args), $saveFile, '', $header);
        
        //检测是否下载成功
        if(!$status){
        
            self::$error = '<a class="text-purple" href="../union/index" target="_blank">'.$http->getError().'</a>';
            
            return false;
        }

        //验证下载文件是否有效
        if( !filesize($saveFile) ){
        
            @unlink($saveFile);
            
            self::$error = 'apply_download_failed';
            
            return false;
        }
        
        return $saveFile;
    }
    
    /**
     * 获取应用下载链接
     * @param array $query
     * @return bool
     */
    public function applyDownUrl($query)
    {
        $query['event'] = 'browser';
        $query['token'] = config('common.site_token');
        $query['daicuoVersion'] = config('daicuo.version');
        return self::$api_url.'/apply/?'.http_build_query($query);
    }
    
    /**
     * 获取开放平台应用列表
     * @param array $query
     * @return bool|array
     */
    public function apiData($query)
    {
        $query['daicuoVersion'] = config('daicuo.version');
        return json_decode(DcCurl('auto', 10, self::$api_url.'/store/?'.http_build_query($query)),true);
    }
    
    /**
     * 获取应用升级信息
     * @param array $query
     * @return bool|array
     */
    public function apiUpgrade($query)
    {
        $query['daicuoVersion'] = config('daicuo.version');
        return json_decode(DcCurl('auto', 10, self::$api_url.'/version/?'.http_build_query($query)),true);
    }
    
    /**
     * 获取开放平台入口
     * @return url
     */
    public function apiUrl()
    {
        return self::$api_url;
    }
    
    /**
     * 获取错误信息
     * @return url
     */
    public function getError()
    {
        return self::$error;
    }
}