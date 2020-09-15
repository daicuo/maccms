<?php
namespace app\common\model;

use think\Model;

class TermMeta extends Model{

	public function term(){
		return $this->belongsTo('Term','term_id','term_id')->bind('term_name,term_slug,term_module,term_group,term_order');
	}
    
    public function termMuch(){
		return $this->belongsTo('TermMuch','term_id','term_id')->bind('term_much_id,term_much_type,term_much_info,term_much_parent,term_much_count');
	}
    
}