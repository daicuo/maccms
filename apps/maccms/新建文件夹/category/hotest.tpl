{extend name="./public/static/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>2020热门电影-2020热门电视剧-{:config('common.site_name')}</title>
<meta name="keywords" content="2020新片票房排行榜,2020最火电影排名" />
<meta name="description" content="{:config('common.site_name')}提供当下热门的电影大片在线观看,同步更新全网热映大片,向网友提供丰富多彩的好莱坞电影、欧洲电影、日韩电影、华语电影等经典电影在线观看服务。"  />
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