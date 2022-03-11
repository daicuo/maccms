<div class="container pt-3">
  {if config("maccms.footer_wap")}
  <p class="text-center px-2 mb-2">{:posterParse("maccms.footer_wap")}</p>
  {/if}
  {volist name=":json_decode(config('maccms.link_footer'),true)" id="dc" offset="0" length="12"}
  <a class="text-dark w-100 mx-3" href="{$dc.url}" target="{$dc.target|default='_blank'}">{$dc.title}</a>
  <p class="text-muted small mx-3">{$dc.describe}</p>
  {/volist}
  <p class="text-muted small mx-3">
    Copyright © 2019-2022 {:config('common.site_domain')} All rights reserved
  </p>
</div>