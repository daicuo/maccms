<?php
namespace daicuo;

class Upload
{
   /**
     * @var string 错误信息
     */
    private static $error = '';

    /*构造函数
    public function __construct($options = [])
    {
     
    }*/
    
    /**
     * 获取错误信息（支持多语言）
     * @return string
     */
    public function getError()
    {
        return self::$error;
    }
    
    /**
     * 批量上传附件
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表 
     * @return null|obj 成功时返回obj
     */
    public static function save_all($files)
    {
        if(!$files){
            return ['code'=>0,'msg'=>lang('mustIn'),'data'=>'','item'=>''];
        }
        //附件转为数组
        if(!is_array($files)){
            $files = [$files];
        }
        //循环处理上传
        $item = [];
        foreach($files as $key=>$file){
            $item[$key] = self::get(self::save($file));
        }
        //返回单个附件
        if( count($item) < 2 ){
            if($item[0]['error']){
                return ['code'=>0,'msg'=>$item[0]['error'],'data'=>$item[0],'item'=>''];
            }else{
                return ['code'=>1,'msg'=>'','data'=>$item[0],'item'=>''];
            }
        }
        //返回多个附件
        return ['code'=>1,'msg'=>'','data'=>'','item'=>$item];
    }
    
    /**
     * 批量删除附件
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表 
     * @return null|obj 成功时返回obj
     */
    public static function delete_all($attachments)
    {
        if(!$attachments){
            return ['code'=>0,'msg'=>lang('mustIn'),'data'=>''];
        }
        if(is_string($attachments)){
            $attachments = explode(',',$attachments);
        }
        //循环处理上传
        $datas = [];
        foreach($attachments as $key=>$attachment){
            $datas[$attachment] = self::delete($attachment);
        }
        return ['code'=>1,'msg'=>'','datas'=>$datas];
    }
    
    /**
     * 创建一个新附件
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表 
     * @return null|obj 成功时返回obj
     */
    public static function save($file)
    {
        //不是TP的file对象直接退出
        if(!is_object($file)){
            self::$error = lang('mustIn');
            return false;
        }
        //钩子传参定义
        $params = array();
        $params['file'] = $file;
        $params['result'] = false;
        unset($file);
        //预埋钩子
        \think\Hook::listen('upload_save_before', $params);
        //添加数据
        if( false == $params['result'] ){
            //验证规则
            $validate = array();
            if( config('common.upload_max_size') ){
                $validate['size'] = self::byte(config('common.upload_max_size'));
            }
            if( config('common.upload_file_ext') ){
                $validate['ext'] = config('common.upload_file_ext');
            }
            if( config('common.upload_mime_type') ){
                $validate['type'] = config('common.upload_mime_type');
            }
            //保存路径
            $path = ROOT_PATH . ltrim(config('common.upload_path'), '/');
            //保存文件名
            $save_name = self::buildSaveName($params['file']);
            //保存文件
            $params['result'] = $params['file']->rule(config('common.upload_save_rule'))->validate($validate)->move($path, $save_name, true);
            //出错信息
            if($params['result'] == false){
                self::$error = $params['file']->getError();
            }
        }
        //预埋钩子
        \think\Hook::listen('upload_save_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 删除一个文件
     * @param obj $attachment 文件完整保存路径
     * @return array
     */
    public function delete($attachment)
    {
        //过滤路径
        $attachment = str_replace(['..','.'], '', urldecode(trim($attachment)));
        //钩子传参定义
        $params = array();
        $params['attachment'] = $attachment;
        $params['result'] = false;
        unset($result);
        //预埋钩子
        \think\Hook::listen('upload_delete_before', $params);
        //数据处理
        if( false == $params['result'] ){
            $file = new \files\File();
            $path = './'.ltrim(config('common.upload_path'), '/');
            $params['result'] = $file->f_delete($path.'/'.$attachment);
        }
        //预埋钩子
        \think\Hook::listen('upload_delete_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 获取保存后的文件信息
     * @param obj $file TP上传后返回的文件对象
     * @return array
     */
    public function get($file)
    {
        //数组格式直接返回
        if(is_array($file)){
            return $file;
        }
        //钩子传参定义
        $params = array();
        $params['file'] = $file;
        $params['result'] = false;
        unset($result);
        //预埋钩子
        \think\Hook::listen('upload_get_before', $params);
        //数据处理
        if( false == $params['result'] ){
            if($params['file']){
                $params['result']['file_name'] = $params['file']->getFilename();//文件保存名
                $params['result']['old_name'] = $params['file']->getInfo('name');//文件原始名
                $params['result']['attachment'] = str_replace("\\",'/',$params['file']->getSaveName());//文件保存路径
                $params['result']['url'] = DcUrlAttachment($params['result']['attachment']);//访问链接
                $params['result']['ext'] = $params['file']->getExtension();
                $params['result']['type'] = $params['file']->getInfo('type');
                $params['result']['size'] = $params['file']->getInfo('size');
                $params['result']['error'] = '';
            }else{
                $params['result']['file_name'] = '';
                $params['result']['old_name'] = '';
                $params['result']['attachment'] = '';
                $params['result']['url'] = '';
                $params['result']['ext'] = '';
                $params['result']['type'] = '';
                $params['result']['size'] = '';
                $params['result']['error'] = self::$error;
            }
        }
        //预埋钩子
        \think\Hook::listen('upload_get_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 转换字节
     * @param array $data 写入数据（一维数组） 
     * @param string $max_size 待转化的字符，如:10mb
     * @return int 字节数
     */
    private static function byte($max_size)
    {
        $max_size = strtolower($max_size);
        preg_match('/([0-9\.]+)(\w+)/', $max_size, $matches);
        $size = $matches ? $matches[1] : $max_size;
        $type = $matches ? strtolower($matches[2]) : 'b';
        $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
        return (int)($size * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0));
    }
    
    /**
     * 自否自动生成文件名
     * @param obj $file TP的文件类
     * @return bool|string true为自动生成,字符串为自定义文件名
     */
    private static function buildSaveName($file)
    {
        //包含/时为自定义文件名
        if($file && count(explode('/',config('common.upload_save_rule'))) > 1){
            $filename = $file->getInfo('name');
            $md5 = md5_file($file->getInfo('tmp_name'));
            $replaceArr = [
                '{year}'     => date("Y"),
                '{mon}'      => date("m"),
                '{day}'      => date("d"),
                '{hour}'     => date("H"),
                '{min}'      => date("i"),
                '{sec}'      => date("s"),
                '{filename}' => substr($filename, 0, 100),
                '{filemd5}'  => $md5,
            ];
            return str_replace(array_keys($replaceArr), array_values($replaceArr), config('common.upload_save_rule'));
        }
        //自动生成条件
        return true;
    }
    
    /**
     * 析构方法，用于关闭文件资源
    public function __destruct(){
        
    }
    */
}