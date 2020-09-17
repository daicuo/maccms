$(function() {
    //扩展daicuo.admin	
    $.extend(daicuo.admin, {
        // 侧栏
        "sidebar": function() {
            $(document).on('click', '[data-toggle="main-left"]',
            function() {
                $('.main-left').toggleClass('open');
                $('.main-left').toggleClass('d-block');
                $('.main-right').toggleClass('col-12');
            });
        },
        // 导航模块
        "nav":{
            "typeChange": function($value){
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
        },
        "version": { //版本
            "compare": function($oldVersion, $newVersion, $updateUrl) { //版本比较 3位数
                $.ajax({
                    type: 'get',
                    cache: false,
                    url: daicuo.file + '/version/client/?old=' + $oldVersion + '&new=' + $newVersion,
                    dataType: 'json',
                    timeout: 3000,
                    success: function($json) {
                        if ($json.code == 1) {
                            $('[data-toggle="version"]').html('<a class="text-danger" href="' + $updateUrl + '" target="_blank">' + $json.msg + '</a>');
                        }
                    }
                });
            },
            "check": function($module) { //获取服务器最新版本jsonp格式
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
                        url: daicuo.config.file + '/version/server/?version=' + $version + '&module=' + $module,
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
            }
        }
    }); //extend
    daicuo.form.back();
    daicuo.form.reload();
    daicuo.form.create();
    daicuo.form.delete();
    daicuo.form.edit();
    daicuo.form.submit();
    daicuo.admin.sidebar();
    daicuo.admin.version.check();
    daicuo.json.beauty();
    daicuo.bootstrap.table.init({
        'urlSort': daicuo.config.file + '/' + daicuo.config.controll + '/sort?id=',
        'urlEdit': daicuo.config.file + '/' + daicuo.config.controll + '/edit?id=',
        'urlDelete': daicuo.config.file + '/' + daicuo.config.controll + '/delete?id='
    });
}); //jquery