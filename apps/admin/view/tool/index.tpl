{extend name="./public/static/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("toolIndex")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
    {:lang("toolIndex")}
</h6>
<div class="table-responsive-sm">
<table class="table table-bordered bg-white mb-0">
    <tbody>
      <tr>
        <td class="w-50 align-middle"><strong>{:lang('clear_cache')}</strong></td>
        <td><a class="btn btn-purple btn-sm" href="{:DcUrl('admin/tool/clear_cache','','')}"><i class="fa fa-fw fa-trash-o"></i>{:lang('clear')}</a></td>
      </tr> 
      <tr>
        <td class="w-25 align-middle"><strong>{:lang('clear_runtime')}</strong></td>
        <td><a class="btn btn-purple btn-sm" href="{:DcUrl('admin/tool/clear_runtime','','')}"><i class="fa fa-fw fa-trash-o"></i>{:lang('clear')}</a></td>
      </tr>
      <tr>
        <td class="w-25 align-middle"><strong>{:lang('clear_option')}</strong></td>
        <td><a class="btn btn-purple btn-sm" href="{:DcUrl('admin/tool/clear_option','','')}" data-toggle="delete"><i class="fa fa-fw fa-trash-o"></i>{:lang('clear')}</a></td>
      </tr>
    </tbody>
</table>
</div>
{/block}
<!-- -->
{block name="js"}
{/block}