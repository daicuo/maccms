{extend name="./public/static/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>2020电影排行榜-2020电视剧排行榜-{:config('common.site_name')}</title>
<meta name="keywords" content="2020新片票房排行榜,2020最火电影排名,2020电影排行榜" />
<meta name="description" content="{:config('common.site_name')}提供当下热门的电影大片在线观看,同步更新全网热映大片,向网友提供丰富多彩的好莱坞电影、欧洲电影、日韩电影、华语电影等经典电影在线观看服务。"  />
{/block}
<!-- -->
{block name="header"}{include file="block/header" /}{/block}
<!-- -->
{block name="main"}
<!--<h5 class="bg-secondary text-center mb-0 py-2 text-light">排行榜</h5> -->
<div class="container bg-info pt-4 py-2">
<ul class="nav nav-justified mb-3 border rounded">
  <li class="nav-item">
    <a class="nav-link {eq name='action' value='index'}bg-white text-info{else/}text-light{/eq}" href="{:DcUrl('xiao/top/index',['length'=>'day'],'')}">综合</a>
  </li>
  <li class="nav-item text-light">
    <a class="nav-link {eq name='action' value='dianying'}bg-white text-info{else/}text-light{/eq}" href="{:DcUrl('xiao/top/dianying',['length'=>'day'],'')}">电影</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {eq name='action' value='dianshi'}bg-white text-info{else/}text-light{/eq}" href="{:DcUrl('xiao/top/dianshi',['length'=>'day'],'')}">电视</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {eq name='action' value='zongyi'}bg-white text-info{else/}text-light{/eq}" href="{:DcUrl('xiao/top/zongyi',['length'=>'day'],'')}">综艺</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {eq name='action' value='dongman'}bg-white text-info{else/}text-light{/eq}" href="{:DcUrl('xiao/top/dongman',['length'=>'day'],'')}">动漫</a>
  </li>
</ul>
<section class="bg-white rounded py-3 px-3">
<div class="row">
    <div class="col-12 text-right">
        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
          <a class="btn btn-outline-info {eq name='length' value='day'}active{/eq}" href="{:DcUrl('xiao/top/'.$action,['length'=>'day'],'')}">1天</a>
          <a class="btn btn-outline-info {eq name='length' value='week'}active{/eq}" href="{:DcUrl('xiao/top/'.$action,['length'=>'week'],'')}">7天</a>
          <a class="btn btn-outline-info {eq name='length' value='month'}active{/eq}" href="{:DcUrl('xiao/top/'.$action,['length'=>'month'],'')}">30天</a>
        </div>
    </div>
    <div class="col-12">
        {volist name=":xiaoItemTop($cateId, $length, 1, 3600)" id="dc"}
        {include file='block/itemMediaSmall' /}
        {/volist}
    </div>
</div>
</section>
<!-- -->
<!-- /container -->
</div>
{/block}
<!-- -->
{block name="footer"}{include file="block/footer" /}{/block}
{block name="js"}{/block}