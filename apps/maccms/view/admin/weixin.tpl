{extend name="./apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin_wx")}－{:lang('appName')}</title>
{/block}
{block name="header_addon"}
<link href="{$path_root}{$path_addon}view/theme.css" rel="stylesheet">
<script src="{$path_root}{$path_addon}view/theme.js"></script>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("admin_wx")}
</h6>
{:DcBuildForm([
    'name'     => 'maccms_index',
    'class'    => 'bg-white px-2 py-2',
    'action'   => DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'update'],''),
    'method'   => 'post',
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'disabled' => false,
    'ajax'     => true,
    'callback' => '',
    'items'    => [
        [
            'type'=>'text',
            'name'=>'wx_token',
            'id'=>'wx_token',
            'title'=>lang('wx_token'),
            'placeholder'=>lang('wx_token_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.wx_token'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>true,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'wx_follow',
            'id'=>'wx_follow',
            'title'=>lang('wx_follow'),
            'placeholder'=>lang('wx_follow_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.wx_follow'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'wx_none',
            'id'=>'wx_none',
            'title'=>lang('wx_none'),
            'placeholder'=>lang('wx_none_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.wx_none'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'wx_domain',
            'id'=>'wx_domain',
            'title'=>lang('wx_domain'),
            'placeholder'=>lang('wx_domain_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.wx_domain'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'json',
            'name'=>'wx_keywords',
            'id'=>'wx_keywords',
            'title'=>lang('wx_keywords'),
            'placeholder'=>lang('wx_keywords_placeholder'),
            'tips'=>'',
            'value'=>DcEmpty(config('maccms.wx_keywords'),json_encode([['title'=>'测试','content'=>'我是测试','picurl'=>'https://cdn.daicuo.cc/images/daicuo/logo.png','url'=>'http://www.daicuo.net']])),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>16,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
    ]
])}
{/block}
<!-- -->
{block name="js"}
{/block}