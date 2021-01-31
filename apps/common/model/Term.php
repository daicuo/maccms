<?php
namespace app\common\model;

use think\Model;

class Term extends Model{

    //数据自动完成
	protected $auto = [];
    
    protected $insert = [];
    
    protected $update = [];
    
    //获取器不存在的字段
    protected $append = ['term_status_text'];

    //修改器
	public function setTermSlugAttr($value, $data)
    {
        if( empty($value) ){
            $value = \daicuo\Pinyin::get(trim($data['term_name']));
        }
        //别名唯一值处理
        return DcSlugUnique('term', $value, intval($data['term_id']));
    }
    
    //获取器增加不存在的字段
    public function getTermStatusTextAttr($value, $data)
    {
        $status = ['normal'=>lang('normal'),'hidden'=>lang('hidden')];
        return $status[$data['term_status']];
    }
	
	//关联一对一
	public function termMuch(){
		return $this->hasOne('TermMuch','term_id')->field('*')->bind('term_much_id,term_much_type,term_much_info,term_much_parent,term_much_count');
	}
	
	//关联一对多
	public function termMeta(){
		return $this->hasMany('TermMeta','term_id')->field('term_meta_id,term_meta_key,term_meta_value,term_id');
	}
	
}