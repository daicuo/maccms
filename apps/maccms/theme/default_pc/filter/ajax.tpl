{foreach name="item" id="dc" key="k"}
  {include file='block/itemRow' /}
  {php}if( ($length>0) && ($k+1 >= $length) ){break;}{/php}
{/foreach}