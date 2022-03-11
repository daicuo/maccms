{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/pack/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("admin/pack/index")}
</h6>
{:DcBuildForm([
    'name'     => 'admin/pack/index',
    'class'    => 'bg-white py-2',
    'action'   => DcUrl('admin/pack/save'),
    'method'   => 'post',
    'ajax'     => true,
    'disabled' => false,
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'callback' => '',
    'items'    => DcFormItems([
        'apply_name' => [
            'type'        => 'text',
            'value'       => config('common.apply_name'),
            'placeholder' => lang('apply_name_placeholder'),
            
        ],
        'apply_module' => [
            'type'        => 'text',
            'value'       => config('common.apply_module'),
            'placeholder' => lang('apply_module_placeholder'),
            
        ],
        'apply_version' => [
            'type'        => 'text',
            'value'       => config('common.apply_version'),
            'placeholder' => lang('apply_version_placeholder'),
        ],
        'apply_rely' => [
            'type'        => 'text',
            'value'       => config('common.apply_rely'),
            'placeholder' => lang('apply_rely_placeholder'),
        ],
    ]),
])}
{/block}