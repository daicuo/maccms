<div class="col-4 mb-2 px-1">
  <div class="d-flex justify-content-end align-items-end position-relative">
    <a href="{:playUrl($maccms['play_last'])}">
      <img class="rounded img-cover" src="{$path_root}public/images/y.gif" data-original="{$maccms.vod_cover|imageUrl}" alt="{$maccms.vod_title}">
    </a>
    <div class="position-absolute text-light px-2 py-1 bg-secondary small dc-opacity text-truncate w-100 text-right">
      {$maccms.episode_title|DcEmpty='完结'}
    </div>
  </div>
  <p class="text-truncate text-center h7 py-1 mb-0">
    <a class="text-dark" href="{:playUrl($maccms['play_last'])}">{$maccms.vod_title}</a>
  </p>
</div>