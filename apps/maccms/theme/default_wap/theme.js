$(function(){
	$.extend(daicuo.fn, {
        filter : function(){
            $('[data-api="filter"]').each(function() {
                var $target = $(this);
                var $url = $(this).attr('data-url');
                daicuo.ajax.get($url, function($data, $status, $xhr){
                    if ($data) {
                        $target.html($data);
                        $target.find('img[data-original]').lazyload();
                        //$($target + ' img[data-original]').lazyload();
                        //daicuo.lazyload.dom($target);
                        //daicuo.language.dom($target);
                    }
                });
            });
        }
	});
	daicuo.lazyload.image();
	daicuo.language.s2t();
	daicuo.page.click();
	daicuo.page.scroll();
    daicuo.fn.filter();
});