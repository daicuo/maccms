<?php
namespace app\index\behavior;

use think\Controller;

class Hook extends Controller
{
    //系统初始化
    public function hookAdminInit(&$params){
        //自定义表单字段需要保存至数据库
        $fields = config('common.custom_fields');
        $fields['term_meta'] = array_merge($fields['term_meta'],['term_hook']);
        config('common.custom_fields', $fields);
    }
    
    // 表单生成
    public function hookBuildForm(&$params)
    {
        //追加表单字段
        if(strpos($params['action'],'category/')){
            $params['items'] = array_merge($params['items'], [
                [
                    'type'=>'textarea',
                    'name'=>'term_hook',
                    'id'=>'term_hook',
                    'title'=>lang('term_hook'),
                    'placeholder'=>lang('term_hook_placeholder'),
                    'tips'=>'',
                    'value'=>$params['data']['term_hook'],
                    'rows'=>4,
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    //'autocomplete'=>'on',
                    //'maxlength'=>'200',
                    'class'=>'row form-group',
                    'class_left'=>'col-12',
                    'class_right'=>'col-12',
                    'class_right_control'=>'',
                    'class_right_tips'=>'',
                ],
            ]);
        }
    }

}