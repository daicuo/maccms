<?php
namespace app\common\model;

use think\Model;

class Log extends Model
{

    //开启自动写入时间戳
	protected $autoWriteTimestamp = true;

    //定义时间戳字段名
    protected $createTime = 'log_create_time';
    
    // 关闭自动写入update_time字段
    protected $updateTime = false;

    //数据自动完成
	protected $auto = [];
    protected $insert = [];
    protected $update = [];
    
    //相对的关联一对一
    public function logUser(){
		return $this->belongsTo('app\common\model\User','log_user_id')->field('user_id,user_name,user_status');
	}

    //相对的关联一对一
    public function logInfo(){
		return $this->belongsTo('app\common\model\Info','log_info_id')->field('info_id,info_name');
	}
}