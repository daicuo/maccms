<div class="col-12 col-md-4 mb-4">
<div class="media border rounded p-1 overflow-hidden h-100">
  <a href="{:DcUrl('xiao/play/index','id='.$dc['id'].'&ep='.count($dc['playlist']),'')}" target="_blank">
  	<img class="rounded img-cover" src="{$dc.image}" alt="{$dc.title}">
  </a>
  <div class="media-body ml-2 d-flex flex-column align-items-start h-100">
    <h6 class="mt-0 text-break text-nowrap font-weight-normal">
      <a class="text-success" href="{:DcUrl('xiao/play/index','id='.$dc['id'].'&ep='.count($dc['playlist']),'')}" target="_blank">{$dc.title}</a>
      <font class="text-muted small">{$dc.episode_status_title}</font>
    </h6>
    <h6 class="text-break text-truncate small ">
    	{$dc.areaname} / {$dc.yearname} / {$dc.updatetime}
    </h6>
    <h6 class="text-break small">
    	{volist name="dc.actors" id="actor" offset="0" length="4"}<font class="mr-1">{$actor.tagname}</font>{/volist}
    </h6>
    <div class="d-block d-md-none small text-muted">
    	{$dc.content|DcSubstr=0,78|DcHtml}
    </div>
    <div class="mt-auto">
    	<a class="btn btn-secondary btn-sm" href="{:DcUrl('xiao/play/index','id='.$dc['id'].'&ep='.count($dc['playlist']),'')}" target="_blank">免费观看 &raquo;</a>
    </div>
  </div>
</div>
</div>