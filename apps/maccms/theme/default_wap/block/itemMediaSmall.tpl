<div class="media mt-3">
  <a class="text-dark" href="{:playUrl( $maccms['play_last'], ['term_id'=>DcEmpty($term_id,$term['term_id']), 'term_slug'=>DcEmpty($term_slug,$term['term_slug'])]) }" target="_blank">
  	<img class="mr-3" src="{$path_root}public/images/y.gif" data-original="{$maccms.vod_cover}" alt="{$maccms.vod_title}" width="90" height="110">
  </a>
  <div class="media-body w-100">
    <h6 class="mt-0">
    	<a class="text-dark" href="{$maccms.play_last|playUrl}" target="_blank">{$maccms.vod_title}</a>
    </h6>
    <h6 class="text-break text-truncate text-muted small">
        {volist name="maccms.vod_year" id="year"}{$year} / {/volist}
        {volist name="maccms.vod_area" id="area"}{$area} / {/volist}
        {$maccms.episode_title}
    	<!--{$dc.updatetime} -->
    </h6>
    <h6 class="text-break text-truncate text-muted small">
    	{volist name="maccms.vod_actor" id="actor" offset="0" length="3"}<font class="mr-1">{$actor}</font>{/volist}
    </h6>
    <h6 class="m-0">
    	<a class="btn btn-outline-info btn-sm" href="{:playUrl( $maccms['play_last'], ['term_id'=>DcEmpty($term_id,$term['term_id']), 'term_slug'=>DcEmpty($term_slug,$term['term_slug'])]) }" target="_blank">免费观看 &raquo;</a>
    </h6>
  </div>
</div>