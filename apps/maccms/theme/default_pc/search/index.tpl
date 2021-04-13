{extend name="./apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{$wd}相关的免费电影在线观看</title>
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
{block name="main"}
<div class="container">
<div class="row">
  <div class="col-12 px-2 px-md-3">
    <div class="jumbotron py-2 mb-2 bg-dark text-center text-light">
      找到{$total}条关于<strong class="mx-1 text-warning">{:DcHtml($wd)}</strong>的相关内容
    </div>
  </div>
</div>
<!--items -->
<div class="row" id="row">
  <div class="col-12 px-2 px-md-3" id="row">
    {volist name="item" id="dc"}
    {include file='block/itemMedia' /}
    {/volist}
  </div>
</div>
<!--page md -->
<div class="d-none d-md-block">
  <div class="d-flex justify-content-center">{$pages}</div>
</div>
<!--page sm -->
<div class="row d-md-none">
  <div class="col-12 px-2">
    <a class="btn btn-block btn-outline-secondary" data-pageClick="true" data-pageScroll="true" data-url="{:DcUrl('maccms/search/index',['wd'=>DcHtml($wd)],'')}&page=" data-page="{$current_page}" data-target="#row" data-target-lazyload="#row img[data-original]" data-target-language="#row">更多结果<i class="fa fa-lg fa-angle-down mx-1"></i></a>
  </div>
</div>
<!-- /container -->
</div> 
{/block}
{block name="footer"}{include file="block/footer" /}{/block}
<!-- -->
{block name="js"}
{/block}