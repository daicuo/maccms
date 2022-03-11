<?php
namespace app\common\model;

use think\Model;

class Info extends Model
{

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
        $status = ['normal'=>lang('normal'),'hidden'=>lang('hidden'),'private'=>lang('private'),'public'=>lang('public')];
        return $status[$data['info_status']];
    }
	
	//一对多
	public function infoMeta()
    {
		return $this->hasMany('InfoMeta','info_id')->field('info_meta_id,info_meta_key,info_meta_value,info_id');
	}
    
    //一对一 hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
	public function user()
    {
		return $this->hasOne('User','user_id','info_user_id')->bind('user_id,user_name,user_nice_name,user_email,user_mobile,user_status,user_slug,user_views,user_hits');
	}
    
    //一对多
	public function userMeta()
    {
		return $this->hasMany('UserMeta','user_id','info_user_id')->field('user_meta_id,user_meta_key,user_meta_value,user_id');
	}
    
    //一对多
	public function termMap()
    {
		return $this->hasMany('TermMap','detail_id')->field('detail_id,term_id');
	}
    
    //多对多 belongsToMany('关联模型名','中间表名','外键名','当前模型关联键名',['模型别名定义'])
    public function term()
    {
        return $this->belongsToMany('Term','TermMap','term_id','detail_id');
	}
}