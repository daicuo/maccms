{extend name="./apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("maccms/weixin/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("maccms/weixin/index")}
</h6>
{:DcBuildForm([
    'name'     => 'maccms/weixin/index',
    'class'    => 'bg-white py-2',
    'action'   => DcUrlAddon(['module'=>'maccms','controll'=>'config','action'=>'update']),
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