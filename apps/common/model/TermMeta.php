<?php
namespace app\common\model;

use think\Model;

class TermMeta extends Model
{

    //修改器
    public function setTermMetaValueAttr($value, $data)
    {
        if(is_array($value)){
            return serialize($value);
        }
        return $value;
    }
    
    //获取器
    public function getTermMetaValueAttr($value, $data)
    {
        $value_ = unserialize($value);
        if($value_ === false){
            return $value;
        }
        return $value_;
    }

    //相对关联反向获取信息
	public function term(){
		return $this->belongsTo('Term','term_id','term_id');
	}
    
}