{extend name="./public/static/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{$wd}免费观看</title>
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="container bg-info p-3">
<h6 class="text-center text-light rounded border mb-3 py-3">
已为您找到<strong class="mx-2">{:DcHtml($wd)}</strong>{$total}条相关结果<!--当前第{$pageIndex}/{$totalPage}页 -->
</h6>
<section class="bg-white rounded mb-3 py-3 px-3" id="row">
{volist name="item" id="maccms"}
    {include file='block/itemMediaSmall' /}
{/volist}
</section>
<!--page start -->
<section class="mb-3">
    <a class="btn btn-block btn-outline-light text-light bg-info" data-toggle="pageClick" data-pageScroll="false" data-url="{:DcUrl('maccms/search/'.action,['wd'=>DcHtml($wd),'page'=>''],'')}" data-page="{$current_page}" data-target="#row">更多结果 <i class="fa fa-lg fa-angle-down"></i></a>
</section>
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}
{block name="js"}{/block}