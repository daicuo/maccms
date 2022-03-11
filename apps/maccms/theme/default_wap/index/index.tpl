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
  {volist name=":navItem(['status'=>['eq','normal'],'action'=>['in',['index','ico']]])" id="navbar" mod="5" offset="0" length="10"}
  <div class="col px-1 mt-3">
    <a class="text-dark text-decoration-none" href="{$navbar.navs_link}" target="{$navbar.navs_target}">
      {assign name="color" value=":colorRand($key)" /}
      <span class="fa-stack fa-lg text-{$color}">
        <i class="fa fa-circle-thin fa-stack-2x"></i>
        <i class="fa fa-stack-1x text-{$color} {:DcEmpty($navbar['navs_class'],'fa-video-camera')}"></i>
      </span>
      <p class="small mb-0 mt-1">{$navbar.navs_name|DcSubstr=0,5,false}</p>
    </a>
  </div>
  {eq name="mod" value="4"}<div class="w-100"></div>{/eq}
  {/volist}
</div>
<!-- -->
<fieldset class="d-block mx-2 mt-3 mb-2">
  <legend class="h6 px-4">
    <i class="fa fa-line-chart text-danger mr-1"></i>
    <a class="text-decoration-none text-dark" href="{:DcUrl('maccms/filter/index')}">最近更新</a>
  </legend>
</fieldset>
<div class="row mx-1">
  {volist name="news" id="maccms" offset="0" length="12"}
    {include file='block/itemRow'/}
  {/volist}
</div>
<!-- -->
{volist name="categorys" id="term"}
{if $item = apiType($term['term_api_tid'], 6)}
<fieldset class="d-block mx-2 mt-3 mb-2">
  <legend class="h6 px-4">
    <i class="fa fa-line-chart text-danger mr-1"></i>
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
<fieldset class="d-block mx-2 mt-3 mb-2">
  <legend class="h6 px-4">
    <i class="fa fa-line-chart text-danger mr-1"></i>
    <a class="text-dark text-decoration-none" href="javascript:;">友情链接</a>
  </legend>
</fieldset>
<div class="row mx-1">
{volist name=":json_decode(config('maccms.link_index'),true)" id="dc" offset="0" length="12"}
<a class="col-3 text-muted" href="{$dc.url}" target="{$dc.target|default='_blank'}">{$dc.title}</a>
{/volist}
</div>
<!-- -->
</div>
{/block}
{block name="footer"}{include file="block/footer" /}{/block}