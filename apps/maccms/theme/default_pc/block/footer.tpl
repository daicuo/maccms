<hr />
<p class="text-center mb-0">
  <a class="text-muted small" href="http://{:config('common.site_domain')}">2020免费电影</a>
  <a class="text-muted small" href="http://{:config('common.site_domain')}">2020最新电影</a>
  <a class="text-muted small" href="http://{:config('common.site_domain')}">2020好看电影</a>
  <a class="text-muted small" href="http://{:config('common.site_domain')}">2020热门电影</a>
</p>
<p class="text-center mb-0">
  {volist name=":categoryItem()" id="maccms" offset="0" length="12"}
    <a class="text-muted small" href="{:categoryUrl($maccms['term_id'],$maccms['term_slug'])}">{$maccms.term_name|DcSubstr=0,5,false}</a>
  {/volist}
</p>
<p class="text-center">
	Copyright © 2019-2020 {:config('common.site_domain')} All rights reserved
</p>