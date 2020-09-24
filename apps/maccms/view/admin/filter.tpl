{extend name="./public/static/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("filterAdmin")}－{:lang('appName')}</title>
{/block}
{block name="header_addon"}
<link href="{$path_root}{$path_addon}view/theme.css" rel="stylesheet">
<script src="{$path_root}{$path_addon}view/theme.js"></script>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("filterAdmin")}
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
            'type'=>'text',
            'name'=>'filter_play',
            'id'=>'filter_play',
            'title'=>lang('filter_play'),
            'placeholder'=>lang('filter_play_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.filter_play'),
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
            'type'=>'textarea',
            'name'=>'filter_tid',
            'id'=>'filter_tid',
            'title'=>lang('filter_tid'),
            'placeholder'=>lang('filter_tid_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.filter_tid'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>8,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'textarea',
            'name'=>'filter_ids',
            'id'=>'filter_ids',
            'title'=>lang('filter_ids'),
            'placeholder'=>lang('filter_ids_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.filter_ids'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>8,
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