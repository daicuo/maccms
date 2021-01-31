$(function() {
    //扩展daicuo.admin
    $.extend(daicuo.admin, {
        // 初始化
        init: function(){
            daicuo.admin.sideBar();
            daicuo.admin.versionCheck();
        },
        // 侧栏
        sideBar: function() {
            $(document).on('click', '[data-toggle="main-left"]', function() {
                $('.main-left').toggleClass('open');
                $('.main-left').toggleClass('d-block');
                $('.main-right').toggleClass('col-12');
            });
        },
        // 版本检测
        versionCheck: function($module) { //获取服务器最新版本jsonp格式
            var $dom = new Array();
            $('[data-toggle="version"]').each(function(key, value) {
                $dom[key] = $(this);
                $version = $dom[key].attr('data-version');
                $module = $dom[key].attr('data-module');
                if (!$version || !$module) {
                    $this.removeClass();
                    return false;
                }
                //后端请求防止恶意JS
                $.ajax({
                    type: 'get',
                    cache: false,
                    url: daicuo.config.file + '/version/index/?version=' + $version + '&module=' + $module,
                    dataType: 'json',
                    timeout: 3000,
                    success: function($json) {
                        if ($json.code == 1) {
                            $dom[key].html('<a class="text-danger" href="' + $json.update + '" target="_blank">' + $json.msg + '</a>');
                        }
                    }
                });
                $dom[key].removeClass();
            });
        },
        // 导航模块
        navType: function($value){
            if($value == 'addon'){
                $('.dc-modal #nav_url').parents('.form-group').addClass('d-none');
                $('.dc-modal #nav_module').parents('.form-group').removeClass('d-none');
                $('.dc-modal #nav_controll').parents('.form-group').removeClass('d-none');
                $('.dc-modal #nav_action').parents('.form-group').removeClass('d-none');
                $('.dc-modal #nav_params').parents('.form-group').removeClass('d-none');
                $('.dc-modal #nav_suffix').parents('.form-group').removeClass('d-none');
            }else{
                $('.dc-modal #nav_url').parents('.form-group').removeClass('d-none');
                $('.dc-modal #nav_module').parents('.form-group').toggleClass('d-none',true);
                $('.dc-modal #nav_controll').parents('.form-group').toggleClass('d-none',true);
                $('.dc-modal #nav_action').parents('.form-group').toggleClass('d-none',true);
                $('.dc-modal #nav_params').parents('.form-group').toggleClass('d-none',true);
                $('.dc-modal #nav_suffix').parents('.form-group').toggleClass('d-none',true);
            }
        }
    }); //extend
    window.daicuo.admin.init();
    window.daicuo.ajax.init();
    window.daicuo.form.init();
    window.daicuo.upload.init();
    window.daicuo.json.init();
    window.daicuo.table.init({
        'urlSort': window.daicuo.config.file + '/' + window.daicuo.config.controll + '/sort?id=',
        'urlEdit': window.daicuo.config.file + '/' + window.daicuo.config.controll + '/edit?id=',
        'urlDelete': window.daicuo.config.file + '/' + window.daicuo.config.controll + '/delete?id='
    });
}); //jquery