{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("op_index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
{:DcBuildForm([
    'name'          => 'op_index',
    'class'         => 'bg-white',
    'action'        => DcUrl('admin/op/update', 'module=common', ''),
    'method'        => 'post',
    'submit'        => lang('submit'),
    'reset'         => lang('reset'),
    'close'         => false,
    'disabled'      => false,
    'ajax'          => true,
    'callback'      => '',
    'class_tabs'    => 'mb-2',
    'class_link'    => 'rounded-0',
    'class_content' => 'border p-2 mb-5',
    'group'         => [
        [
            'title' => lang('op_index'),
            'items' => [
                [
                    'type'=>'switch',
                    'name'=>'app_domain',
                    'id'=>'app_domain',
                    'title'=>lang('app_domain'),
                    'tips'=>'',
                    'value'=>config('common.app_domain'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-auto',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'switch',
                    'name'=>'site_status',
                    'id'=>'site_status',
                    'title'=>lang('site_status'),
                    'tips'=>'',
                    'value'=>config('common.site_status'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-auto',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'text',
                    'name'=>'site_name',
                    'id'=>'site_name',
                    'title'=>lang('site_name'),
                    'placeholder'=>lang('site_name_placeholder'),
                    'tips'=>DcTplLabelOp('common','site_name'),
                    'value'=>config('common.site_name'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>true,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6 d-md-flex',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'text',
                    'name'=>'site_domain',
                    'id'=>'site_domain',
                    'title'=>lang('site_domain'),
                    'placeholder'=>lang('site_domain_placeholder'),
                    'tips'=>DcTplLabelOp('common','site_domain'),
                    'value'=>config('common.site_domain'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'text',
                    'name'=>'wap_domain',
                    'id'=>'wap_domain',
                    'title'=>lang('wap_domain'),
                    'placeholder'=>lang('wap_domain_placeholder'),
                    'tips'=>DcTplLabelOp('common','wap_domain'),
                    'value'=>config('common.wap_domain'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'text',
                    'name'=>'site_icp',
                    'id'=>'site_icp',
                    'title'=>lang('site_icp'),
                    'placeholder'=>lang('site_icp_placeholder'),
                    'tips'=>DcTplLabelOp('common','site_icp'),
                    'value'=>config('common.site_icp'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'text',
                    'name'=>'site_id',
                    'id'=>'site_id',
                    'title'=>lang('site_id'),
                    'placeholder'=>lang('site_id_placeholder'),
                    'tips'=>DcTplLabelOp('common','site_id'),
                    'value'=>config('common.site_id'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'text',
                    'name'=>'site_token',
                    'id'=>'site_token',
                    'title'=>lang('site_token'),
                    'placeholder'=>lang('site_token_placeholder'),
                    'tips'=>DcTplLabelOp('common','site_token'),
                    'value'=>config('common.site_token'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'textarea',
                    'name'=>'site_tongji',
                    'id'=>'site_tongji',
                    'title'=>lang('site_tongji'),
                    'placeholder'=>lang('site_tongji_placeholder'),
                    'tips'=>DcTplLabelOp('common','site_tongji'),
                    'value'=>config('common.site_tongji'),
                    'rows'=>4,
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'textarea',
                    'name'=>'site_close',
                    'id'=>'site_close',
                    'title'=>lang('site_close'),
                    'placeholder'=>lang('site_close_placeholder'),
                    'tips'=>DcTplLabelOp('common','site_close'),
                    'value'=>config('common.site_close'),
                    'rows'=>5,
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
            ]
        ],
        [
            'title' => lang('op_safe'),
            'items' => [
                [
                    'type'=>'text',
                    'name'=>'site_secret',
                    'id'=>'site_secret',
                    'title'=>lang('site_secret'),
                    'placeholder'=>lang('site_secret_placeholder'),
                    'tips'=>'',
                    'value'=>config('common.site_secret'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'text',
                    'name'=>'user_max_expire',
                    'id'=>'user_max_expire',
                    'title'=>lang('user_max_expire'),
                    'placeholder'=>lang('user_max_expire_placeholder'),
                    'tips'=>'',
                    'value'=>config('common.user_max_expire'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'text',
                    'name'=>'user_max_error',
                    'id'=>'user_max_error',
                    'title'=>lang('user_max_error'),
                    'placeholder'=>lang('user_max_error_placeholder'),
                    'tips'=>'',
                    'value'=>config('common.user_max_error'),
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
                [
                    'type'=>'textarea',
                    'name'=>'user_force_white',
                    'id'=>'user_force_white',
                    'title'=>lang('user_force_white'),
                    'placeholder'=>lang('user_force_white_placeholder'),
                    'tips'=>'',
                    'value'=>config('common.user_force_white'),
                    'rows'=>6,
                    'readonly'=>false,
                    'disabled'=>false,
                    'required'=>false,
                    'class'=>'row form-group',
                    'class_left'=>'col-md-2',
                    'class_right'=>'col-md-6',
                    'class_right_control'=>'',
                    'class_tips'=>'',
                ],
            ]
        ]
    ],
])}
{/block}
<!-- -->
{block name="js"}
{/block}