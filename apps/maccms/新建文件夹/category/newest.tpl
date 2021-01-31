{extend name="./public/static/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>2020年最新电影-2020年最新电视剧-{:config('common.site_name')}</title>
<meta name="keywords" content="最新好看的电影,最新热播的电视剧" />
<meta name="description" content="最新电影顾名思义的意思是最新和最近上映的电影，每一年，每个月最新电影的上映，大片、科幻、搞笑、武侠、动作片等等，最新最好看的电影网站提供给网民观看"  />
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