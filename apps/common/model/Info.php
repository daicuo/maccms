<?php
namespace app\common\model;

use think\Model;

class Info extends Model{

    //开启自动写入时间戳
	protected $autoWriteTimestamp = true;

    //定义时间戳字段名
    protected $createTime = 'info_create_time';
    protected $updateTime = 'info_update_time';

    //数据自动完成
	protected $auto = [];
    protected $insert = [];
    protected $update = [];
    
    //获取器不存在的字段
    protected $append = ['info_status_text'];

    //修改器
	public function setInfoSlugAttr($value, $data)
    {
        if( empty($value) ){
            $value = \daicuo\Pinyin::get(trim($data['info_name']));
        }
        //别名唯一值处理
        return DcSlugUnique('info', $value, intval($data['info_id']));
    }
    
    //获取器增加不存在的字段
    public function getInfoStatusTextAttr($value, $data)
    {
        $status = ['normal'=>lang('normal'),'hidden'=>lang('hidden')];
        return $status[$data['info_status']];
    }
	
	//关联一对多
	public function infoMeta(){
		return $this->hasMany('InfoMeta','info_id')->field('info_meta_id,info_meta_key,info_meta_value,info_id');
	}
    
    //关联一对多
	public function termMap(){
		return $this->hasMany('TermMap','detail_id')->field('detail_id,term_much_id');
	}
    
    //关联多对多
	public function termMuch(){
		return $this->belongsToMany('TermMuch','TermMap','term_much_id','detail_id');
	}
	
}