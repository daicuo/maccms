{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/upload/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("admin/upload/index")}
</h6>
{:DcBuildForm([
    'name'      => 'admin/upload/index',
    'class'     => 'py-2',
    'action'    => DcUrl('admin/upload/update'),
    'method'    => 'post',
    'submit'    => lang('submit'),
    'reset'     => lang('reset'),
    'close'     => false,
    'disabled'  => false,
    'ajax'      => true,
    'callback'  => '',
    'items'     => $fields,
])}
{/block}