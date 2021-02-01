{extend name="./apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin_link")}－{:lang('appName')}</title>
{/block}
{block name="header_addon"}
<link href="{$path_root}{$path_addon}view/theme.css" rel="stylesheet">
<script src="{$path_root}{$path_addon}view/theme.js"></script>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("admin_link")}
</h6>
{:DcBuildForm([
    'class'    => 'bg-white px-2 py-2',
    'action'   => DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'update'],''),
    'method'   => 'post',
    'ajax'     => true,
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'disabled' => false,
    'callback' => '',
    'items'   => [
        [
            'type'=>'json',
            'name'=>'link_index',
            'id'=>'link_index',
            'title'=>lang('link_index'),
            'placeholder'=>lang('link_index_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.link_index'),
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
        [
            'type'=>'json',
            'name'=>'link_footer',
            'id'=>'link_footer',
            'title'=>lang('link_footer'),
            'placeholder'=>lang('link_footer_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.link_footer'),
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