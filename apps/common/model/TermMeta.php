<?php
namespace app\common\model;

use think\Model;

class TermMeta extends Model{

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

    //关联多对一
	public function term(){
		return $this->belongsTo('Term','term_id','term_id')->bind('term_name,term_slug,term_module,term_group,term_order');
	}
    
    //关联多对一
    public function termMuch(){
		return $this->belongsTo('TermMuch','term_id','term_id')->bind('term_much_id,term_much_type,term_much_info,term_much_parent,term_much_count');
	}
    
}