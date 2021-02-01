<?php
namespace daicuo;

/**
 * 视频在线播放
 * @author laotan <271513820@qq.com>
 */
 
class Video
{
    //默认配置
    public $options = [
        'in'           =>true,//站内播放开关
        'ai'           =>'',//智能解析
        'type'         =>'',//播放器类型
        'url'          =>'',//播放地址
        'next'         =>'',//下一集播放地址
        'jump'         =>'',//下一集播放跳转
        'buffer'       =>'',//缓冲
        'pause'        =>'',//暂停
        'frontUrl'     =>'',//前贴片
        'frontTime'    =>0, //前贴片时长
        'endUrl'       =>'',//后贴片广告
        'endTime'      =>'',//后贴片时长
        'poster'       =>'',//封面图片
        'index'        =>0,//多个播放器时编号
        'advUnit'      =>'',//广告扩展配置
    ];

     //构造函数
     public function __construct($options = [])
     {
         $json = array();
         $json['in'] = DcBool(config('common.video_in'));
         $json['ai'] = config('common.video_ai');
         $json['buffer'] = config('common.video_buffer');
         $json['pause'] = config('common.video_pause');
         $json['frontUrl'] = config('common.video_frontUrl');
         $json['frontTime'] = config('common.video_frontTime');
         $json['endUrl'] = config('common.video_endUrl');
         $json['endTime'] = config('common.video_endTime');
         $json['advUnit'] = config('common.video_advUnit');
         $this->options = array_merge($this->options, $json);
         if($options){
             $this->options = array_merge($this->options, $options);
         }
    }

    /**
    * 调用云播放器
    * @param array $options 播放器参数
    * @return string
    */
    public function player($options = [], $functionName='daicuo.media.video();')
    {
        if($options){
            $this->options = array_merge($this->options, $options);
        }
        $this->options['ai'] = str_replace('[type]',$this->options['type'],$this->options['ai']);
        $this->options['element'] = '#DcPlayer';
        return '<div class="embed-responsive embed-responsive-'.DcEmpty(config('common.video_size'),'16by9').'" id="DcPlayer"></div><script>daicuo.media.init('.json_encode($this->options).');</script>';
    }
   
}