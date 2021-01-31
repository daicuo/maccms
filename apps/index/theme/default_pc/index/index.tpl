{extend name="apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:config('index.site_title')}－{:config('common.site_name')}</title>
<meta name="keywords" content="{:config('index.site_keywords')}" />
<meta name="description" content="{:config('index.site_description')}"  />
{/block}
<!--main -->
{block name="main"}
<div class="jumbotron">
    <div class="container text-center py-5">
      <h1 class="display-5 text-purple">{:config('index.site_title')}</h1>
      <hr class="my-4">
      <p class="lead mb-4">{:config('index.site_description')}</p>
    </div>
</div>
<div class="container">
    <div class="row">
    {volist name=":config('common.site_applys')" id="apply"}
    <div class="col-sm-6 mb-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{$apply.name|DcHtml|DcSubstr=0,20,false}</h5>
                <p class="card-text text-muted text-truncate">{$apply.info|DcHtml}</p>
                <a href="{:DcUrl($apply['module'].'/index/index','','')}" class="btn btn-purple btn-sm">{:lang('view')}</a>
            </div>
        </div>
    </div>
    {/volist} 
    </div>
    <p class="text-center">
        Powered by <a class="text-muted small" href="http://www.daicuo.net" target="_blank">{:lang('appName')} V {:config('daicuo.version')}</a>
    </p>
    <p class="text-center">
        Copyright © 2019-2020 {:config('common.site_domain')} All rights reserved
    </p>
</div>
{/block}
<!-- -->