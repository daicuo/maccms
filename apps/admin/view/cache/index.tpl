{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/cache/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("admin/cache/index")}
  <small class="text-muted">{:lang("cache_tips")}</small>
</h6>
{:DcBuildForm([
    'name'     => 'admin/cache/index',
    'class'    => 'py-2',
    'action'   => DcUrl('admin/cache/update'),
    'method'   => 'post',
    'disabled' => false,
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'ajax'     => true,
    'callback' => '',
    'items'    => $fields,
])}
{/block}