<div class="media pt-3">
  <a class="text-dark" href="{$maccms.play_last|playUrl}">
  	<img class="mr-3" src="{$path_root}public/images/y.gif" data-original="{$maccms.vod_cover}" alt="{$maccms.vod_title}" width="90" height="110">
  </a>
  <div class="media-body w-100">
    <h6 class="mt-0">
      <a class="text-dark" href="{$maccms.play_last|playUrl}">{$maccms.vod_title|DcSubstr=0,12,true}</a>
    </h6>
    <h6 class="text-break text-truncate text-muted small">
      {$maccms.episode_title|DcEmpty='正片'}
      {volist name="maccms.vod_year" id="year"} / {$year}{/volist}
      {volist name="maccms.vod_area" id="area"} / {$area}{/volist}
    </h6>
    <h6 class="text-break text-truncate text-muted small mb-3">
      {volist name="maccms.vod_actor" id="actor" offset="0" length="3"}<font class="mr-1">{$actor}</font>{/volist}
    </h6>
    <h6 class="m-0">
      <a class="btn btn-info btn-sm" href="{$maccms.play_last|playUrl}">免费观看 &raquo;</a>
    </h6>
  </div>
</div>