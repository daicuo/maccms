<?php
namespace app\common\model;

use think\Model;

class TermMap extends Model
{
    //相对关联反向获取队列信息
    public function term()
    {
		return $this->belongsTo('Term','term_id','term_id');
	}
    
    //相对关联反向获取内容信息
	public function info()
    {
		return $this->belongsTo('Info','detail_id','info_id');
	}
}