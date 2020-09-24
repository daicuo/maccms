{extend name="./public/static/front.tpl" /}
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
<div class="jumbotron jumbotron-fluid pt-0 pb-0 mb-2">
    {$vod_player}
</div>
<p class="px-1">
  <a class="btn btn-block btn-purple text-light" href="http://cdn.youdu.com/jialin/02.png" target="_blank">
  <small>微信关注（回复片名 / 免费观看）永不迷路</small>
  </a>
</p>
<div class="px-1">
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
    {volist name="vod_year" id="year"}{$year} / {/volist}
    {volist name="vod_area" id="area"}{$area} / {/volist}
    <a class="text-muted" href="{:categoryUrl($term_id, $term_slug)}">{$type_name}</a>
  </h6>
</div>
<div class="container">
{assign name="n" value="1" /}
{foreach name="play_list" item="playOne" key="playFrom"}
<div class="row px-0">
  <div class="col-12 px-1 mt-3 mb-2">
    <fieldset>
      <legend class="h6 px-4">
        <i class="fa fa-play-circle-o text-purple"></i>
        <a class="text-dark text-decoration-none" href="javascript:;">播放地址{$n++}</a>
      </legend>
    </fieldset>
  </div>
</div>
<div class="row px-0">
{volist name="playOne" id="play"}
<div class="col-3 mb-2 px-1">
  <a class="text-truncate btn btn-sm btn-block btn-{:DcDefault($playFrom.$play_index, $play_from.$i, 'dark', 'secondary')}" href="{:playUrl(['tid'=>$type_id,'id'=>$vod_id,'ep'=>$i,'from'=>$playFrom])}"><small>{$play.play_title}</small></a>
</div>
{/volist}
</div>
{/foreach}
<!---->
<div class="row px-0">
  <div class="col-12 px-1 mt-3">
    <fieldset>
      <legend class="h6 px-4">
        <i class="fa fa-flag text-purple"></i>
        <a class="text-dark text-decoration-none" name="content">影片简介</a>
      </legend>
    </fieldset>
  </div>
  <div class="col-12 px-1">
    <p class="lead mb-1 pt-1">
      <small>{$vod_content|strip_tags|DcHtml}</small>
    </p>
    <p class="d-flex flex-row flex-wrap justify-content-between">
      <a class="mt-2 mr-2 badge badge-{:colorRand(7)} flex-fill" href="{:categoryUrl($term_id, $term_slug)}"><span class="fa fa-tag mr-1"></span>{$type_name}</a>
      {volist name="vod_year" id="year"}<a class="mt-2 mr-2 badge badge-{:colorRand(3)} flex-fill" href="{:DcUrl('maccms/search/year',['wd'=>$year,'page'=>1],'')}"><span class="fa fa-tag mr-1"></span>{$year}</a>{/volist}
      {volist name="vod_language" id="language" mod="2"}<a class="mt-2 mr-2 badge badge-{:colorRand(3)} flex-fill" href="{:DcUrl('maccms/search/language',['wd'=>$language,'page'=>1],'')}"><span class="fa fa-tag mr-1"></span>{$language}</a>{/volist}
      {volist name="vod_director" id="director"}<a class="mt-2 mr-2 badge badge-{:colorRand(3)} flex-fill" href="{:DcUrl('maccms/search/director',['wd'=>$director,'page'=>1],'')}"><span class="fa fa-tag mr-1"></span>{$director}</a>{/volist}
      {volist name="vod_actor" id="actor"}<a class="mt-2 mr-2 badge badge-{:colorRand(3)} flex-fill" href="{:DcUrl('maccms/search/actor',['wd'=>$actor,'page'=>1],'')}"><span class="fa fa-tag mr-1"></span>{$actor}</a>{/volist}
    </p>
  </div>
</div>
<!-- -->
<div class="row px-0">
  <div class="col-12 px-1 mt-3 mb-2">
    <fieldset>
      <legend class="h6 px-4">
        <i class="fa fa-fire text-purple"></i>
        <a class="text-dark text-decoration-none" href="{:DcUrl('maccms/category/'.$term_slug,['page'=>1],'')}">猜你喜欢</a>
      </legend>
    </fieldset>
  </div>
</div>
<div class="row px-0" data-api="filter" data-url="{:DcUrl('maccms/category/index',['id'=>$term_id,'length'=>9,'page'=>1],'')}">
  <p class="mx-auto"><span class="fa fa-spinner fa-spin"></span> loading...</p>
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
<div class="row px-0" data-api="filter" data-url="{:DcUrl('maccms/filter/lately',['length'=>9,'page'=>1],'')}">
  <p class="mx-auto"><span class="fa fa-spinner fa-spin"></span> loading...</p>
</div>
<!---->
</div><!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}
{block name="js"}{/block}