{extend name="./apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("maccms/admin/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("maccms/admin/index")}
</h6>
{:DcBuildForm([
    'name'     => 'maccms/admin/index',
    'class'    => 'bg-white py-2',
    'action'   => DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'update']),
    'method'   => 'post',
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'disabled' => false,
    'ajax'     => true,
    'callback' => '',
    'items'    => $items,
])}
{/block}