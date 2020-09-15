<?php
namespace app\common\model;

use think\Model;

class UserMeta extends Model
{

    //修改器
    public function setUserMetaValueAttr($value, $data)
    {
        if(is_array($value)){
            return serialize($value);
        }
        return $value;
    }
    
    //获取器
    public function getUserMetaValueAttr($value, $data)
    {
        $value_ = unserialize($value);
        if($value_ === false){
            return $value;
        }
        return $value_;
    }
    
    //关联多对一
	public function user(){
		return $this->belongsTo('User','user_id');
	}
	
}