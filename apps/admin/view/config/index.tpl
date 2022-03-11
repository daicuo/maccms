{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/config/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
{:DcBuildForm([
    'name'          => 'admin/config/index',
    'class'         => 'bg-white',
    'action'        => DcUrl('admin/config/update'),
    'method'        => 'post',
    'submit'        => lang('submit'),
    'reset'         => lang('reset'),
    'close'         => false,
    'disabled'      => false,
    'ajax'          => true,
    'callback'      => '',
    'class_tabs'    => 'mb-2',
    'class_link'    => 'rounded-0',
    'class_content' => 'border p-3',
    'class_button'  => 'form-group text-center mb-0',
    'group'         => $group,
])}
{/block}