<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
<div class="container">
  <a class="navbar-brand" href="{:DcUrl('maccms/index/index','','')}">{:config('common.site_name')}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="nav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item {:DcDefault($controll.$action, 'indexindex', 'active', '')}">
        <a class="nav-link" href="{:DcUrl('maccms/index/index','','')}">首页 <span class="sr-only">(current)</span></a>
      </li>
      {volist name=":navItem(['limit'=>10])" id="maccms" offset="0" length="10"}
      {if $maccms['_child']}
        <li class="nav-item {:DcDefault($controll.$action, $maccms['nav_active'], 'active', '')} position-relative">
          <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">{$maccms.nav_text|DcSubstr=0,5,false}</a>
          <div class="dropdown-menu">
            {volist name="maccms._child" id="navSon"}
            <a class="dropdown-item" href="{$navSon.nav_link}" target="{$navSon.nav_target}">{$navSon.nav_text|DcSubstr=0,5,false}</a>
            {/volist}
          </div>
        </li>
      {else/}
        <li class="nav-item {:DcDefault($controll.$action, $maccms['nav_active'], 'active', '')}">
          <a class="nav-link" href="{$maccms.nav_link}" target="{$maccms.nav_target}">{$maccms.nav_text|DcSubstr=0,5,false}</a>
        </li>
      {/if}
      {/volist}
    </ul>
    <form class="form-inline my-2 my-lg-0" action="{:DcUrl('maccms/search/index','','')}" method="get">
      <div class="input-group mr-sm-2 mr-md-0">
        <input class="form-control" name="wd" type="text" placeholder="片名或主演">
        <div class="input-group-append">
          <button class="btn btn-outline-light" type="submit"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </form>
  </div>
</div>
</nav>
{if config("maccms.header_pc")}
<div class="container text-center mb-2">
  <div class="bg-white py-2 rounded">{:config("maccms.header_pc")}</div>
</div>
{/if}