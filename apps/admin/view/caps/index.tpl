{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/caps/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("admin/caps/index")}
</h6>
{:DcBuildForm([
    'name'         => 'admin/caps/index',
    'class'        => 'bg-white py-2',
    'action'       => DcUrl('admin/caps/update'),
    'method'       => 'post',
    'ajax'         => false,
    'submit'       => lang('submit'),
    'reset'        => lang('reset'),
    'close'        => false,
    'disabled'     => false,
    'callback'     => '',
    'items'        => $items,
    'class_button' => 'form-group mb-0',
])}
{/block}