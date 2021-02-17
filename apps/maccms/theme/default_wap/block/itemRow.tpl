<div class="col-4 mb-3 px-1">
  <div class="d-flex justify-content-end align-items-end position-relative">
    <a href="{:playUrl($maccms['play_last'], ['term_id'=>DcEmpty($term_id,$term['term_id']), 'term_slug'=>DcEmpty($term_slug,$term['term_slug'])]) }">
      <img class="rounded img-cover" src="{$path_root}public/images/y.gif" data-original="{$maccms.vod_cover|imageUrl}" alt="{$maccms.vod_title}">
    </a>
    <div class="position-absolute text-light px-2 py-1 bg-secondary small dc-opacity text-truncate w-100 text-right">
      {$maccms.episode_title|DcEmpty='完结'}
    </div>
  </div>
  <h6 class="text-truncate text-center pt-2 my-0">
    <a class="text-dark" href="{:playUrl( $maccms['play_last'], ['term_id'=>DcEmpty($term_id,$term['term_id']), 'term_slug'=>DcEmpty($term_slug,$term['term_slug'])]) }">{$maccms.vod_title}</a>
  </h6>
</div>