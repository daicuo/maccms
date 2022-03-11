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
<!--page start -->
<div class="row d-md-none mb-2">
  <div class="col-12 px-2 px-md-3">
    <a class="btn btn-block btn-outline-secondary" data-pageClick="true" data-pageScroll="false" data-url="{:DcUrl('maccms/category/'.$action,['id'=>$term_id,'limit'=>intval($term_api_limit)])}&pageNumber=" data-page="{$current_page}" data-target="#row" data-target-lazyload="#row img[data-original]" data-target-language="#row">更多结果 <i class="fa fa-lg fa-angle-down"></i></a>
  </div>
</div>
<!-- -->
<div class="d-none d-md-block">
  <div class="d-flex justify-content-center">{$pages}</div>
</div>
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}