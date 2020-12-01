<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
<div class="container">
  <a class="navbar-brand" href="{:DcUrl('maccms/index/index','','')}">{:config('common.site_name')}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#xiaonav" aria-controls="xiaonav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="xiaonav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item {:DcDefault($controll.$action, 'indexindex', 'active', '')}">
        <a class="nav-link" href="{:DcUrl('maccms/index/index','','')}">首页 <span class="sr-only">(current)</span></a>
      </li>
      {volist name=":navItem(['limit'=>6])" id="maccms" offset="0" length="6"}
      <li class="nav-item {:DcDefault($controll.$action, $maccms['nav_active'], 'active', '')}">
        <a class="nav-link" href="{$maccms.nav_link}">{$maccms.nav_text|DcSubstr=0,5,false}</a>
      </li>
      {/volist}
    </ul>
    <form class="form-inline my-2 my-lg-0" action="{:DcUrl('maccms/search/index','','')}" method="get">
      <div class="input-group mr-sm-2">
        <input class="form-control" name="wd" type="text" placeholder="片名或主演">
        <div class="input-group-append">
          <button class="btn btn-outline-light" type="submit"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </form>
  </div>
</div>  
</nav>