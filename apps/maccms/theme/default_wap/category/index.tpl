{extend name="./apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{$term_name}第{$current_page|default='1'}页-{:config('common.site_name')}</title>
<meta name="keywords" content="{$term_slug},{$term_name}" />
<meta name="description" content="{$term_info|default='好看免费无广告'}"  />
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<script>$("li[data-cid='{$term_id}']").addClass('active');</script>
<div class="container pt-2">
{if config("maccms.header_wap")}
<div class="mb-2 mx-1">
  {:posterParse("maccms.header_wap")}
</div>
{else/}
<div class="text-center text-light bg-info rounded mb-2 mx-1 py-2">
  共找到<strong class="mx-1">{$total|number_format}</strong>条{$term_name}
</div>
{/if}
<!-- -->
<div class="row mx-1" id="row">
  {volist name="item" id="maccms"}
    {include file='block/itemRow' /}
  {/volist}
</div>
<!--page start -->
<div class="row mx-2 mt-2">
  <div class="col-12 px-1">
    <a class="btn btn-block btn-outline-dark" data-pageClick="true" data-pageScroll="true" data-url="{:DcUrl('maccms/category/index',['id'=>$term_id,'pageNumber'=>''])}" data-page="1" data-target="#row" data-target-lazyload="#row img[data-original]" data-target-language="#row">查看更多 <i class="fa fa-lg fa-angle-down"></i></a>
  </div>
</div>
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}