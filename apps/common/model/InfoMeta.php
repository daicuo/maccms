<?php
namespace app\common\model;

use think\Model;

class InfoMeta extends Model
{

    //修改器
    public function setInfoMetaValueAttr($value, $data)
    {
        if(is_array($value)){
            return serialize($value);
        }
        return $value;
    }
    
    //获取器
    public function getInfoMetaValueAttr($value, $data)
    {
        $value_ = unserialize($value);
        if($value_ === false){
            return $value;
        }
        return $value_;
    }
    
    //关联多对一
	public function info(){
		return $this->belongsTo('Info', 'info_id');
	}
}