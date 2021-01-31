{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("indexAdmin")}－{:lang('appName')}</title>
{/block}
{block name="header_addon"}
<link href="{$path_root}{$path_addon}view/theme.css">
<script src="{$path_root}{$path_addon}view/theme.js"></script>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("indexAdmin")}
</h6>
{:DcBuildForm([
    'class'    => 'bg-white px-2 py-2',
    'action'   => DcUrlAddon(['module'=>'index','controll'=>'admin','action'=>'update'],''),
    'method'   => 'post',
    'ajax'     => true,
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'disabled' => false,
    'callback' => '',
    'items'   => [
        [
            'type'=>'text',
            'name'=>'site_title',
            'id'=>'site_title',
            'title'=>lang('site_title'),
            'placeholder'=>lang('site_title_placeholder'),
            'tips'=>'',
            'value'=>config('index.site_title'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>true,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'site_keywords',
            'id'=>'site_keywords',
            'title'=>lang('site_keywords'),
            'placeholder'=>lang('site_keywords_placeholder'),
            'tips'=>'',
            'value'=>config('index.site_keywords'),
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
            'name'=>'site_description',
            'id'=>'site_description',
            'title'=>lang('site_description'),
            'placeholder'=>lang('site_description_placeholder'),
            'tips'=>'',
            'value'=>config('index.site_description'),
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
            'type'=>'select.custom',
            'name'=>'theme',
            'id'=>'theme',
            'title'=>lang('site_theme'),
            'placeholder'=>lang('site_theme_placeholder'),
            'tips'=>'',
            'value'=>config('index.theme'),
            'option'=>$themes,
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'multiple'=>false,
        ],
        [
            'type'=>'select.custom',
            'name'=>'theme_wap',
            'id'=>'theme_wap',
            'title'=>lang('wap_theme'),
            'placeholder'=>lang('wap_theme_placeholder'),
            'tips'=>'',
            'value'=>config('index.theme_wap'),
            'option'=>$themes,
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'multiple'=>false,
        ],
    ]
])}
{/block}
<!-- -->
{block name="js"}
{/block}