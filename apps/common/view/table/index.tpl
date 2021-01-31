<div class="table-responsive-sm">
<table{volist name="table" id="tableAttr"} {if $key neq 'columns'} {$key}="{$tableAttr}"{/if} {/volist}>
  <thead>
    <tr>
      {volist name="table.columns" id="tr" key="key_tr"}
        <th{foreach name="tr" item="trAttrValue" key="trAttrKey"} {$trAttrKey}="{$trAttrValue}"{/foreach}>...</th>
      {/volist}
    </tr>
  </thead>
</table>
</div>