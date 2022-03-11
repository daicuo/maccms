{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/apply/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("admin/apply/index")}
</h6>
{empty name="applys"}
<div class="alert alert-warning alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>{:lang('warning')}</strong>
  <a class="text-muted" href="{:DcUrl('admin/apply/store')}">{:lang('apply_empty')}</a>
</div>
{else/}
<ul class="list-unstyled">
  {volist name="applys" id="apply"}
  <li class="media bg-white mb-2 py-3 border-bottom">
    <div class="media-body">
      <h6>
        <strong>{$apply.name|DcHtml}</strong>
        <small class="text-muted">{$apply.version|DcHtml}</small>
      </h6>
      <p class="my-2 text-muted">{$apply.info|DcHtml}</p>
      <p class="my-0">
      	{eq name="apply.save" value="true"}
          <a class="btn btn-sm btn-purple" href="{:DcUrlAddon( ['module'=>$apply['module'],'controll'=>'admin','action'=>'index'] )}">{:lang('manage')}</a>
          {if $apply['disable']}
            <a class="btn btn-sm btn-warning" href="{:DcUrl('/admin/apply/enable',['module'=>$apply['module']])}">{:lang('enable')}</a>
          {else/}
           <a class="btn btn-sm btn-info" href="{:DcUrl('/admin/apply/disable',['module'=>$apply['module']])}">{:lang('disable')}</a>
          {/if}
          {if $apply['update']}
            <a class="btn btn-sm btn-success" href="{:DcUrl('/admin/apply/update','module='.$apply['module'])}">{:lang('upgrade')}</a>
          {/if}
          <a class="btn btn-sm btn-secondary" href="{:DcUrl('/admin/apply/remove','module='.$apply['module'])}">{:lang('unInstall')}</a>
          <a class="btn btn-sm btn-danger" href="{:DcUrl('/admin/apply/delete','module='.$apply['module'])}" data-toggle="delete">{:lang('delete')}</a>
          <a class="btn btn-sm btn-info" href="{:DcUrlAdmin($apply['module'].'/index/index')}" target="_blank">{:lang('preview')}</a>
        {else/}
          <a class="btn btn-sm btn-success" href="{:DcUrl('/admin/apply/save',['module'=>$apply['module']])}">{:lang('install')}</a>
        {/eq}
        <a class="btn btn-sm btn-primary" href="{$api_url}/home/?module={$apply.module}" target="_blank">{:lang('apply_home')}</a>
      </p>
    </div>
  </li>
  {/volist}
</ul>
{/empty}
{/block}