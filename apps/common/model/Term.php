<?php
namespace app\common\model;

use think\Model;

class Term extends Model
{

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
        $status = ['normal'=>lang('normal'),'hidden'=>lang('hidden'),'private'=>lang('private'),'public'=>lang('public')];
        return $status[$data['term_status']];
    }
	
	//一对多hasMany('关联模型名','外键名','主键名',['模型别名定义']);
	public function termMeta(){
		return $this->hasMany('TermMeta','term_id')->field('term_meta_id,term_meta_key,term_meta_value,term_id');
	}
    
    //一对多hasMany('关联模型名','外键名','主键名',['模型别名定义']);
    public function termMap()
    {
        return $this->hasMany('TermMap','term_id')->field('detail_id,term_id');
	}
}