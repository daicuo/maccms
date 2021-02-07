{extend name="./apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{$vod_title}{$play_title}-{:config('common.site_name')}</title>
<meta name="keywords" content="{$vod_title}{$play_name},{$vod_name}" />
<meta name="description" content="{$vod_content}"  />
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
{block name="main"}
<div class="container">
<!-- -->
<div class="jumbotron jumbotron-fluid pt-0 pb-0 mb-2">{$vod_player}</div>
<!-- -->
{if config("maccms.thread_pc")}
<div class="bg-white py-2 mb-2 rounded text-center">{:config("maccms.thread_pc")}</div>
{/if}
<!-- -->
{assign name="n" value="1" /}
{foreach name="play_list" item="playOne" key="playFrom"}
<div class="row mb-3" style="margin:0 -5px;">
  <div class="col-12 px-1">
    <div class="py-2 rounded bg-dark text-light pl-3">
      <i class="fa fa-play-circle-o text-warning"></i> 播放地址{$n++}
    </div>
  </div>
  {volist name=":DcArraySequence($playOne,'play_title','SORT_DESC')" id="play"}
  <div class="col-3 col-md-1 mt-2 px-1">
    <a class="text-truncate btn btn-sm btn-block btn-{:DcDefault($playFrom.$play['play_index'], $play_from.$play_index, 'dark', 'secondary')}" href="{:playUrl(['tid'=>$type_id,'id'=>$vod_id,'ep'=>$play['play_index'],'from'=>$playFrom],['term_id'=>$term_id,'term_slug'=>$term_slug])}"><small>{$play.play_title}</small></a>
  </div>
  {/volist}
</div>
{/foreach}
<!-- -->
<div class="card bg-dark mb-3">
  <div class="card-header text-white">
    <i class="fa fa-edit text-warning"></i> {$vod_title}
    <small class="text-muted ml-1">{$episode_status} {$episode_title|DcEmpty='完结'} {if $episode_total}/{$episode_total}{/if}</small>
  </div>
  <div class="card-body text-break pt-2 pb-0">
    <a class="mr-1 text-light" href="{:categoryUrl($term_id,$term_slug)}">{$term_name}</a>/
    {volist name="vod_area" id="area"}
    <a class="mr-1 text-light" href="{:DcUrl('maccms/filter/area',['id'=>$area,'page'=>1],'')}">{$area}</a>/
    {/volist}
    {volist name="vod_year" id="year"}
    <a class="mr-1 text-light" href="{:DcUrl('maccms/filter/year',['id'=>$year,'page'=>1],'')}">{$year}</a>/
    {/volist}
    {volist name="vod_language" id="language"}
    <a class="mr-1 text-light" href="{:DcUrl('maccms/filter/language',['id'=>$language,'page'=>1],'')}">{$language}</a>/
    {/volist} 
    <span class="text-light">{$vod_updatetime}</span>
  </div>
  <div class="card-body pt-2 pb-0">
    {volist name="vod_actor" id="actor"}<a class="mr-2 text-light" href="{:DcUrl('maccms/filter/actor',['id'=>$actor],'')}">{$actor}</a>{/volist}
    {volist name="vod_director" id="director"}<a class="mr-2 text-light" href="{:DcUrl('maccms/filter/director',['id'=>$director],'')}">{$director}</a>{/volist}
  </div>
  <div class="card-body pt-2">
    {$vod_content}
  </div> 
</div>
<!-- -->
<div class="row">
  <div class="col-12">
    <div class="py-2 mb-2 rounded bg-dark text-light pl-3">
      <i class="fa fa-heart text-warning"></i> 猜你喜欢
    </div>
  </div>
{volist name=":apiTermIdLimit($term_id, 12)" id="dc" offset="0" length="12"}
{include file='block/itemRow' /}
{/volist}
</div>
<!--container -->
</div>
{/block}
{block name="footer"}{include file="block/footer" /}{/block}
{block name="js"}
<script>$("li[data-cid='{$cateid}']").addClass('active');</script>
{/block}