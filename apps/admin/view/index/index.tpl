{extend name="./public/static/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("indexIndex")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
    {:lang("indexIndex")}
</h6>
{:DcHookListen('admin_index_header',$params)}
<div class="table-responsive-sm">
<table class="table table-bordered bg-white mb-0">
    <tbody>
      {if condition="config('common.apply_name')"}
       <tr>
        <td>{:lang('apply_name')}</td>
        <td><a class="text-purple" href="{:lang('appServer')}/home/?module={:config('common.apply_module')}" target="_blank">{:DcHtml(config('common.apply_name'))}</a></td>
      </tr>
       <tr>
        <td>{:lang('apply_version')}</td>
        <td>{:DcHtml(config('common.apply_version'))}
        <span class="fa fa-spinner fa-spin dc-version" data-toggle="version" data-version="{:config('common.apply_version')}" data-module="{:config('common.apply_module')}"></span>
        </td>
      </tr>
      {/if}
      <tr>
        <td>{:lang('frameName')}</td>
        <td><a class="text-purple" href="{:lang('appUrl')}" target="_blank">{:lang('appName')}</a></td>
      </tr>
       <tr>
        <td>{:lang('frameVersion')}</td>
        <td>{:config('daicuo.version')}
        <span class="fa fa-spinner fa-spin dc-version" data-toggle="version" data-version="{:config('daicuo.version')}" data-module="daicuo" data-url="{:urlencode('http://hao.daicuo.cc/version/?action=check')}"></span></td>
      </tr>
       <tr>
        <td>{:lang('frameAuthor')}</td>
        <td><a class="text-dark" href="mailto:{:lang('appAuthor')}">{:lang('appAuthor')}</a></td>
      </tr>
      {if (in_array('administrator',$user['user_capabilities']))}
      <tr>
        <td>{:lang('serverInformation')}</td>
        <td>{:php_uname()}</td>
      </tr>
       <tr>
        <td>{:lang('serverEnvironment')}</td>
        <td>{$Think.PHP_OS} {:input('server.server_software')}</td>
      </tr>     
      <tr>
        <td>{:lang('webDirectory')}</td>
        <td>{$path_root}</td>
      </tr> 
      <tr>
        <td>{:lang('physicalPath')}</td>
        <td>{:input('server.document_root')}</td>
      </tr>           
       <tr>
        <td>{:lang('webDomain')}</td>
        <td>{:input('server.server_name')}({:input('server.server_addr')}:{:input('server.server_port')})</td>
      </tr>     
      <tr>
        <td>{:lang('phpVersion')}</td>
        <td>{$Think.PHP_VERSION}</td>
      </tr>
      <tr>
        <td>{:lang('phpEngine')}</td>
        <td>{$Think.PHP_SAPI}</td>
      </tr>
      <tr>
        <td>PHP{:lang('function')}（file_get_contents）</td>
        <td>{:DcDefault(function_exists(@file_get_contents),1,'<font class="text-purple">YES</font>','<font class="text-danger">NO</font>')} <small class="text-muted">{:lang('must')}</small></td>
      </tr>
       <tr>
        <td>PHP{:lang('function')}（mb_strimwidth）</td>
        <td>{:DcDefault(function_exists(@mb_strimwidth),1,'<font class="text-purple">YES</font>','<font class="text-danger">NO</font>')} <small class="text-muted">{:lang('optional')}</small></td>
      </tr>      
       <tr>
        <td>PHP{:lang('extend')}（curl_init）</td>
        <td>{:DcDefault(function_exists(@curl_init),1,'<font class="text-purple">YES</font>','<font class="text-danger">NO</font>')} <small class="text-muted">{:lang('optional')}</small></td>
      </tr>
       <tr>
        <td>PHP{:lang('extend')}（openssl）</td>
        <td>{:DcDefault(extension_loaded(@openssl),1,'<font class="text-purple">YES</font>','<font class="text-danger">NO</font>')} <small class="text-muted">{:lang('optional')} {:lang('emailFuntion')}</small></td>
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