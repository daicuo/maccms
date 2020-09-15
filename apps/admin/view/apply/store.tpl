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
<ul class="list-unstyled">
  {volist name="list" id="apply"}
  <li class="media bg-white mb-2 p-2">
    <div class="media-body">
      <h6 class="my-0">{$apply.name|DcHtml}</h6>
      <p class="my-2 text-muted">{$apply.info|DcHtml}</p>
      <p class="my-0">
      	<a class="btn btn-sm btn-outline-primary" href="{$apply.down|remove_xss}" target="_blank">{:lang('down')}</a>
        {notempty name="apply.demo"}
        	<a class="btn btn-sm btn-outline-secondary" href="{$apply.demo|remove_xss}" target="_blank">{:lang('demo')}</a>
        {/notempty}        
        {eq name="apply.online" value="true"}
        	<a class="btn btn-sm btn-outline-success" href="{:DcUrl('/admin/apply/online','module='.$apply['module'],'')}">{:lang('install')}</a>
        {/eq}
      </p>
    </div>
  </li>
  {/volist}
</ul>
{/block}
<!-- -->
{block name="js"}
{/block}