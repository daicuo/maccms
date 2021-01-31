{extend name="./public/static/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>泰剧天堂 - 海量好看的泰剧在线观看、最新泰剧下载!</title>
<meta name="keywords" content="人人泰剧,泰剧下载,天天泰剧,泰剧天堂,爱泰剧" />
<meta name="description" content="{:config('common.site_name')}一个可在线观看泰剧的视频网站，这里有最新最全的泰剧资源，采用云点播技术，给你高清流畅的观看体验。"  />
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