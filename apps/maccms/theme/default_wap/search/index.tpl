{extend name="./apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{$wd|DcHtml}免费观看－{:config('common.site_name')}</title>
<meta name="keywords" content="{$wd|DcHtml}免费观看" />
<meta name="description" content="关于{$wd|DcHtml}的免费在线电影搜索结果共有{$total}条"  />
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="container bg-info p-2 pb-1">
<h6 class="text-center text-light rounded border mb-3 py-2">
  共找到<strong class="mx-1">{$total|number_format}</strong>条
  关于<span class="text-dark mx-1">{$wd|DcHtml|DcSubstr=0,6,false}</span>的影片<!--当前第{$pageIndex}/{$totalPage}页 -->
</h6>
<!---->
<section class="bg-white rounded mb-3 pb-3 px-2" id="row">
{volist name="item" id="maccms"}
  {include file='block/itemMediaSmall' /}
{/volist}
</section>
<!--page start -->
<section>
  <a class="btn btn-block btn-outline-light text-light bg-info" data-pageClick="true" data-pageScroll="false" data-url="{:DcUrl('maccms/search/index',['wd'=>DcHtml($wd)])}&pageNumber=" data-page="{$current_page}" data-target="#row" data-target-lazyload="#row img[data-original]" data-target-language="#row">更多结果 <i class="fa fa-lg fa-angle-down"></i></a>
</section>
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}