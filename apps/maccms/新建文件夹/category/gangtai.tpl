{extend name="./public/static/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>港台剧天堂 - 海量好看的港台剧在线观看、最新港台剧下载!</title>
<meta name="keywords" content="人人港台剧,港台剧下载,天天港台剧,港台剧天堂,爱港台剧" />
<meta name="description" content="{:config('common.site_name')}一个可在线观看港台剧的视频网站，这里有最新最全的港台剧资源，采用云点播技术，给你高清流畅的观看体验。"  />
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
<div class="row px-0 mt-2">
<div class="col-12 px-1">
<a class="btn btn-block btn-outline-secondary" data-toggle="pageClick" data-url="{:DcUrl('xiao/category/'.$action,['page'=>''],'')}" data-page="{$pageIndex}" data-target="#row">更多结果 <i class="fa fa-lg fa-angle-down"></i></a>
</div>
</div>
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}
{block name="js"}{/block}