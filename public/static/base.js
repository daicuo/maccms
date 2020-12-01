window.daicuo = {
    // 后台扩展对象
    admin: {},
    // 语言包扩展对象
    lang: {},
    // 插件扩展对象
    fn: {},
    // 根据自定义属性动态配置变量
    config: function() {
        var obj = {};
        $.each($('script[data-id="daicuo"]').get(0).attributes,
        function() {
            if (this.specified) {
                if (this.name.indexOf('data-') > -1) {
                    obj[this.name.replace(/^data-/, '')] = this.value; //this.nodeName+this.nodeValue
                }
            }
        });
        return obj; //alert(JSON.stringify(obj));
    } (),
    // bootstrap组件常用方法
    bootstrap: {
        // 对话框
        "dialog": {
            "tips": function($html) {
                $('.dc-modal .modal-dialog').removeClass('modal-lg').html('<div class="modal-content"><div class="modal-header pt-2 pb-1"><h6 class="my-0 py-0">' + daicuo.lang.modalTitle + '</h6><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-primary">&times;</span></button></div><div class="modal-body"><h5 class="text-center py-3 text-dark">' + $html + '</h5></div></div>');
                $('.dc-modal').modal('show');
                //$('.dc-modal .modal-dialog').toggleClass('modal-dialog-centered',true);
            },
            "form": function($html) {
                $('.dc-modal .modal-dialog').html($html);
                $('.dc-modal').modal('show');
            }
        },
        // 表格
        "table": {
            // 插件配置
            "option": {
                "toggle": 'table[data-toggle="bootstrap-table"]',
                "urlSort": '',
                "urlEdit": '',
                "urlDelete": '',
                "urlPreview": '',
            },
            // 插件初始化
            "init": function($option, $callback) {
                //daicuo.config.file+'/'+daicuo.config.controll+'/edit?id=';
                $.extend(daicuo.bootstrap.table.option, $option);
                if ($(daicuo.bootstrap.table.option.toggle).get(0)) {
                    daicuo.bootstrap.table.ajax($callback);
                };
            },
            // data-formatter 拖拽排序
            "sort": function(value, row, index, field) {
                return '<i class="fa fa-arrows-alt fa-lg text-purple dc-handle"></i>';
            },
            // data-formatter 操作图标
            "operation": function(value, row, index, field) {
                //return value+row+index+field;
                var id = row[field.replace('_operation', '')]; //row.op_id
                var html = [];
                html.push('<div class="btn-group btn-group-sm">');
                if (daicuo.bootstrap.table.option.urlPreview) {
                    html.push('<a class="btn btn-outline-secondary bg-light" href="' + daicuo.bootstrap.table.option.urlPreview + id + '" data-toggle="link"><i class="fa fa-fw fa-link"></i></a>');
                }
                if (daicuo.bootstrap.table.option.urlEdit) {
                    html.push('<a class="btn btn-outline-secondary bg-light" href="' + daicuo.bootstrap.table.option.urlEdit + id + '" data-toggle="edit"><i class="fa fa-fw fa-pencil"></i></a>');
                }
                if (daicuo.bootstrap.table.option.urlDelete) {
                    html.push('<a class="btn btn-outline-secondary" href="' + daicuo.bootstrap.table.option.urlDelete + id + '" data-toggle="delete"><i class="fa fa-fw fa-trash-o"></i></a>');
                }
                html.push('</div>');
                return html.join('');
            },
            // 动态加载bootstrap-table
            "ajax": function($callback) {
                daicuo.ajax.css("//lib.baomitu.com/bootstrap-table/1.15.5/bootstrap-table.min.css");
                daicuo.ajax.script("//lib.baomitu.com/bootstrap-table/1.15.5/bootstrap-table.min.js",
                function() {
                    daicuo.ajax.script("//lib.baomitu.com/bootstrap-table/1.15.5/bootstrap-table-locale-all.min.js",
                    function() {
                        //创建表单动态数据
                        $(daicuo.bootstrap.table.option.toggle).bootstrapTable();
                        //数据创建成功
                        $(daicuo.bootstrap.table.option.toggle).on('load-success.bs.table',
                        function($data, $status, $xhr) {
                            //是否加载拖拽排序插件
                            if (daicuo.bootstrap.table.option.urlSort) {
                                daicuo.sortable.init($('tbody').get(0), {
                                    dataIdAttr: 'data-uniqueid',
                                    onEnd: function(evt) {
                                        $.get(daicuo.bootstrap.table.option.urlSort + daicuo.sortable.obj.toArray().join(','));
                                    }
                                });
                            }
                            //是否回调
                            if (typeof($callback) == "function") {
                                $callback();
                            }
                        });
                        //数据创建失败
                        $(daicuo.bootstrap.table.option.toggle).on('load-error.bs.table',
                        function($status, $xhr) {
                            daicuo.bootstrap.dialog.tips(daicuo.lang.ajaxError);
                        });
                    });
                });
            }
        }
    },
    // 拖拽排序
    sortable: {
        'obj': '',
        "option": {
            handle: '.dc-handle',
            //draggable: ".item",
            dataIdAttr: 'data-id',
            ghostClass: 'bg-secondary',
            onStart: function(evt) {
                //
            },
            onEnd: function(evt) {
                $(evt.item).css({
                    'transform': 'none'
                });
            }
        },
        "init": function($el, $option) {
            $.extend(this.option, $option);
            daicuo.ajax.script('//lib.baomitu.com/Sortable/1.10.0/Sortable.min.js',
            function() {
                daicuo.sortable.obj = Sortable.create($el, daicuo.sortable.option);
            });
        }
    },
    // 轮播滑动
    carousel: {
        nav: function($target) {
            $target = $target || '[data-toggle="carousel"]'; //if ($target === undefined)
            daicuo.carousel.ajax(function() {
                $($target).each(function(i) {
                    $index = $(this).find('.active').index() * 1;
                    if ($index > 3) {
                        $index = $index - 3;
                    } else {
                        $index = 0;
                    }
                    $(this).flickity({
                        initialIndex: $index,
                        freeScroll: true,
                        cellAlign: "left",
                        resize: true,
                        contain: true,
                        lazyLoad: true,
                        prevNextButtons: false,
                        pageDots: false
                    });
                });
            });
        },
        resize: function($target) { //resize
            $target = $target || '[data-toggle="carousel"]';
            if (daicuo.carousel.ajaxEnd) {

                $($target).flickity('resize');
            }
        },
        ajaxEnd: false,
        //JS是否加载完成
        ajax: function($callback) {
            //动态插入CSS
            if (!daicuo.carousel.ajaxEnd) {
                $("<link>").attr({
                    rel: "stylesheet",
                    type: "text/css",
                    href: "//lib.baomitu.com/flickity/2.2.0/flickity.min.css"
                }).appendTo("head");
            }
            //动态加载JS
            $.ajaxSetup({
                cache: true
            });
            $.getScript("//lib.baomitu.com/flickity/2.2.0/flickity.pkgd.min.js",
            function(data, status, jqxhr) {
                daicuo.carousel.ajaxEnd = true;
                if (typeof($callback) == "function") {
                    $callback();
                }
            });
        }
    },
    // 延迟加载
    lazyload: {
        image: function() { //初始图片
            daicuo.lazyload.ajax(function() {
                $("img[data-original]").lazyload({
                    placeholder: daicuo.root + "public/images/grey.gif",
                    effect: "fadeIn",
                    failurelimit: 10
                    //threshold : 400
                    //skip_invisible : false
                    //container: $(".carousel-inner"),
                });
            });
        },
        dom: function($target) { //dom操作
            if (daicuo.lazyload.ajaxEnd) {
                $($target + ' img[data-original]').lazyload();
            }
        },
        ajaxEnd: false,
        //JS是否加载完成
        ajax: function($callback) {
            $.ajaxSetup({
                cache: true
            });
            $.getScript("//lib.baomitu.com/jquery.lazyload/1.9.1/jquery.lazyload.min.js",
            function(data, status, jqxhr) {
                daicuo.lazyload.ajaxEnd = true;
                if (typeof($callback) == "function") {
                    $callback();
                }
            });
        }
    },
    // 简繁转换API
    language: {
        type: 's2t',
        s2t: function($target) { //简转繁
            if ($target == undefined) {
                $target = document.body;
            }
            if (daicuo.browser.language == 'zh-hk' || daicuo.browser.language == 'zh-tw') {
                daicuo.language.type = 's2t';
                daicuo.language.ajax(function() {
                    $($target).s2t();
                });
            }
        },
        t2s: function($target) { //繁转简
            if ($target == undefined) {
                $target = document.body;
            }
            if (daicuo.browser.language == 'zh-cn') {
                daicuo.language.type = 't2s';
                daicuo.language.ajax(function() {
                    $($target).t2s();
                });
            }
        },
        dom: function($target) { //Dom更新后需重新激活
            if (daicuo.language.ajaxEnd) {
                if (daicuo.language.type == 's2t') {
                    if (daicuo.browser.language == 'zh-hk' || daicuo.browser.language == 'zh-tw') {
                        $($target).s2t();
                    }
                } else {
                    if (daicuo.browser.language == 'zh-cn') {
                        $($target).t2s();
                    }
                }
            }
        },
        ajaxEnd: false,
        //JS是否加载完成
        ajax: function($callback) {
            $.ajaxSetup({
                cache: true
            });
            $.getScript("//cdn.daicuo.cc/jquery.s2t/0.1.0/s2t.min.js",
            function(data, status, jqxhr) {
                daicuo.language.ajaxEnd = true;
                if (typeof($callback) == "function") {
                    $callback();
                }
            });
        }
    },
    // AJAX翻页
    page: {
        locked: false,
        click: function($callback) {
            $(document).on("click", '[data-toggle="pageClick"],[data-pageClick]',
            function() {
                if (daicuo.page.locked == false) {
                    daicuo.page.ajax($(this), $(this).attr('data-url'), $(this).attr('data-page') * 1 + 1, $(this).attr('data-target'), $callback);
                }
            });
        },
        scroll: function($callback) {
            $obj = $('[data-toggle="pageScroll"],[data-pageScroll]').eq(0);
            if ($obj.length) {
                $(window).bind("scroll",
                function() {
                    if (daicuo.page.locked == false && ($(this).scrollTop() + $(window).height()) >= $(document).height()) {
                        daicuo.page.ajax($obj, $obj.attr('data-url'), $obj.attr('data-page') * 1 + 1, $obj.attr('data-target'), $callback);
                    }
                });
            }
        },
        ajax: function($event, $url, $page, $target, $callback) {
            $html = $event.html();
            $(document).ajaxStart(function() {
                daicuo.page.locked = true;
                $event.html(daicuo.lang.loading);
            });
            $.get($url + $page,
            function($data) {
                daicuo.page.locked = false;
                $event.html($html);
                if ($data) {
                    $($target).append($data);
                    $event.attr("data-page", $page);
                    //window.history.pushState(null, null, $url+$page);
                    //图片懒加载
                    daicuo.lazyload.dom($target);
                    //是否简繁转化
                    daicuo.language.dom($target);
                    //回调事件
                    if (typeof($callback) == "function") {
                        $callback($event, $url, $page, $target, $data);
                    }
                } else {
                    $event.remove();
                    $event.unbind("click");
                }
            });
        }
    },
    // JSON处理
    json: {
        // 美化
        "beauty": function() {
            $('[data-toggle="json"]').each(function() {
                var jsonText = $(this).val();
                $(this).val(JSON.stringify(JSON.parse(jsonText), null, 4));
            });
        },
        // 丑化
        "ugly": function() {
            $('[data-toggle="json"]').each(function() {
                var jsonText = $(this).val();
                $(this).val(JSON.stringify(JSON.parse(jsonText)));
            });
        }
    },
    // 多媒体对象
    media: {
        'video': function() {
            if (DcPlayer. in =='false') {
                document.write('<iframe class="embed-responsive-item" src="https://hao.daicuo.cc/player/outplay/?url=' + encodeURIComponent(DcPlayer.url) + '" width="100%" height="100%" frameborder="0" scrolling="no" allowfullscreen="true"></iframe>');
            } else {
                if (DcPlayer.ai) {
                    $src = DcPlayer.ai + DcPlayer.url + '&json=';
                    delete DcPlayer['ai'];
                    delete DcPlayer['in'];
                    delete DcPlayer['url'];
                    document.write('<iframe class="embed-responsive-item" src="' + $src + encodeURIComponent(JSON.stringify(DcPlayer)) + '" width="100%" height="100%" frameborder="0" scrolling="no" allowfullscreen="true"></iframe>');
                } else {
                    //document.write('<script src="https://hao.daicuo.cc/player/?type=' + DcPlayer.type + '&url=' + DcPlayer.url + '"></script>');
                    daicuo.ajax.script('https://hao.daicuo.cc/player.1.3/?type=' + DcPlayer.type + '&url=' + DcPlayer.url, function(){
                        daicuo.media.yun.init(DcPlayer);
                    });
                }
            }
        }
    },
    // 表单常用
    form: {
        // 后退
        "back": function() {
            $(document).on("click", '[data-toggle="back"]',
            function() {
                window.history.back(); //history.go(-1);
            });
        },
        // 刷新
        "reload": function() {
            $(document).on("click", '[data-toggle="reload"]',
            function() {
                location.reload();
            });
        },
        // 新增
        "create": function() {
            $(document).on("click", '[data-toggle="create"]',
            function() {
                if( $(this).attr('data-modal-lg')=='true'){
                    $('.modal-dialog').addClass('modal-dialog-scrollable modal-lg');
                }
                daicuo.ajax.get($(this).attr('href'), $(this).attr('data-formatter'));
                return false;
            });
        },
        // 删除
        "delete": function() {
            $(document).on("click", '[data-toggle="delete"]',
            function() {
                if ($(this).attr('href') === undefined){
                    return confirm($(this).text() + daicuo.lang.confirm);
                }
                //AJAX请求删除页
                if( confirm($(this).text() + daicuo.lang.confirm) ){
                    daicuo.ajax.get($(this).attr('href'));
                }
                return false;
            });
        },
        // 编辑
        "edit": function() {
            $(document).on('click', '[data-toggle="edit"]',
            function() {
                if( $(this).attr('data-modal-lg')=='true' ){
                    $('.modal-dialog').addClass('modal-dialog-scrollable modal-lg');
                }
                daicuo.ajax.get($(this).attr('href'), $(this).attr('data-formatter'));
                return false;
            });
        },
        // 提交
        "submit": function() {
            $(document).on('submit', '[data-toggle="form"]',
            function() {
                $form = $(this);
                if ($(this).attr('data-callback')) {
                    daicuo.ajax.post($(this).attr('action'), $(this).serialize(), $(this).attr('data-callback'));
                } else {
                    daicuo.ajax.post($(this).attr('action'), $(this).serialize(),
                    function($data, $status, $xhr) {
                        if ($data.code == 1) {
                            daicuo.bootstrap.dialog.tips($data.msg);
                            setTimeout('$(".dc-modal").modal("hide");location.reload()', 1000);
                        }else{
                            //根据验证失败的提示信息检查字段
                            $msg = $data.msg.split('%');
                            if($msg.length > 1){
                                //client
                                $form.removeClass('was-validated');
                                $form.find('.valid').removeClass('valid');
                                $form.find('.invalid').removeClass('invalid');
                                //server
                                $form.find('.is-invalid').removeClass('is-invalid').addClass('is-valid');
                                $form.find('.invalid-feedback').remove();
                                $form.find('#'+$msg[0]).removeClass('is-valid').addClass('is-invalid');
                                $form.find('#'+$msg[0]).after('<div class="invalid-feedback">'+$msg[1]+'</div>');
                            }else{
                                daicuo.bootstrap.dialog.tips($msg);
                            }
                        }
                    });
                };
                return false;
            });
        }
    },
    // ajax请求封装
    ajax: {
        // 字符串的方式回调任意函数
        'callBack': function($data, $status, $xhr, $callback) {
            $callback($data, $status, $xhr);
        },
        // 浮动窗口
        "get": function($url, $callback) {
            $.ajax({
                type: 'get',
                cache: false,
                url: $url,
                //dataType: 'html',
                timeout: 5000,
                error: function() {
                    daicuo.bootstrap.dialog.tips(daicuo.lang.ajaxError); //location.reload();
                },
                success: function($data, $status, $xhr) {
                    if( $.isPlainObject($data) ){
                        daicuo.bootstrap.dialog.tips($data.msg);
                        if($data.code == 1){
                            setTimeout('$(".dc-modal").modal("hide");location.reload()', 1000);
                        }
                    }else{
                        daicuo.bootstrap.dialog.form($data);
                        if (typeof($callback) == "function") {
                            $callback($data, $status, $xhr);
                        } else if (typeof($callback) == "string") {
                            daicuo.ajax.callBack($data, $status, $xhr, new Function('return (' + $callback + ')')());
                        }
                    }
                },
                complete: function(xhr) {}
            });
        },
        // 表单提交
        "post": function($url, $data, $callback) {
            $.ajax({
                url: $url,
                type: 'post',
                dataType: 'json',
                timeout: 5000,
                data: $data,
                error: function() {
                    daicuo.bootstrap.dialog.tips(daicuo.lang.ajaxError);
                },
                success: function($data, $status, $xhr) {
                    //daicuo.bootstrap.dialog.tips($data.msg);
                    if (typeof($callback) == "function") {
                        $callback($data, $status, $xhr);
                    } else if (typeof($callback) == "string") {
                        daicuo.ajax.callBack($data, $status, $xhr, new Function('return (' + $callback + ')')());
                    }
                },
                complete: function(xhr) {}
            });
        },
        // css样式表
        "css": function($url, $callback) {
            $("<link>").attr({
                rel: "stylesheet",
                type: "text/css",
                href: $url
            }).appendTo("head");
            if (typeof($callback) == "function") {
                $callback();
            }
        },
        // 动态加载脚本
        "script": function($url, $callback) {
            $.ajax({
                cache: true,
                url: $url,
                dataType: "script",
                success: function(script, textStatus, jqXHR) {
                    if (typeof($callback) == "function") {
                        $callback();
                    }
                }
            });
        }
    },
    // 浏览器对象属性
    browser: {
        'url': document.URL,
        'domain': document.domain,
        'title': document.title,
        // zh-tw|zh-hk|zh-cn
        'language': (navigator.browserLanguage || navigator.language).toLowerCase(),
        // 浏览器控制台调试
        'console': function(obj) {
            $.each(obj,
            function(key, val) {
                console.log(key); //alert(obj[key]);
            });
        },
        // 是否支持画图
        'canvas': function() {
            return !! document.createElement('canvas').getContext;
        } (),
        useragent: function() {
            var ua = navigator.userAgent; //navigator.appVersion
            return {
                'language': (navigator.browserLanguage || navigator.language).toLowerCase(),
                //zh-tw|zh-hk|zh-cn
                'mobile': !!ua.match(/AppleWebKit.*Mobile.*/),
                //是否为移动终端 
                'ios': !!ua.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
                //ios终端
                'android': ua.indexOf('Android') > -1 || ua.indexOf('Linux') > -1,
                //android终端或者uc浏览器 
                'iPhone': ua.indexOf('iPhone') > -1 || ua.indexOf('Mac') > -1,
                //是否为iPhone或者QQHD浏览器 
                'iPad': ua.indexOf('iPad') > -1,
                //是否iPad
                'trident': ua.indexOf('Trident') > -1,
                //IE内核
                'presto': ua.indexOf('Presto') > -1,
                //opera内核
                'webKit': ua.indexOf('AppleWebKit') > -1,
                //苹果、谷歌内核
                'gecko': ua.indexOf('Gecko') > -1 && ua.indexOf('KHTML') == -1,
                //火狐内核 
                'weixin': ua.indexOf('MicroMessenger') > -1 //是否微信 ua.match(/MicroMessenger/i) == "micromessenger",		
            };
        } ()
    },
    event: { //事件列表
        click: function() {
            daicuo.page.click();
        },
        scroll: function() {
            daicuo.page.scroll();
        }
    }
};