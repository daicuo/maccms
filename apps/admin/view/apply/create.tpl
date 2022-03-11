{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/apply/create")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("admin/apply/create")}
</h6>
{:DcBuildForm([
    'name'     => 'admin/apply/create',
    'class'    => 'bg-white py-2',
    'action'   => DcUrl('admin/apply/park'),
    'method'   => 'post',
    'ajax'     => true,
    'disabled' => false,
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'callback' => '',
    'items'    => [
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
            'class_tips'=>'',
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
            'class_tips'=>'',
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
            'class_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'apply_rely',
            'id'=>'apply_rely',
            'title'=>lang('apply_rely'),
            'placeholder'=>lang('apply_rely_placeholder'),
            'tips'=>'',
            'value'=>config('common.apply_rely'),
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
])}
{/block}