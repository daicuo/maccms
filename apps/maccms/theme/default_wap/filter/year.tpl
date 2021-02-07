{extend name="./apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{$id|DcHtml}年最新好看的电影与电视剧-{:config('common.site_name')}</title>
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="container">
<!-- -->
<div class="row px-0">
  <div class="col-12 px-1">
    <fieldset class="mt-4 mb-3">
      <legend class="h6 px-4">
        <i class="fa fa-line-chart text-purple"></i>
        <span class="text-dark">{$id|DcHtml}年上映的影片</span>
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
    <a class="btn btn-block btn-outline-secondary" data-toggle="pageClick" data-pageScroll="true" data-url="{:DcUrl('maccms/filter/year',['id'=>DcHtml($id)],'')}&page=" data-page="{$current_page}" data-target="#row">更多结果 <i class="fa fa-lg fa-angle-down"></i></a>
  </div>
</div>
<!-- -->
</div>
{/block}
{block name="footer"}{include file="block/footer" /}{/block}
<!-- -->
{block name="js"}{/block}