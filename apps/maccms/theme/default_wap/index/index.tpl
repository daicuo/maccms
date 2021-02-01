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
<div id="dcSlide" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    {volist name=":config('slide')" id="dc" offset="0" length="8"}
    <li data-target="#dcSlide" data-slide-to="{$key}" {eq name="key" value="0"}class="active"{/eq}></li>
    {/volist}
  </ol>
  <div class="carousel-inner">
    {volist name=":config('slide')" id="dc" offset="0" length="8"}
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
<div class="container">
<!-- -->
<div class="row px-0 text-center">
  {volist name=":navItem(['limit'=>10])" id="maccms" mod="5" offset="0" length="10"}
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
<div class="row px-0">
  <div class="col-12 px-1 mt-3 mb-2">
    <fieldset>
      <legend class="h6 px-4">
        <i class="fa fa-line-chart text-purple"></i>
        <a class="text-dark text-decoration-none" href="{:DcUrl('maccms/filter/lately',['page'=>1],'')}">最近更新</a>
      </legend>
    </fieldset>
  </div>
</div>
<div class="row px-0" id="row" data-api="filter" data-url="{:DcUrl('maccms/filter/lately',['page'=>1],'')}">
  <p class="mx-auto"><span class="fa fa-spinner fa-spin"></span> loading...</p>
</div>
<!--page start -->
<div class="row px-0 mt-2">
    <div class="col-12 px-1">
      <a class="btn btn-block btn-outline-secondary" data-toggle="pageClick" data-pageScroll="true" data-url="{:DcUrl('maccms/filter/lately',['page'=>''],'')}" data-page="1" data-target="#row">查看更多 <i class="fa fa-lg fa-angle-down"></i></a>
    </div>
</div>
<!-- -->
</div>
{/block}
{block name="footer"}{include file="block/footer" /}{/block}
<!-- -->
{block name="js"}{/block}