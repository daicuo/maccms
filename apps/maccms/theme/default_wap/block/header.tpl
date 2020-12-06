<ul class="nav bg-dark justify-content-between py-1">
    <li class="nav-item">
        <a class="nav-link text-light" href="javascript:;" data-toggle="collapse" data-target="#nav"><i class="fa fa-lg fa-navicon"></i></a> 
    </li>
    <li class="nav-item">
        <a class="nav-link text-light font-weight-bold" href="{:DcUrl('maccms/index/index','','')}">{:config('common.site_name')}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-light" href="javascript:;" data-toggle="collapse" data-target="#search"><i class="fa fa-lg fa-search"></i></a> 
    </li>
</ul>
<div class="collapse" id="search">
    <form class="bg-secondary px-3 py-3" action="{:DcUrl('maccms/search/index','','')}" method="get">
        <div class="input-group input-group-sm">
            <input class="form-control" name="wd" type="text" placeholder="片名或主演">
            <div class="input-group-append">
                <button class="btn btn-outline-light" type="submit"><i class="fa fa-search fa-lg"></i></button>
            </div>
        </div>
    </form>
</div>
<div class="collapse" id="nav">
    <div class="list-group list-group-flush">
        {volist name=":navItem()" id="maccms" mod="5"}
        <a class="list-group-item list-group-item-action list-group-item-dark" href="{$maccms.nav_link}" target="{$maccms.nav_target}">{$maccms.nav_text}</a>
        {/volist}
    </div>
</div>