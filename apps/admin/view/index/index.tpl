{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("index_index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
    {:lang("index_index")}
</h6>
{:DcHookListen('admin_index_header',$params)}
<div class="table-responsive-sm">
<table class="table table-bordered bg-white mb-0">
    <tbody>
      {if config('common.apply_name')}
       <tr>
        <td>{:lang('apply_name')}</td>
        <td><a class="text-purple" href="{$api_url}/home/?module={:config('common.apply_module')}" target="_blank">{:DcHtml(config('common.apply_name'))}</a></td>
      </tr>
       <tr>
        <td>{:lang('apply_version')}</td>
        <td>{:DcHtml(config('common.apply_version'))}
        <span class="fa fa-spinner fa-spin dc-version" data-toggle="version" data-version="{:config('common.apply_version')}" data-module="{:config('common.apply_module')}"></span>
        </td>
      </tr>
      {else/}
      <tr>
        <td>{:lang('frame_name')}</td>
        <td><a class="text-purple" href="{:lang('appUrl')}" target="_blank">{:lang('appName')}</a></td>
      </tr>
       <tr>
        <td>{:lang('frame_version')}</td>
        <td>{:config('daicuo.version')}
        <span class="fa fa-spinner fa-spin dc-version" data-toggle="version" data-version="{:config('daicuo.version')}" data-module="daicuo"></span></td>
      </tr>
      <tr>
        <td>{:lang('frame_author')}</td>
        <td><a class="text-dark" href="mailto:{:lang('appAuthor')}">{:lang('appAuthor')}</a></td>
      </tr>
      {/if}
      {if in_array('administrator',$user['user_capabilities'])}
      <tr>
        <td>{:lang('server_iformation')}</td>
        <td>{:php_uname()}</td>
      </tr>
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
        <td>{:lang('web_domain')}</td>
        <td>{:input('server.server_name')}({:input('server.server_addr')}:{:input('server.server_port')})</td>
      </tr>     
      <tr>
        <td>{:lang('php_version')}</td>
        <td>{$Think.PHP_VERSION}</td>
      </tr>
      <tr>
        <td>{:lang('php_engine')}</td>
        <td>{$Think.PHP_SAPI}</td>
      </tr>
      {/if}
    </tbody>
</table>
</div>
{:DcHookListen('admin_index_footer',$params)}
{/block}
<!-- -->
{block name="js"}
{/block}