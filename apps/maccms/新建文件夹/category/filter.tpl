{extend name="./public/static/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{gt name="yearId" value="0"}{:DcArraySearch($filter['year'],['id'=>$yearId],'name')}{/gt}
{:DcArraySearch($filter['sort'],['id'=>$sortId],'name')}
{gt name="areaId" value="0"}{:DcArraySearch($filter['area'],['id'=>$areaId],'name')}{/gt}
{gt name="tagId" value="0"}{:DcArraySearch($filter['tag'],['id'=>$tagId],'name')}{/gt}
{gt name="languageId" value="0"}{:DcArraySearch($filter['language'],['id'=>$languageId],'name')}{/gt}
{gt name="cateId" value="0"}{:DcArraySearch($filter['cate'],['id'=>$cateId],'name')}{else/}影片{/gt}筛选结果第{$pageIndex}页-{:config('common.site_name')}</title>
<meta name="keywords" content="最新好看的电视,最新热播的电视剧" />
<meta name="description" content="最新电视顾名思义的意思是最新和最近上映的电视，每一年，每个月最新电视的上映，大片、科幻、搞笑、武侠、动作片等等，最新最好看的电视网站提供给网民观看"  />
{/block}
{block name="header_more"}
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="card mb-2 rounded-0 position-sticky">
    <div class="card-header">
    	<h6 class="d-flex m-0 p-0 justify-content-between">
    	<span id="tips">已为您找到<strong class="mx-2 text-purple">{$totalRecord}</strong>条相关结果</span>
        <a class="text-dark text-decoration-none" href="javascript:;" data-toggle="collapse" data-target="#card-body">
          <i class="fa fa-filter text-purple"></i>
          <span data-toggle="collapse-tips">收起</span>
        </a>
        </h6>
    </div>
    <div class="collapse show pb-2" id="card-body">
        <div class="card-body text-nowrap pt-2 pb-0" data-toggle="carousel">
            {volist name="filter.cate" id="cate"}
            <a class="carousel-cell text-decoration-none pr-4 {:DcDefault($cateId,$cate['id'],'text-purple active','text-dark')}" href="{:DcUrl('xiao/category/filter',['cateId'=>$cate['id'],'areaId'=>$areaId,'yearId'=>$yearId,'tagId'=>$tagId,'languageId'=>$languageId,'sortId'=>$sortId,'page'=>1],'')}">{$cate.name}</a>
            {/volist}
        </div>         
        <div class="card-body text-nowrap pt-2 pb-0" data-toggle="carousel">
            {volist name="filter.year" id="year"}
            <a class="carousel-cell text-decoration-none pr-4 {:DcDefault($yearId,$year['id'],'text-purple active','text-dark')}" href="{:DcUrl('xiao/category/filter',['cateId'=>$cateId,'areaId'=>$areaId,'yearId'=>$year['id'],'tagId'=>$tagId,'languageId'=>$languageId,'sortId'=>$sortId,'page'=>1],'')}">{$year.name}</a>
            {/volist}
        </div>                
        <div class="card-body text-nowrap pt-2 pb-0" data-toggle="carousel">
            {volist name="filter.area" id="area" offset="0" length="20"}
            <a class="carousel-cell text-decoration-none pr-4 {:DcDefault($areaId,$area['id'],'text-purple active','text-dark')}" href="{:DcUrl('xiao/category/filter',['cateId'=>$cateId,'areaId'=>$area['id'],'yearId'=>$yearId,'tagId'=>$tagId,'languageId'=>$languageId,'sortId'=>$sortId,'page'=>1],'')}">{$area.name}</a>
            {/volist}
        </div>
        <div class="card-body text-nowrap pt-2 pb-0" data-toggle="carousel">
            {volist name="filter.tag" id="tag" offset="0" length="20"}
            <a class="carousel-cell text-decoration-none pr-4 {:DcDefault($tagId,$tag['id'],'text-purple active','text-dark')}" href="{:DcUrl('xiao/category/filter',['cateId'=>$cateId,'areaId'=>$areaId,'yearId'=>$yearId,'tagId'=>$tag['id'],'languageId'=>$languageId,'sortId'=>$sortId,'page'=>1],'')}">{$tag.name}</a>
            {/volist}
        </div>
        <div class="card-body text-nowrap pt-2 pb-0" data-toggle="carousel">
            {volist name="filter.language" id="language" offset="0" length="20"}
            <a class="carousel-cell text-decoration-none pr-4 {:DcDefault($languageId,$language['id'],'text-purple active','text-dark')}" href="{:DcUrl('xiao/category/filter',['cateId'=>$cateId,'areaId'=>$areaId,'yearId'=>$yearId,'tagId'=>$tagId,'languageId'=>$language['id'],'sortId'=>$sortId,'page'=>1],'')}">{$language.name}</a>
            {/volist}
        </div>
        <div class="card-body text-nowrap pt-2 pb-0" data-toggle="carousel">
            {volist name="filter.sort" id="sort"}
            <a class="carousel-cell text-decoration-none pr-4 {:DcDefault($sortId,$sort['id'],'text-purple active','text-dark')}" href="{:DcUrl('xiao/category/filter',['cateId'=>$cateId,'areaId'=>$areaId,'yearId'=>$yearId,'tagId'=>$tagId,'languageId'=>$languageId,'sortId'=>$sort['id'],'page'=>1],'')}">{$sort.name}</a>
            {/volist}
        </div>      
    </div>
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
<a class="btn btn-block btn-outline-secondary" data-toggle="pageClick" data-pageScroll="true" data-url="{:DcUrl('xiao/category/filter',['cateId'=>$cateId,'areaId'=>$areaId,'yearId'=>$yearId,'tagId'=>$tagId,'languageId'=>$languageId,'sortId'=>$sortId,'page'=>''],'')}" data-page="{$pageIndex}" data-target="#row">更多结果 <i class="fa fa-lg fa-angle-down"></i></a>
</div>
</div>
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}
{block name="js"}
<script>
daicuo.carousel.nav();
$('#card-body').on('shown.bs.collapse', function () {
	daicuo.carousel.resize();
	$('[data-toggle="collapse-tips"]').text('收起');
});
$('#card-body').on('hidden.bs.collapse', function () {
	$('[data-toggle="collapse-tips"]').text('展开');
});
</script>
{/block}