{extend name="./public/static/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:config('maccms.site_title')}-{:config('common.site_name')}</title>
<meta name="keywords" content="{:config('maccms.site_keywords')}" />
<meta name="description" content="{:config('maccms.site_description')}"  />
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="container">
<!-- -->
<div class="row px-0">
  <div class="col-12 px-1 mt-3 mb-2">
    <fieldset>
      <legend class="h6 px-4">
        <i class="fa fa-line-chart text-purple"></i>
        <a class="text-dark text-decoration-none" href="{:DcUrl('maccms/filter/lately',['page'=>1],'')}">最近更新</a>
      </legend>
    </fieldset>
  </div>
</div>
<div class="row px-0" id="row">
    {volist name="item" id="maccms" offset="0" length="30"}
        {include file='block/itemRow' /}
    {/volist}
</div>
<!--page start -->
<div class="row px-0 mt-2">
    <div class="col-12 px-1">
        <a class="btn btn-block btn-outline-secondary" data-toggle="pageClick" data-pageScroll="true" data-url="{:DcUrl('maccms/filter/lately',['limit'=>$limit,'page'=>''],'')}" data-page="{$current_page}" data-target="#row">更多结果 <i class="fa fa-lg fa-angle-down"></i></a>
    </div>
</div>
<!-- -->
</div>
{/block}
{block name="footer"}{include file="block/footer" /}{/block}
<!-- -->
{block name="js"}{/block}