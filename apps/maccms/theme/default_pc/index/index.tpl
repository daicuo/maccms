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
<div class="row px-md-2 mb-3">
<div class="col-12 col-md-9 px-2 px-md-2">
    <div id="dcSlide" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        {volist name=":config('slide')" id="dc" offset="0" length="8"}
        <li data-target="#dcSlide" data-slide-to="{$key}" {eq name="key" value="0"}class="active"{/eq}></li>
        {/volist}
      </ol>
      <div class="carousel-inner rounded">
        {volist name=":config('slide')" id="dc" offset="0" length="8"}
        <div class="carousel-item {eq name="key" value="0"}active{/eq}">
            <a href="{$dc.url}">
            <img class="d-block w-100 h-slide" src="{$dc.image}" alt="{$dc.title}">
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
</div>
<div class="col-12 col-md-3 px-2 px-md-3 d-none d-md-inline">
  <ul class="list-group h-slide overflow-hidden">
  {volist name=":apiNew(22)" id="dc" offset="0" length="10"}
  <li class="list-group-item bg-dark d-flex justify-content-between align-items-center py-2">
    <a class="text-light small" href="{$dc.play_last|playUrl}">{$dc.vod_title}</a>
    <span class="badge badge-light badge-pill">{$key+1}</span>
  </li>
  {/volist}
  </ul>
</div>
</div>
<!-- -->
{volist name=":categoryItem()" id="term" mod="5" offset="0" length="6"}
{if $item = apiTermIdLimit($term['term_id'], 6)}
<div class="row mb-2">
  <div class="col-12 px-2 px-md-3 mb-2">
    <i class="fa fa-line-chart text-warning"></i> 
    <a class="text-light text-decoration-none" href="{:categoryUrl($term['term_id'],$term['term_slug'])}">2020最新{$term.term_name|DcSubstr=0,5,false}</a>
  </div>
  {volist name="$item" id="dc" offset="0" length="6"}
    {include file='block/itemRow' /}
  {/volist}
</div>
{/if}
{/volist}
<!-- -->
<div class="row mb-2">
  <div class="col-12 px-2 px-md-3 mb-2">
    <i class="fa fa-line-chart text-warning"></i>
    <a class="text-light text-decoration-none" href="{:DcUrl('maccms/filter/lately','','')}">最近更新</a>
  </div>
{volist name=":apiNew(22)" id="dc" offset="10" length="12"}
  {include file='block/itemRow' /}
{/volist}
</div>
<!-- -->
<div class="row">
<div class="col-md-4">
  <h4>2020免费电影</h4>
  <p>提供2020年上映的免费电影，即不收费就可观看和下载的电影。随着Internet的快速发展，许多网站都陆续都推出了。小小影视网正是这样一家网站，本站纯属爱好制作，不接受任何商业广告。</p>
</div>
<div class="col-md-4">
  <h4>2020最新电影</h4>
  <p>提供2020年高清电影，2020年最新电视剧，好看的西瓜影音，百度影音电影和免费快播电影,最新最全的大片资源，都是高质量的精选电影，一定满足不同口味的观众需求。 </p>
</div>
<div class="col-md-4">
  <h4>2020好看电影</h4>
  <p>免费的清晰度不太高影视业务，当然也包括免费的视频网站，免费电影可以用搜索引擎搜索，一般情况下是在搜索框中输入"免费电影"、"免费视频"等关键词，便有不少互联网的免费电影站点</p>
</div>
</div>
<!-- -->
</div> <!-- /container -->
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}