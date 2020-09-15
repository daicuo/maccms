<div class="table-responsive-sm">
<table{volist name="table" id="tableAttr"} {$key}="{$tableAttr}"{/volist}>
    <thead>
        <tr>
        {volist name="table.columns" id="tr" key="key_tr"}
		    <th{foreach name="tr" item="trAttrValue" key="trAttrKey"} {$trAttrKey}="{$trAttrValue}"{/foreach}>...</th>
		{/volist}
        </tr>
    </thead>
</table>
</div>