<?php
namespace app\index\behavior;

use think\Controller;

class Hook extends Controller
{
    //系统初始化
    public function hookBaseInit(&$params){
        config('custom_fields.term_meta', array_merge(config('custom_fields.term_meta'), ['term_hook']) );
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