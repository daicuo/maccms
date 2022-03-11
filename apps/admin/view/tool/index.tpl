{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/tool/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("admin/tool/index")}
</h6>
<div class="table-responsive-sm">
<table class="table table-bordered bg-white mb-0">
<tbody>
  {:DcHookListen('admin_tool_header')}
  <tr>
    <td class="w-50 align-middle"><strong>{:lang('delete_cache')}</strong></td>
    <td><a class="btn btn-purple btn-sm" href="{:DcUrl('admin/tool/delete',['type'=>'cache'])}"><i class="fa fa-fw fa-trash-o"></i>{:lang('clear')}</a></td>
  </tr> 
  <tr>
    <td class="w-25 align-middle"><strong>{:lang('delete_runtime')}</strong></td>
    <td><a class="btn btn-purple btn-sm" href="{:DcUrl('admin/tool/delete',['type'=>'runtime'])}"><i class="fa fa-fw fa-trash-o"></i>{:lang('clear')}</a></td>
  </tr>
  <tr>
    <td class="w-25 align-middle"><strong>{:lang('update_count')}</strong></td>
    <td><a class="btn btn-purple btn-sm" href="{:DcUrl('admin/tool/update',['type'=>'termCount'])}"><i class="fa fa-fw fa-check"></i>{:lang('update')}</a></td>
  </tr>
  <tr>
    <td class="w-25 align-middle"><strong>{:lang('clear_option')}</strong></td>
    <td><a class="btn btn-purple btn-sm" href="{:DcUrl('admin/tool/clear')}" data-toggle="delete"><i class="fa fa-fw fa-trash-o"></i>{:lang('clear')}</a></td>
  </tr>
  {:DcHookListen('admin_tool_footer')}
</tbody>
</table>
</div>
{/block}