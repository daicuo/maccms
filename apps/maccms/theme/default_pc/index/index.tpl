{extend name="./apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:config('maccms.site_title')}-{:config('common.site_name')}</title>
<meta name="keywords" content="{:config('maccms.site_keywords')}" />
<meta name="description" content="{:config('maccms.site_description')}"  />
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="container">
<div class="row px-md-2 mb-3">
<div class="col-12 col-md-9 px-2">
    <div id="dcSlide" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        {volist name=":json_decode(config('maccms.slide_index'),true)" id="dc" offset="0" length="8"}
        <li data-target="#dcSlide" data-slide-to="{$key}" {eq name="key" value="0"}class="active"{/eq}></li>
        {/volist}
      </ol>
      <div class="carousel-inner rounded">
        {volist name=":json_decode(config('maccms.slide_index'),true)" id="dc" offset="0" length="8"}
        <div class="carousel-item {eq name="key" value="0"}active{/eq}">
          <a href="{$dc.url}"><img class="d-block w-100 h-slide" src="{$dc.image}" alt="{$dc.title}"></a>
        </div>
        {/volist}
      </div>
      <a class="carousel-control-prev" href="#dcSlide" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#dcSlide" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
</div>
<div class="col-12 col-md-3 px-2 d-none d-md-inline">
  <ul class="list-group h-slide overflow-hidden">
  {volist name="news" id="dc" offset="0" length="10"}
  <li class="list-group-item bg-dark d-flex justify-content-between align-items-center py-2">
    <a class="text-light small" href="{$dc.play_last|playUrl}">{$dc.vod_title|DcSubstr=0,14}</a>
    <span class="badge badge-light badge-pill">{$key+1}</span>
  </li>
  {/volist}
  </ul>
</div>
</div>
<!-- -->
{if count($news) > 10}
<div class="row mb-2">
  <div class="col-12 mb-2">
    <i class="fa fa-line-chart text-warning"></i>
    <a class="text-light text-decoration-none" href="{:DcUrl('maccms/filter/index')}">最近更新</a>
  </div>
{volist name="news" id="dc" offset="10" length="6"}
  {include file='block/itemRow' /}
{/volist}
</div>
{/if}
<!-- -->
{volist name="categorys" id="term"}
{if $item = apiType($term['term_api_tid'], 6)}
<div class="row mb-2">
  <div class="col-12 mb-2">
    <i class="fa fa-line-chart text-warning"></i> 
    <a class="text-light text-decoration-none" href="{:categoryUrl($term['term_id'],$term['term_slug'])}">2021最新{$term.term_name|DcSubstr=0,5,false}</a>
  </div>
  {volist name="$item" id="dc" offset="0" length="6"}
    {include file='block/itemRow'/}
  {/volist}
</div>
{/if}
{/volist}
<!-- -->
<div class="row mb-2">
  <div class="col-12 mb-2">
    <i class="fa fa-line-chart text-warning"></i> 
    <a class="text-light text-decoration-none" href="javascript:;">友情链接</a>
  </div>
{volist name=":json_decode(config('maccms.link_index'),true)" id="dc" offset="0" length="12"}
<div class="col-4 col-md-2">
  <h6><a class="text-muted" href="{$dc.url}" target="{$dc.target|default='_blank'}">{$dc.title}</a></h6>
</div>
{/volist}
</div>
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}