{extend name="./apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{$id|DcHtml}年好看的电影与电视剧第{$current_page|default='1'}页-{:config('common.site_name')}</title>
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="container">
<div class="row">
  <div class="col-12 px-2 px-md-3">
    <div class="py-2 mb-2 rounded bg-dark text-light text-center">
      共找到<strong class="text-warning mx-1">{$total}</strong>条，当前第<strong class="text-warning mx-1">{$current_page}</strong>页，共有<strong class="text-warning mx-1">{$last_page}</strong>页
    </div>
  </div>
</div>
<!-- -->
<div class="row px-0" id="row">
  {volist name="item" id="dc"}
    {include file='block/itemRow' /}
  {/volist}
</div>
<!-- -->
<div class="d-none d-md-block">
  <div class="d-flex justify-content-center">{$pages}</div>
</div>
<!--page start -->
<div class="row d-md-none">
  <div class="col-12 px-2 px-md-3">
    <a class="btn btn-block btn-outline-secondary" data-pageClick="true" data-pageScroll="true" data-url="{:DcUrl('maccms/filter/year',['id'=>DcHtml($id)],'')}&page=" data-page="{$current_page}" data-target="#row" data-target-lazyload="#row img[data-original]" data-target-language="#row">更多结果 <i class="fa fa-lg fa-angle-down"></i></a>
  </div>
</div>
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}
{block name="js"}{/block}