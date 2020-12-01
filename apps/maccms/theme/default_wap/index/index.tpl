{extend name="./public/static/front.tpl" /}
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
<!-- -->
<div class="row px-0 pt-4 pb-1 text-center">
  {volist name=":categoryItem()" id="maccms" mod="5" offset="0" length="100"}
  <div class="col px-1">
    <a class="text-dark text-decoration-none" href="{:categoryUrl($maccms['term_id'],$maccms['term_slug'])}">
      {assign name="color" value=":colorRand(7)" /}
      <span class="fa-stack fa-lg text-{$color}">
        <i class="fa fa-circle-thin fa-stack-2x"></i>
        <i class="fa-fw fa-stack-1x {:faIcoRand()} text-{$color}"></i>
      </span>
      <p class="small mb-0 mt-1">{$maccms.term_name|DcSubstr=0,5,false}</p>
    </a>
  </div>
  {eq name="mod" value="4"}<div class="w-100 mb-2"></div>{/eq}
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