{extend name="./public/static/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>综艺频道</title>
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="jumbotron jumbotron-fluid p-2 mb-2 text-center text-purple">
	共找到{$totalRecord}条，当前第{$pageIndex}页，共有{$totalPage}页
</div>
<div class="container">
<!-- -->
<div class="row px-0" id="row">
{volist name="item" id="dc"}
{include file='block/itemRow' /}
{/volist}
</div>
<!--page start -->
{include file='block/pageAjax' /}
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}
{block name="js"}{/block}