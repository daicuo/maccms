{if config("maccms.footer_pc")}
<div class="container text-center mb-2">
  <div class="bg-white py-2 rounded">{:config("maccms.footer_pc")}</div>
</div>
{/if}
<hr />
<!-- -->
<div class="container">
<div class="row">
{volist name=":json_decode(config('maccms.link_footer'),true)" id="dc" offset="0" length="12"}
<div class="col-6 col-md-4">
  <h6><a class="text-muted" href="{$dc.url}" target="{$dc.target|default='_blank'}">{$dc.title}</a></h6>
  <p class="small">{$dc.describe}</p>
</div>
{/volist}
</div>
<!-- -->
<p class="text-center mb-0">
  {volist name=":categoryItem()" id="maccms" offset="0" length="12"}
  <a class="text-muted small" href="{:categoryUrl($maccms['term_id'],$maccms['term_slug'])}">{$maccms.term_name|DcSubstr=0,5,false}</a>
  {/volist}
</p>
<!-- -->
<p class="text-center">
  Copyright © 2020-2021 {:config('common.site_domain')} All rights reserved
</p>

</div>