<div class="col-4 col-md-2 mb-2 mb-md-4 px-2 px-md-3">
  <div class="card rounded-0 border-0 bg-transparent">
    <a class="text-light text-decoration-none" href="{:playUrl($dc['play_last'], ['term_id'=>DcEmpty($term_id,$term['term_id']), 'term_slug'=>DcEmpty($term_slug,$term['term_slug'])]) }">
      <span class="position-absolute w-100 h-100 bg-play"></span>
      <img class="w-100 h-row rounded-top" src="{$path_root}public/images/y.gif" data-original="{$dc.vod_cover|imageUrl}" alt="{$dc.vod_title}">
      <p class="bg-dark rounded-bottom text-center text-truncate py-1 px-2 mb-0 h7">
        {$dc.vod_title}
      </p>
    </a>
  </div>
</div>