<?php
namespace app\maccms\validate;

use think\Validate;

//验证规则回调

class Category extends Validate
{
	
	protected $rule = [
		'term_name'        => 'require|unique_name',
        'term_api_tid'     => 'require',
	];
	
	protected $message = [
		'term_name.require'    => '{%term_name_require}',
        'term_api_tid.require' => '{%term_api_tid_require}',
	];
	
	//验证场景
	protected $scene = [
		'save'    => ['term_name','term_api_tid'],
		'update'  => ['term_name','term_api_tid'],
	];
    
    //队列名唯一验证
    protected function unique_name($value, $rule, $data, $field)
    {
        $where = [];
        $where['term_module']   = 'maccms';
        $where['term_controll'] = 'category';
        $where['term_parent']   = intval($data['term_parent']);;
        $where['term_name']     = $value;
        if($data['term_id']){
            $where['term_id']   = ['neq',$data['term_id']];
        }
        $info = DcDbGet('common/Term', $where, false);
        //无记录直接验证通过
        if(is_null($info)){
            return true;
        }
        return lang('tag_name_unique');
	}

}