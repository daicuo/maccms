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
<div id="dcSlide" class="carousel slide">
  <ol class="carousel-indicators">
    {volist name=":json_decode(config('maccms.slide_index_m'),true)" id="dc" offset="0" length="8"}
    <li data-target="#dcSlide" data-slide-to="{$key}" {eq name="key" value="0"}class="active"{/eq}></li>
    {/volist}
  </ol>
  <div class="carousel-inner">
    {volist name=":json_decode(config('maccms.slide_index'),true)" id="dc" offset="0" length="8"}
    <div class="carousel-item {eq name="key" value="0"}active{/eq}">
     <a href="{$dc.url}">
     <img src="{$dc.image}" class="d-block w-100 img-slide" alt="{$dc.title}">
     </a>
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
<!-- -->
<div class="row px-0 text-center mb-2">
  {volist name=":navItem(['limit'=>10,'where'=>['op_module'=>['eq','maccms']]])" id="maccms" mod="5" offset="0" length="10"}
  <div class="col px-1 mt-3">
    <a class="text-dark text-decoration-none" href="{$maccms.nav_link}">
      {assign name="color" value=":colorRand($key)" /}
      <span class="fa-stack fa-lg text-{$color}">
        <i class="fa fa-circle-thin fa-stack-2x"></i>
        <i class="fa-fw fa-stack-1x text-{$color} {$maccms.nav_ico}"></i>
      </span>
      <p class="small mb-0 mt-1">{$maccms.nav_text|DcSubstr=0,5,false}</p>
    </a>
  </div>
  {eq name="mod" value="4"}<div class="w-100"></div>{/eq}
  {/volist}
</div>
<!-- -->
{volist name=":categoryItem()" id="term" offset="0" length="5"}
{if $item = apiTermIdLimit($term['term_id'], 6)}
<fieldset class="mx-2 mt-2">
  <legend class="h6 px-4 mb-2">
    <i class="fa fa-line-chart text-info"></i>
    <a class="text-dark text-decoration-none" href="{:categoryUrl($term['term_id'],$term['term_slug'])}">{$term.term_name|DcSubstr=0,5,false}</a>
  </legend>
</fieldset>
<div class="row mx-1">
  {volist name="$item" id="maccms" offset="0" length="6"}
    {include file='block/itemRow'/}
  {/volist}
</div>
{/if}
{/volist}
<!-- -->
<fieldset class="mx-2 mt-2">
  <legend class="h6 px-4 mb-2">
    <i class="fa fa-line-chart text-info"></i>
    <a class="text-dark text-decoration-none" href="{:DcUrl('maccms/filter/lately',['page'=>1],'')}">最近更新</a>
  </legend>
</fieldset>
<div class="row mx-1" id="row" data-api="filter" data-url="{:DcUrl('maccms/filter/lately',['page'=>1],'')}">
  <p class="mx-auto"><span class="fa fa-spinner fa-spin"></span> loading...</p>
</div>
<!--page start -->
<div class="row mx-2 mt-2">
  <div class="col-12 px-1">
    <a class="btn btn-block btn-outline-dark" data-toggle="pageClick" data-pageScroll="true" data-url="{:DcUrl('maccms/filter/lately',['page'=>''],'')}" data-page="1" data-target="#row">查看更多 <i class="fa fa-lg fa-angle-down"></i></a>
  </div>
</div>
<!-- -->
<fieldset class="mx-2 mt-3">
  <legend class="h6 px-4 mb-2">
    <i class="fa fa-line-chart text-info"></i>
    <a class="text-dark text-decoration-none" href="javascript:;">友情链接</a>
  </legend>
</fieldset>
<div class="row mx-1">
{volist name=":json_decode(config('maccms.link_index'),true)" id="dc" offset="0" length="12"}
<div class="col-4 col-md-2">
  <h6><a class="text-muted" href="{$dc.url}" target="{$dc.target|default='_blank'}">{$dc.title}</a></h6>
</div>
{/volist}
</div>
<!-- -->
</div>
{/block}
{block name="footer"}{include file="block/footer" /}{/block}
<!-- -->
{block name="js"}{/block}