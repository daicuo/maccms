<div class="container">
  <hr />
  <div class="row mx-1">
    {if config("maccms.footer_wap")}
    <div class="col-12 text-center px-1 mb-2">{:posterParse("maccms.footer_wap")}</div>
    {/if}
    {volist name=":json_decode(config('maccms.link_footer'),true)" id="dc" offset="0" length="12"}
    <div class="col-6 px-1">
      <h6><a class="text-muted" href="{$dc.url}" target="{$dc.target|default='_blank'}">{$dc.title}</a></h6>
      <p class="small">{$dc.describe}</p>
    </div>
    {/volist}
  </div>
  <p class="text-center">
    Copyright © 2019-2020 {:config('common.site_domain')} All rights reserved
  </p>
</div>