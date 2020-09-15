{extend name="./public/static/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("applyIndex")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("applyIndex")}
</h6>
{empty name="applys"}
<div class="alert alert-warning alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>{:lang('warning')}</strong>
  <a class="text-muted" href="{:DcUrl('admin/apply/store','','')}">{:lang('apply_empty')}</a>
</div>
{else/}
<ul class="list-unstyled">
  {volist name="applys" id="apply"}
  <li class="media bg-white mb-2 p-2">
    <div class="media-body">
      <h6 class="my-0">{$apply.name|DcHtml}</h6>
      <p class="my-2 text-muted">{$apply.info|DcHtml}</p>
      <p class="my-0">
      	{eq name="apply.install" value="true"}
        	<a class="btn btn-sm btn-outline-primary" href="{:DcUrl('/admin/addon/index',['appmodule'=>$apply['module'],'appcontroll'=>'index','appaction'=>'index'],'')}">{:lang('manage')}</a>
        	<a class="btn btn-sm btn-outline-secondary" href="{:DcUrl('/admin/apply/delete','module='.$apply['module'],'')}">{:lang('unInstall')}</a>
        	<a class="btn btn-sm btn-outline-success" href="{:DcAdminUrl($apply['module'].'/index/index','','')}" target="_blank">{:lang('preview')}</a>
        {else/}
        	<a class="btn btn-sm btn-outline-success" href="{:DcUrl('/admin/apply/save','module='.$apply['module'],'')}">{:lang('install')}</a>
        {/eq}
        <a class="btn btn-sm btn-outline-secondary" href="{:lang('appServer')}/home/?module={$apply.module}" target="_blank">{:lang('apply_home')}</a>
      </p>
    </div>
  </li>
  {/volist}
</ul>
{/empty}
{/block}
<!-- -->
{block name="js"}
{/block}