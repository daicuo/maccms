<?php
namespace app\common\model;

use think\Model;

use think\Error;

class TermMuch extends Model{

	public function term(){
		return $this->belongsTo('Term','term_id');
	}
    
    public function termMeta(){
		return $this->belongsTo('TermMeta','term_id');
	}
    
    public function termMap(){
		return $this->hasMany('TermMap','term_much_id');
	}
    
}