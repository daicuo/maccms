$(function(){

	$("li[data-active='"+daicuo.controll+daicuo.action+"']").toggleClass('active');
    
	daicuo.lazyload.image();
    
	daicuo.language.s2t();
    
	daicuo.page.click();
    /*
    $(".dropdown").mouseover(function () {
        $('.dropdown-toggle').dropdown('show');
    });

    $(".dropdown").mouseleave(function(){
        $(this).dropdown('hide');
    });*/
});