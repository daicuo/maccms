<div class="media mb-3">
	<a class="text-light" href="{$dc.play_last|playUrl}">
	<img class="mr-2 rounded" src="{$path_root}public/images/y.gif" data-original="{$dc.vod_cover|imageUrl}" alt="{$dc.vod_title}" width="100" height="130">
    </a>
    <div class="media-body">
        <h6><a class="text-light" href="{$dc.play_last|playUrl}">{$dc.vod_title}</a></h6>
        <h6>{volist name="dc.vod_actor" id="actor" offset="0" length="8"}<a class="mt-2 mr-2 text-light small" href="{:DcUrl('maccms/search/index',['wd'=>$actor['tagname']],'')}">{$actor}</a>{/volist}</h6>
        <h6 class="small">{$dc.type_name} / {$dc.vod_area|implode=' / '} / {$dc.vod_year|implode=' / '}</h6>
    	<p class="mb-0 d-none d-md-block small">{$dc.vod_content|DcSubstr=0,148|DcHtml}</p>
    </div> 
</div>