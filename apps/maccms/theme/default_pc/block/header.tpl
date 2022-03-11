<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
<div class="container">
  <a class="navbar-brand" href="{:DcUrl('maccms/index/index')}">{:config('common.site_name')}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="nav">
    <ul class="navbar-nav mr-auto">
      {volist name=":navItem(['type'=>'navbar','status'=>['eq','normal']])" id="navbar" offset="0" length="99"}
      {if $navbar['_child']}
        <li class="nav-item dropdown {:DcDefault($module.$controll.$action, $navbar['navs_active'], 'active', 'nav-'.$key)}" id="{$navbar.navs_active|default='nav-'.$key}" data-cid="{$navbar.navs_id}">
          <a class="nav-link dropdown-toggle" href="{$navbar.navs_link}" data-toggle="dropdown">{$navbar.navs_name|DcSubstr=0,6,false}</a>
          <div class="dropdown-menu mt-0">
            {volist name="navbar._child" id="navSon"}
            <a class="dropdown-item" href="{$navSon.navs_link}" target="{$navSon.navs_target}">{$navSon.navs_name|DcSubstr=0,6,false}</a>
            {/volist}
          </div>
        </li>
      {else/}
        <li class="nav-item {:DcDefault($module.$controll.$action, $navbar['navs_active'], 'active', '')}" id="{$navbar.navs_active}" data-cid="{$navbar.navs_id}">
          <a class="nav-link" href="{$navbar.navs_link}" target="{$navbar.navs_target}">{$navbar.navs_name|DcSubstr=0,6,false}</a>
        </li>
      {/if}
      {/volist}
    </ul>
    <form class="form-inline my-2 my-lg-0" action="{:DcUrl('maccms/search/index')}" method="get">
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
  <div class="bg-white py-2 rounded">{:posterParse("maccms.header_pc")}</div>
</div>
{/if}