<nav class="navbar navbar-dark fixed-top bg-dark mx-auto">
<div class="container">
<ul class="nav d-flex justify-content-between w-100">
  <li class="nav-item">
    <a class="nav-link text-light" href="javascript:;" data-toggle="collapse" data-target="#nav"><i class="fa fa-lg fa-navicon"></i></a> 
  </li>
  <li class="nav-item">
    <a class="nav-link text-light font-weight-bold" href="{:DcUrl('maccms/index/index')}">{:config('common.site_name')}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link text-light" href="javascript:;" data-toggle="collapse" data-target="#search"><i class="fa fa-lg fa-search"></i></a> 
  </li>
</ul>

<div class="collapse navbar-collapse" id="search">
 <form class="px-3 py-3" action="{:DcUrl('maccms/search/index')}" method="get">
    <div class="input-group input-group-sm">
      <input class="form-control" name="wd" type="text" placeholder="片名或主演">
        <div class="input-group-append">
          <button class="btn btn-outline-light" type="submit"><i class="fa fa-search fa-lg"></i></button>
        </div>
    </div>
  </form>
</div>

<div class="collapse navbar-collapse" id="nav">
  <ul class="navbar-nav px-3">
  {volist name=":navItem(['type'=>'navbar','status'=>['eq','normal']])" id="navbar" offset="0" length="99"}
    <li class="nav-item" id="{$navbar.navs_active}" data-cid="{$navbar.navs_id}">
      <a class="nav-link" href="{$navbar.navs_link}" target="{$navbar.navs_target}">{$navbar.navs_name|DcSubstr=0,6,false}</a>
    </li>
    {volist name="navbar._child" id="navSon"}
      <li class="nav-item" id="{$navSon.navs_active}" data-cid="{$navSon.navs_id}">
      <a class="nav-link" href="{$navSon.navs_link}" target="{$navSon.navs_target}">{$navSon.navs_name|DcSubstr=0,6,false}</a>
      </li>
    {/volist}
  {/volist}
  </ul>
</div>
</div>
</nav>