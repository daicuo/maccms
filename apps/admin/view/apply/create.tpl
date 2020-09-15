{extend name="./public/static/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("applyInfo")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("applyInfo")}
</h6>
{:DcBuildForm([
    'class'=>'bg-white px-2 py-2',
    'action'=>DcUrl('admin/apply/update', '', ''),
    'method'=>'post',
    'ajax'=>true,
    'disabled'=>false,
    'submit'=>lang('submit'),
    'reset'=>lang('reset'),
    'close'=>false,
    'callback'=>'',
    'items'=>[
        [
            'type'=>'text',
            'name'=>'apply_name',
            'id'=>'apply_name',
            'title'=>lang('apply_name'),
            'placeholder'=>lang('apply_name_placeholder'),
            'tips'=>'',
            'value'=>config('common.apply_name'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'apply_module',
            'id'=>'apply_module',
            'title'=>lang('apply_module'),
            'placeholder'=>lang('apply_module_placeholder'),
            'tips'=>'',
            'value'=>config('common.apply_module'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'apply_version',
            'id'=>'apply_version',
            'title'=>lang('apply_version'),
            'placeholder'=>lang('apply_version_placeholder'),
            'tips'=>'',
            'value'=>config('common.apply_version'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_right_tips'=>'',
        ],
    ]
])}
{/block}
<!-- -->
{block name="js"}
{/block}