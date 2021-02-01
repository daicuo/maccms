{extend name="./apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{$term_name}第{$current_page|default='1'}页-{:config('common.site_name')}</title>
<meta name="keywords" content="{$term_slug},{$term_name}" />
<meta name="description" content="{$term_much_info|default='好看免费无广告'}"  />
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="jumbotron jumbotron-fluid p-2 mb-2 text-center text-purple">
	共找到{$total}条，当前第{$current_page}页，共有{$last_page}页
</div>
<div class="container">
<!-- -->
<div class="row px-0" id="row">
    {volist name="item" id="maccms"}
        {include file='block/itemRow' /}
    {/volist}
</div>
<!--page start -->
<div class="row px-0 mt-2">
    <div class="col-12 px-1">
        <a class="btn btn-block btn-outline-secondary" data-toggle="pageClick" data-pageScroll="true" data-url="{:DcUrl('maccms/category/'.$action,['id'=>$term_id,'limit'=>intval($term_api_limit)],'')}&page=" data-page="{$current_page}" data-target="#row">更多结果 <i class="fa fa-lg fa-angle-down"></i></a>
    </div>
</div>
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}
{block name="js"}{/block}