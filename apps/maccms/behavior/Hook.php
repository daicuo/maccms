<?php
namespace app\maccms\behavior;

use think\Controller;

class Hook extends Controller
{
    // 后台首页
    public function adminIndexHeader(&$params, $extra)
    {
        echo( DcCurl('auto','2','http://hao.daicuo.cc/maccms/welcome/?host='.input('server.HTTP_HOST')) );
    }
    
    // 表单生成
    public function formBuild(&$params)
    {
        //追加表单字段
        if( in_array($params['name'],['category_create','category_edit','tag_create','tag_edit']) ){
            $params['items'] = array_merge($params['items'], [
                [
                    'type'=>'text',
                    'name'=>'term_api_url',
                    'id'=>'term_api_url',
                    'title'=>lang('term_api_url'),
                    'placeholder'=>lang('term_api_url_placeholder'),
                    'tips'=>'',
                    'value'=>$params['data']['term_api_url'],
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'autocomplete'=>'on',
                    'maxlength'=>'200',
                    'class'=>'row form-group',
                    'class_left'=>'col-12',
                    'class_right'=>'col-12',
                ],
                [
                    'type'=>'text',
                    'name'=>'term_api_params',
                    'id'=>'term_api_params',
                    'title'=>lang('term_api_params'),
                    'placeholder'=>lang('term_api_params_placeholder'),
                    'tips'=>'',
                    'value'=>$params['data']['term_api_params'],
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'autocomplete'=>'on',
                    'maxlength'=>'60',
                    'class'=>'row form-group',
                    'class_left'=>'col-12',
                    'class_right'=>'col-12',
                    'class_right_control'=>'',
                    'class_right_tips'=>'',
                ],
                [
                    'type'=>'text',
                    'name'=>'term_api_tid',
                    'id'=>'term_api_tid',
                    'title'=>lang('term_api_tid'),
                    'placeholder'=>lang('term_api_tid_placeholder'),
                    'tips'=>'',
                    'value'=>$params['data']['term_api_tid'],
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'autocomplete'=>'on',
                    'maxlength'=>'20',
                    //'rows'=>4,
                    'class'=>'row form-group',
                    'class_left'=>'col-12',
                    'class_right'=>'col-12',
                    'class_right_control'=>'',
                    'class_right_tips'=>'',
                ],
                [
                    'type'=>'text',
                    'name'=>'term_api_type',
                    'id'=>'term_api_type',
                    'title'=>lang('term_api_type'),
                    'placeholder'=>lang('term_api_type_placeholder'),
                    'tips'=>'',
                    'value'=>$params['data']['term_api_type'],
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'autocomplete'=>'on',
                    'maxlength'=>'60',
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