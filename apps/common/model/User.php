<?php
namespace app\common\model;

use think\Model;

class User extends Model
{
    //默认主键为自动识别
    //protected $pk = 'user_id';

	//开启自动写入时间戳
	protected $autoWriteTimestamp = false;
	
	//定义时间戳字段名
    protected $createTime = 'user_create_time';
    protected $updateTime = 'user_update_time';
	
	//数据自动完成
	protected $auto = [];
    protected $insert = ['user_create_ip','user_update_ip'];  
    protected $update = ['user_update_ip'];
    
    //获取器不存在的字段
    protected $append = ['user_status_text'];
    
    //修改器
	public function setUserSlugAttr($value, $data)
    {
        if( empty($value) ){
            $value = \daicuo\Pinyin::get(trim($data['user_name']));
        }
        return DcSlugUnique('user', $value, intval($data['user_id']));
    }
	
	//修改器
	public function setUserPassAttr($value, $data)
    {
        return md5(trim($value));
    }
    
    public function setUserCreateTimeAttr($value, $data)
    {
        if( empty($value) ){
            return time();
        }
        return strtotime($data['user_create_time']);
    }
    
    public function setUserUpdateTimeAttr($value, $data)
    {
        if( empty($value) ){
            return time();
        }
        return strtotime($data['user_update_time']);
    }

	public function setUserCreateIpAttr($value, $data)
    {
        if( empty($value) ){
            return request()->ip();
        }
        return $data['user_create_ip'];
    }
	
	public function setUserUpdateIpAttr($value, $data)
    {
        if( empty($value) ){
            return request()->ip();
        }
        return $data['user_update_ip'];
    }
    
    //获取器增加不存在的字段
    public function getUserStatusTextAttr($value, $data)
    {
        $status = ['normal'=>lang('normal'),'hidden'=>lang('hidden'),'private'=>lang('private'),'public'=>lang('public')];
        return $status[$data['user_status']];
    }
    
    //关联一对多
	public function userMeta(){
		return $this->hasMany('UserMeta','user_id')->field('user_meta_id,user_meta_key,user_meta_value,user_id');
	}
	
}