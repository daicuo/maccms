<?php
namespace app\common\model;

use think\Model;

class TermMap extends Model{
    
    public function termMuch(){
		return $this->belongsTo('TermMuch','term_much_id','term_much_id')->bind('term_much_id,term_much_type,term_much_info,term_much_parent,term_much_count');
	}
    
}