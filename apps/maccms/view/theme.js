//提交绑定的回调函数
var callAjax = function($data, $status, $xhr){
    setTimeout('$(".dc-modal").modal("hide");location.href="../category/index"', 1000);
};
//监听分析资源站事件
$(document).on("click", '.api-add', function() {
    var $apiUrl = $('#api_url').val();
    if($apiUrl){
        daicuo.bootstrap.dialog('<span class="fa fa-spinner fa-spin"></span> Loading...');
        daicuo.ajax.get('../addon/index?module=maccms&controll=type&action=index&apiurl='+$apiUrl, function($data, $status, $xhr){
            //回调显示表单
            daicuo.bootstrap.dialogForm($data);
            //监听提交绑定的事件
            $(document).on('submit', '.form-bind[data-toggle="form"]', function(){
                $(this).html('<span class="fa fa-spinner fa-spin"></span> Loading...');
            });
        });
    }else{
        daicuo.bootstrap.dialog('请先添加资源站地址后再使用此功能！'); 
    }
    $('.modal-dialog').addClass('modal-dialog-scrollable modal-lg');
});