{extend name="./apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:date('Y',time())}年最新好看的电影与电视剧-{:config('common.site_name')}</title>
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="container">
{if config("maccms.header_wap")}
<div>{:posterParse("maccms.header_wap")}</div>
{/if}
<!-- -->
<fieldset class="d-block mx-2 mt-3 mb-2">
  <legend class="h6 px-4">
    <i class="fa fa-line-chart text-info"></i>
    <a class="text-dark text-decoration-none" href="{:DcUrl('maccms/filter/index',['pageNumber'=>1])}">最近更新</a>
  </legend>
</fieldset>
<div class="row mx-1" id="row">
  {volist name="item" id="maccms"}
    {include file='block/itemRow' /}
  {/volist}
</div>
<!--page start -->
<div class="row mx-1">
  <div class="col-12 px-1">
    <a class="btn btn-block btn-secondary" data-pageClick="true" data-pageScroll="true" data-url="{:DcUrl('maccms/filter/index',['pageNumber'=>''])}" data-page="{$current_page}" data-target="#row" data-target-lazyload="#row img[data-original]" data-target-language="#row">更多结果 <i class="fa fa-lg fa-angle-down"></i></a>
  </div>
</div>
<!-- -->
</div>
{/block}
{block name="footer"}{include file="block/footer" /}{/block}