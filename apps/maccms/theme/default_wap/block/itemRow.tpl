<div class="col-4 mb-3 px-1">
  <div class="d-flex justify-content-end align-items-end">
      <a href="{:playUrl($maccms['play_last'],$term_id)}">
        <img class="rounded img-cover" src="{$path_root}public/images/y.gif" data-original="{$maccms.vod_cover|imageUrl}" alt="{$maccms.vod_title}">
      </a>
      <div class="position-absolute text-light px-2 bg-secondary small dc-opacity text-truncate w-75 text-right">{$maccms.episode_title|default='完结'}</div>
  </div>
  <h6 class="text-truncate text-center pt-2 my-0"><a class="text-dark" href="{$maccms.play_last|playUrl}">{$maccms.vod_title}</a></h6>
</div>