{foreach name="item" id="maccms" key="k"}
    {include file='block/itemRow' /}
    {php}if( ($length>0) && ($k+1 >= $length) ){break;}{/php}
{/foreach}