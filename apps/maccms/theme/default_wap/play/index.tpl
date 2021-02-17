{extend name="./apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{$vod_title}{$play_title}-{:config('common.site_name')}</title>
<meta name="keywords" content="{$vod_title}{$play_name},{$vod_name}" />
<meta name="description" content="{$vod_content}"  />
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<div class="container">
{if config("maccms.header_wap")}
<div class="mb-0">
  {:config("maccms.header_wap")}
</div>
{/if}
<!---->
<div class="jumbotron jumbotron-fluid pt-0 pb-0 mb-2">
  {$vod_player}
</div>
<!---->
{if config("maccms.center_wap")}
<p class="mx-2">
  {:posterParse("maccms.center_wap")}
</p>
{/if}
<!---->
<div class="mx-2 mb-3">
  <h6 class="text-truncate d-flex flex-row justify-content-between">
    <div>
      <span class="text-dark">{$vod_title}</span>
      <small class="text-muted">{$episode_title}</small>
    </div>
    <a class="small text-muted text-decoration-none mr-1" href="#content">
      简介<span class="fa fa-angle-double-right"></span>
    </a>
  </h6>
  <h6 class="text-truncate text-muted small">
    <a class="mr-1 text-muted" href="{:categoryUrl($term_id,$term_slug)}">{$term_name}</a>/
    {volist name="vod_area" id="area"}
    <a class="mr-1 text-muted" href="{:DcUrl('maccms/filter/area',['id'=>$area,'page'=>1],'')}">{$area}</a>/
    {/volist}
    {volist name="vod_year" id="year"}
    <a class="mr-1 text-muted" href="{:DcUrl('maccms/filter/year',['id'=>$year,'page'=>1],'')}">{$year}</a>/
    {/volist}
    <span>{$vod_updatetime}</span>
  </h6>
</div>
<!---->
{assign name="n" value="1" /}
{foreach name="play_list" item="playOne" key="playFrom"}
<fieldset class="mx-2 mb-2">
  <legend class="h6 px-4">
    <i class="fa fa-play-circle-o text-info"></i>
    <a class="text-dark text-decoration-none" href="javascript:;">播放地址{$n++}</a>
  </legend>
</fieldset>
<div class="row mx-1 mb-1">
{volist name=":DcArraySequence($playOne,'play_title','SORT_DESC')" id="play"}
<div class="col-3 mb-2 px-1">
  <a class="text-truncate btn btn-sm btn-block btn-{:DcDefault($playFrom.$play['play_index'], $play_from.$play_index, 'dark', 'secondary')}" href="{:playUrl(['tid'=>$type_id,'id'=>$vod_id,'ep'=>$play['play_index'],'from'=>$playFrom],['term_id'=>$term_id,'term_slug'=>$term_slug])}"><small>{$play.play_title}</small></a>
</div>
{/volist}
</div>
{/foreach}
<!---->
<fieldset class="mx-2">
  <legend class="h6 px-4">
    <i class="fa fa-flag text-info"></i>
    <a class="text-dark text-decoration-none" name="content">影片简介</a>
  </legend>
</fieldset>
<div class="row mx-1 mb-2">
  <div class="col-12 px-1">
    <p class="lead mb-1">
      <small>{$vod_content|DcStrip|DcHtml}</small>
    </p>
    <p class="d-flex flex-row flex-wrap justify-content-between">
      <a class="mt-2 mr-2 badge badge-{:colorRand(7)} flex-fill" href="{:categoryUrl($term_id, $term_slug)}"><span class="fa fa-tag mr-1"></span>{$term_name}</a>
      {volist name="vod_year" id="year"}<a class="mt-2 mr-2 badge badge-{:colorRand(3)} flex-fill" href="{:DcUrl('maccms/filter/year',['id'=>$year,'page'=>1],'')}"><span class="fa fa-tag mr-1"></span>{$year}</a>{/volist}
      {volist name="vod_language" id="language" mod="2"}<a class="mt-2 mr-2 badge badge-{:colorRand(3)} flex-fill" href="{:DcUrl('maccms/filter/language',['id'=>$language,'page'=>1],'')}"><span class="fa fa-tag mr-1"></span>{$language}</a>{/volist}
      {volist name="vod_director" id="director"}<a class="mt-2 mr-2 badge badge-{:colorRand(3)} flex-fill" href="{:DcUrl('maccms/filter/director',['id'=>$director,'page'=>1],'')}"><span class="fa fa-tag mr-1"></span>{$director}</a>{/volist}
      {volist name="vod_actor" id="actor"}<a class="mt-2 mr-2 badge badge-{:colorRand(3)} flex-fill" href="{:DcUrl('maccms/filter/actor',['id'=>$actor,'page'=>1],'')}"><span class="fa fa-tag mr-1"></span>{$actor}</a>{/volist}
    </p>
  </div>
</div>
<!-- -->
<fieldset class="mx-2 mb-2">
  <legend class="h6 px-4">
    <i class="fa fa-fire text-purple"></i>
    <a class="text-dark text-decoration-none" href="{:DcUrl('maccms/category/'.$term_slug,['page'=>1],'')}">猜你喜欢</a>
  </legend>
</fieldset>
<div class="row mx-1 mb-1" data-api="filter" data-url="{:DcUrl('maccms/category/index',['id'=>$term_id,'length'=>9,'page'=>1],'')}">
  <p class="mx-auto"><span class="fa fa-spinner fa-spin"></span> loading...</p>
</div>
<!-- -->
<fieldset class="mx-2 mb-2">
  <legend class="h6 px-4">
    <i class="fa fa-line-chart text-purple"></i>
    <a class="text-dark text-decoration-none" href="{:DcUrl('maccms/filter/lately',['page'=>1],'')}">最近更新</a>
  </legend>
</fieldset>
<div class="row mx-1 mb-1" data-api="filter" data-url="{:DcUrl('maccms/filter/lately',['length'=>9,'page'=>1],'')}">
  <p class="mx-auto"><span class="fa fa-spinner fa-spin"></span> loading...</p>
</div>
<!---->
</div><!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}
{block name="js"}{/block}