$(function(){

	$("li[data-active='"+daicuo.controll+daicuo.action+"']").toggleClass('active');
    
	daicuo.lazyload.init();
    
	daicuo.language.init({method:'auto'});
    
	daicuo.page.init();
    /*
    $(".dropdown").mouseover(function () {
        $('.dropdown-toggle').dropdown('show');
    });

    $(".dropdown").mouseleave(function(){
        $(this).dropdown('hide');
    });*/
});