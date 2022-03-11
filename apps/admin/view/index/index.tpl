{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/index/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("admin/index/index")}
</h6>
{:DcHookListen('admin_index_header',$params)}
<div class="table-responsive-sm">
<table class="table table-bordered bg-white mb-0 text-nowrap">
  <tbody>
  {if config('common.apply_name')}
  <tr>
    <td>{:lang('frame_version')}</td>
    <td>{:config('daicuo.version')}</td>
  </tr>
   <tr>
    <td>{:lang('apply_version')}</td>
    <td>{:DcHtml(config('common.apply_version'))} <span class="fa fa-spinner fa-spin dc-version" data-toggle="version" data-version="{:config('common.apply_version')}" data-module="{:config('common.apply_module')}"></span></td>
  </tr>
  {else/}
  <tr>
    <td>{:lang('frame_version')}</td>
    <td>{:config('daicuo.version')} <span class="fa fa-spinner fa-spin dc-version" data-toggle="version" data-version="{:config('daicuo.version')}" data-module="daicuo"></span></td>
  </tr>
  {/if}
  <tr>
    <td>{:lang('frame_author')}</td>
    <td><a class="text-dark" href="mailto:{:lang('appAuthor')}">{:lang('appAuthor')}</a></td>
  </tr>
  {if in_array('administrator',$user['user_capabilities'])}
   <tr>
    <td>{:lang('server_environment')}</td>
    <td>{$Think.PHP_OS} {:input('server.server_software')}</td>
  </tr>     
  <tr>
    <td>{:lang('web_directory')}</td>
    <td>{$path_root}</td>
  </tr> 
  <tr>
    <td>{:lang('physical_path')}</td>
    <td>{:input('server.document_root')}</td>
  </tr>
  <tr>
    <td>{:lang('database_type')}</td>
    <td>{:config('database.type')}</td>
  </tr>
  <tr>
    <td>{:lang('php_version')}</td>
    <td>{$Think.PHP_VERSION}</td>
  </tr>
  <tr>
    <td class="w-25">{:lang('php_engine')}</td>
    <td class="w-75">{$Think.PHP_SAPI}</td>
  </tr>
  {/if}
  </tbody>
</table>
</div>
{:DcHookListen('admin_index_footer',$params)}
{/block}