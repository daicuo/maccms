<?php
namespace app\admin\loglic;

class Config
{
    public function fields()
    {
        $group = [
        [
            'title' => lang('config_base'),
            'items' => DcFormItems([
                'site_status' => [
                    'type'  => 'switch',
                    'value' => config('common.site_status'),
                ],
                'app_debug' => [
                    'type'  => 'switch',
                    'value' => config('common.app_debug'),
                ],
                'app_domain' => [
                    'type'  => 'switch',
                    'value' => config('common.app_domain'),
                ],
                'site_captcha' => [
                    'type'  => 'switch',
                    'value' => config('common.site_captcha'),
                ],
                'editor_name' => [
                    'type'        => 'select',
                    'value'       => config('common.editor_name'),
                    'option'      => DcEditorOption(),
                    'placeholder' => lang('editor_name_placeholder'),
                    'multiple'    => false,
                    'class_left'  => 'col-md-2',
                    'class_right' => 'col-auto',
                ],
                'site_id' => [
                    'type'        => 'text',
                    'value'       => config('common.site_id'),
                    'placeholder' => lang('site_id_placeholder'),
                    'tips'        => DcTplLabelOp('common','site_id').lang('site_token_tips'),
                ],
                'site_token' => [
                    'type'        => 'text',
                    'value'       => config('common.site_token'),
                    'placeholder' => lang('site_token_placeholder'),
                    'tips'        => DcTplLabelOp('common','site_token').lang('site_token_tips'),
                ],
                'site_name' => [
                    'type'        => 'text',
                    'value'       => config('common.site_name'),
                    'placeholder' => lang('site_name_placeholder'),
                    'tips'        => DcTplLabelOp('common','site_name'),
                ],
                'site_domain' => [
                    'type'        => 'text',
                    'value'       => config('common.site_domain'),
                    'placeholder' => lang('site_domain_placeholder'),
                    'tips'        => DcTplLabelOp('common','site_domain'),
                ],
                'wap_domain' => [
                    'type'        => 'text',
                    'value'       => config('common.wap_domain'),
                    'placeholder' => lang('wap_domain_placeholder'),
                    'tips'        => DcTplLabelOp('common','wap_domain'),
                ],
                'url_suffix' => [
                    'type'        => 'text',
                    'value'       => config('common.url_suffix'),
                    'placeholder' => lang('url_suffix_placeholder'),
                    'tips'        => DcTplLabelOp('common','url_suffix'),
                ],
                'site_email' => [
                    'type'        => 'text',
                    'value'       => config('common.site_email'),
                    'placeholder' => lang('site_email_placeholder'),
                    'tips'        => DcTplLabelOp('common','site_email'),
                ],
                'site_close' => [
                    'type'        => 'text',
                    'value'       => config('common.site_close'),
                    'placeholder' => lang('site_close_placeholder'),
                    'tips'        => DcTplLabelOp('common','site_close'),
                ],
                'site_icp' => [
                    'type'        => 'text',
                    'value'       => config('common.site_icp'),
                    'placeholder' => lang('site_icp_placeholder'),
                    'tips'        => DcTplLabelOp('common','site_icp'),
                ],
                'site_gongan' => [
                    'type'        => 'text',
                    'value'       => config('common.site_gongan'),
                    'placeholder' => lang('site_gongan_placeholder'),
                    'tips'        => DcTplLabelOp('common','site_gongan'),
                ],
                'header_tongji' => [
                    'type'        => 'textarea',
                    'value'       => config('common.header_tongji'),
                    'placeholder' => lang('header_tongji_placeholder'),
                    'tips'        => DcTplLabelOp('common','header_tongji'),
                    'rows'        => 3,
                ],
                'site_tongji' => [
                    'type'        => 'textarea',
                    'value'       => config('common.site_tongji'),
                    'placeholder' => lang('site_tongji_placeholder'),
                    'tips'        => DcTplLabelOp('common','site_tongji'),
                    'rows'        => 3,
                ],
            ]),
        ],
        [
            'title' => lang('config_plus'),
            'items' => DcFormItems([
                'site_secret' => [
                    'type'        => 'text',
                    'value'       => config('common.site_secret'),
                    'placeholder' => lang('site_secret_placeholder'),
                ],
                'site_log' => [
                    'type'        => 'text',
                    'value'       => config('common.site_log'),
                    'placeholder' => lang('site_log_placeholder'),
                ],
                'token_expire' => [
                    'type'        => 'text',
                    'value'       => config('common.token_expire'),
                    'placeholder' => lang('token_expire_placeholder'),
                ],
                'user_max_expire' => [
                    'type'        => 'text',
                    'value'       => config('common.user_max_expire'),
                    'placeholder' => lang('user_max_expire_placeholder'),
                    
                ],
                'user_max_error' => [
                    'type'        => 'text',
                    'value'       => config('common.user_max_error'),
                    'placeholder' => lang('user_max_error_placeholder'),
                ],
                'user_force_white' => [
                    'type'        => 'text',
                    'value'       => config('common.user_force_white'),
                    'placeholder' => lang('user_force_white_placeholder'),
                ],
            ])
        ],
    ];
    //合并动态扩展字段
    if($customs = model('common/Config','loglic')->metaList('admin', 'config')){
        $group[1]['items'] = array_merge($group[1]['items'], DcFormItems(DcFields($customs, config('common'))) );
        //array_push($group,['title'=>lang('op_global'),'items'=>DcFormItems(DcFields($customs, config('common')))]);

    }
    //返回所有表单字段
    return $group;
  }
}