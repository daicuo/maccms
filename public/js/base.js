// 定义框架根对象 selector target toggle
window.daicuo = {
    // 后台扩展对像
    admin: {},
    // 语言包扩展对像
    lang: {},
    // 配置扩展对像.根据自定义HTML属性动态配置变量
    config: function(){
        "use strict";
        var obj = {};
        $.each($('script[data-id="daicuo"]').get(0).attributes, function() {
            if (this.specified) {
                if (this.name.indexOf('data-') > -1) {
                    obj[this.name.replace(/^data-/, '')] = this.value;
                }
            }
        });
        return obj;
    }(),
    //按默认配置初始化组件合集
    init: function(options){
        "use strict";
        window.daicuo.ajax.init();
        window.daicuo.captcha.init();
        window.daicuo.dateTime.init();
        window.daicuo.form.init();
        window.daicuo.json.init();
        //window.daicuo.language.init();
        window.daicuo.lazyload.init();
        window.daicuo.media.init();
        window.daicuo.page.init();
        //window.daicuo.sortable.init();
        //window.daicuo.table.init();
        window.daicuo.tags.init();
        window.daicuo.upload.init();
    }
};

// AJAX请求封装
window.daicuo.ajax = {
    // 默认值
    defaults: {
        selectorReady: '[data-toggle="script"],[data-toggle="css"]',
        selectorClick: '[data-toggle="get"]',
    },
    // 初始调用或重载
    init: function(options){
        //合并初始参数
        options = $.extend({}, this.defaults, options);
        //监听.动态加载
        daicuo.ajax.onReady(options.selectorReady);
        //监听.点击事件
        daicuo.ajax.onClick(options.selectorClick);
    },
    // 方法.监听动态加载
    onReady: function(selector){
        //默认值
        selector = selector || this.defaults.selectorReady;
        //多元素
        $(selector).each(function() {
            var self = $(this);
            
            if( self.attr('data-js') ){
                var objJs = JSON.parse(self.attr('data-js'));
                daicuo.ajax.script(objJs, self.attr('data-on-success'), self.attr('data-on-fail'));
            }
            
            if( self.attr('data-css') ){
                var objCss = JSON.parse(self.attr('data-css'));
                daicuo.ajax.css(objCss);
            }
        });
    },
    // 方法.监听点击加载事件
    onClick: function(selector){
        //默认值
        selector = selector || this.defaults.selectorClick;
        //监听事件
        $(document).on("click", selector, function() {
        
            daicuo.bootstrap.dialog('<span class="fa fa-spinner fa-spin"></span> Loading...');
            
            daicuo.ajax.get($(this).attr('href'), $(this).attr('data-call-back'));
            
            return false;
        });
    },
    // 方法.回调任意函数
    'callBack': function($data, $status, $xhr, $callBack) {
        //form = typeof form === 'object' ? form : $(form);
        //函数类型回调
        if(typeof($callBack) == "function"){
            return $callBack($data, $status, $xhr);
        }else if(typeof($callBack) == "string"){
            $callBack = new Function('return (' + $callBack + ') ')();
            return $callBack($data, $status, $xhr);
        }
    },
    // 方法.浮动窗口
    get: function($url, $callSuccess, $callError, $callComplete) {
        $.ajax({
            type: 'get',
            cache: false,
            url: $url,
            //dataType: 'html',
            //timeout: 5000,
            error: function($xhr, $status, $data) {
                if($callError){
                    //回调错误处理
                    daicuo.tools.call($callError, [$data, $status, $xhr]);
                }else{
                    //默认加载失败location.reload();
                    daicuo.bootstrap.dialog(daicuo.lang.ajaxError);
                }
            },
            success: function($data, $status, $xhr) {
                if($callSuccess){
                    daicuo.tools.call($callSuccess, [$data, $status, $xhr]);
                }else{
                    if( $.isPlainObject($data) ){
                        daicuo.bootstrap.dialog($data.msg);
                        if($data.code == 1){
                            setTimeout('$(".dc-modal").modal("hide");location.reload()', 1000);
                        }
                    }else{
                        daicuo.bootstrap.dialogForm($data);
                    }
                }
            },
            //请求完成后回调函数success 和 error之后均调用)
            complete: function($xhr, $status) {
                if($callComplete){
                    daicuo.tools.call($callComplete, [$xhr, $status]);
                }
            }
        });
    },
    // 方法.表单提交
    post: function($url, $data, $callback) {
        $.ajax({
            url: $url,
            type: 'post',
            dataType: 'json',
            //timeout: 5000,
            data: $data,
            error: function() {
                daicuo.bootstrap.dialog(daicuo.lang.ajaxError);
            },
            success: function($data, $status, $xhr) {
                daicuo.tools.call($callback,[$data, $status, $xhr]);
            },
            complete: function(xhr) {}
        });
    },
    // 方法.动态加载样式表
    css: function(urls, callback) {
        //地址池
        urls = $.isArray(urls) ? urls : urls.split(/,|;|\s+/);
        for(var i = 0, l = urls.length; i < l; i++) {
            $("<link>").attr({
                rel: "stylesheet",
                type: "text/css",
                href: urls[i]
            }).appendTo("head");
        };
        //加载回调
        daicuo.tools.call(callback);
    },
    // 方法.动态加载脚本
    script: function(urls, success, fail){
        //请求池
        var cache = daicuo.ajax.script.cache = daicuo.ajax.script.cache || {};
        //console.log(cache);
        var getDeferred = function(url) {
            if( url in cache) return cache[url];
            return cache[url] = $.ajax({cache: true, dataType: "script", url: url});
            //return cache[url] = $.getScript( url );
        }
        //参数适配，可以支持string|Array类型的参数
        urls = $.isArray(urls) ? urls : urls.split(/,|;|\s+/);
        var ajaxs = [];
        $.each(urls, function(index, value){
            ajaxs.push( getDeferred( value ) );
        });
        //延迟对象
        $.when.apply(this, ajaxs).done(function(){
            daicuo.tools.call(success);
        }).fail(function(){
            daicuo.tools.call(fail);
        });
    }
};

// Bootsrtap组件
window.daicuo.bootstrap = {
    // 对话框BASE
    dialog: function($html, $title) {
        $title = $title || daicuo.lang.modalTitle;
        $('.dc-modal .modal-dialog').removeClass('modal-lg modal-xl').html('<div class="modal-content"><div class="modal-header pt-2 pb-1"><h6 class="my-0 py-0">' + $title + '</h6><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-primary">&times;</span></button></div><div class="modal-body"><h5 class="text-center py-3 text-dark">' + $html + '</h5></div></div>');
        $('.dc-modal').modal('show');
    },
    // 对话框表单
    dialogForm: function($html) {
        $('.dc-modal .modal-dialog').html($html);
        $('.dc-modal').modal('show');
        window.daicuo.upload.init();
        window.daicuo.json.init();
        window.daicuo.tags.init();
    },
    // 预览图片
    preview: function($html, $title){
        $('.dc-preview').remove();
        $('.dc-modal').after('<div class="modal fade dc-preview"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-body">' + $html + '</div></div></div></div>');
        $('.dc-preview').modal('show');
    }
};

// 浏览器对象属性
window.daicuo.browser = {
    // 获取网页当前网址
    'url': document.URL,
    // 获取网页域名
    'domain': document.domain,
    // 获取网页标题
    'title': document.title,
    // 获取浏览器地区 zh-tw|zh-hk|zh-cn
    'language': (navigator.browserLanguage || navigator.language).toLowerCase(),
    // 浏览器控制台调试
    'console': function(obj) {
        $.each(obj, function(key, val) {
            console.log(key); //alert(obj[key]);
        });
    },
    // 是否支持画图
    'canvas': function() {
        return !! document.createElement('canvas').getContext;
    }(),
    // 浏览器请求头
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
    }()
};

// 验证码
window.daicuo.captcha = {
    // 默认配置
    defaults: {
        selector: '[data-toggle="captcha"]',
        onClick: function(e) {
            e.attr('src', daicuo.config.root+'index.php?s=captcha&rand='+Math.random()).css({cursor:'pointer'});
        }
    },
    // 插件初始化
    init: function(options) {
        //动态配置选择器默认值处理
        options = $.extend({selector:this.defaults.selector}, options || {});
        //通过选择器获取HTML自定义属性配置
        var optionsHtml = daicuo.tools.extendAttr(this.defaults, $(options.selector));
        //合并所有动态传参配置
        options = $.extend({}, daicuo.captcha.defaults, optionsHtml, options);
        //增加手势
        $(options.selector).css({cursor:'pointer'});
        //事件监听
        $(document).on('click', options.selector, function(){
            daicuo.tools.call(options.onClick, [$(this)]);
        });
    },
    // 刷新验证码
    refresh: function(options){
        options = $.extend({selector:this.defaults.selector}, options);
        
        $(options.selector).attr('src', daicuo.config.root+'index.php?s=captcha&rand='+Math.random()).css({cursor:'pointer'});
    }
};

// 轮播滑动
window.daicuo.carousel = {
    // 默认配置
    defaults : {
        selector: '[data-toggle="carousel"]',
        initialIndex: 0,
        freeScroll: true,
        cellAlign: "left",
        resize: true,
        contain: true,
        lazyLoad: true,
        prevNextButtons: false,
        pageDots: false
    },
    // 插件初始化
    init: function(options){
        //合并默认配置
        options = $.extend({}, this.defaults, options || {});
        //动态加载插件库
        if( $(options.selector).length > 0 ){
            daicuo.carousel.ajaxLoad(function(){
                window.daicuo.carousel.plusInit(options);
            });
        }
    },
    // 插件方法：重置
    resize: function(options) { //resize
        //合并动态配置选择器
        options = $.extend({selector:this.defaults.selector}, options || {});
        //执行插件方法
        daicuo.carousel.ajaxLoad(function(){
            $(options.selector).flickity('resize');
        });
    },
    // 动态加载插件库并回调
    ajaxLoad: function(callback) {
        daicuo.ajax.script(['https://lib.baomitu.com/flickity/2.2.0/flickity.pkgd.min.js'],function(){
            //加载CSS
            daicuo.ajax.css('https://lib.baomitu.com/flickity/2.2.0/flickity.min.css');
            //执行回调
            daicuo.tools.callBack(callback);
        });
    },
    // 插件库初始调用方法
    plusInit: function(options){
        $(options.selector).each(function(i) {
            var $index = $(this).find('.active').index() * 1;
            if ($index > 3) {
                options.initialIndex = $index - 3;
            }
            $(this).flickity(options);
        });
    },
    // 1.5之前版本
    nav: function(selector) {
        selector = selector || '[data-toggle="carousel"]'; //if ($selector === undefined)
        daicuo.carousel.init({selector:selector});
    }
};

// 日期选择器
window.daicuo.dateTime = {
    // 默认配置
    defaults: {
        selector: '[data-toggle="datetime"]',//绑定日期元素
        format: 'YYYY/MM/DD',//日期格式
        autoclose: true,//当选择一个日期之后是否立即关闭此日期时间选择器。
        viewMode: 'days',
        buttons: {
            showToday: true,
            showClear: true,
            showClose: true
        },
        icons: {
            today: 'fa fa-caret-square-o-right',
            clear: 'fa fa-trash-o',
            close: 'fa fa-close'
        },
        tooltips:{
        
        },//这会将tooltips每个图标上方的内容更改为自定义字符串。
        locale: daicuo.config.lang //zh-cn
    },
    // 初始化
    init: function(options) {
        //合并动态配置.选择器
        options = $.extend({selector:this.defaults.selector}, options || {});
        //HTML自定义属性调用参数处理
        var optionsHtml = daicuo.tools.extendAttr(this.defaults, $(options.selector));
        //合并所有配置参数
        options = $.extend({}, this.defaults, optionsHtml, options);
        //执行插件方法.监听日期事件
        if( $(options.selector).length > 0 ){
            daicuo.dateTime.ajaxLoad(function(){
                window.daicuo.dateTime.plusInit(options);
            });
        }
    },
    // 动态加载插件库并回调
    ajaxLoad: function(callback) {
        daicuo.ajax.script(["https://lib.baomitu.com/moment.js/2.29.1/moment-with-locales.min.js"], function(){
            daicuo.ajax.script(['https://lib.baomitu.com/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js'],function(){
                //加载datetimepickerCSS
                daicuo.ajax.css('https://lib.baomitu.com/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css');
                //执行回调
                daicuo.tools.callBack(callback);
            });
        });
    },
    // 插件库的初始化调用方法
    plusInit: function(options){
        //定义语言包
        options.tooltips = daicuo.lang.dateTime.tooltips;
        //插件调用
        $(options.selector).datetimepicker(options);
    }
};

// 表单对象
window.daicuo.form = {
    // 默认配置
    defaults: {
        selectorAction:'[data-toggle="submit"]',
        selectorBack: '[data-toggle="back"]',
        selectorReload: '[data-toggle="reload"]',
        selectorCreate: '[data-toggle="create"]',
        selectorDelete: '[data-toggle="delete"]',
        selectorEdit: '[data-toggle="edit"]',
        selectorSubmit: '[data-toggle="form"]',
        selectorPreview:'[data-toggle="imagePreview"]'
    },
    // 初始化
    init: function(options){
        options = $.extend({}, this.defaults, options);
        daicuo.form.action(options.selectorAction);
        daicuo.form.back(options.selectorBack);
        daicuo.form.reload(options.selectorReload);
        daicuo.form.create(options.selectorCreate);
        daicuo.form.delete(options.selectorDelete);
        daicuo.form.edit(options.selectorEdit);
        daicuo.form.submit(options.selectorSubmit);
        daicuo.form.imagePreview(options.selectorPreview);
    },
    // 后退
    back: function(selector) {
        selector = selector || this.defaults.selectorBack;
        $(document).on("click", selector, function() {
            window.history.back(); //history.go(-1);
        });
    },
    // 刷新
    reload: function(selector) {
        selector = selector || this.defaults.selectorReload;
        $(document).on("click", selector, function() {
            location.reload();
        });
    },
    // 新增
    create: function(selector) {
        selector = selector || this.defaults.selectorCreate;
        $(document).on("click", selector, function() {
            if( $(this).data('modal-lg') ){
                $('.modal-dialog').addClass('modal-lg');
            }
            if( $(this).data('modal-xl') ){
                $('.modal-dialog').addClass('modal-xl');
            }
            daicuo.ajax.get($(this).attr('href'), $(this).attr('data-callback'));
            return false;
        });
    },
    // 删除
    delete: function(selector) {
        selector = selector || this.defaults.selectorDelete;
        $(document).on("click", selector, function() {
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
    edit: function(selector) {
        selector = selector || this.defaults.selectorEdit;
        $(document).on('click', selector, function() {
            if( $(this).data('modal-lg') ){
                $('.modal-dialog').addClass('modal-lg');
            }
            if( $(this).data('modal-xl') ){
                $('.modal-dialog').addClass('modal-xl');
            }
            daicuo.ajax.get($(this).attr('href'), $(this).attr('data-callback'));
            return false;
        });
    },
    // 动态改变Action
    action: function(selector){
        //<button data-toggle="submit" data-action="#">submit</button>
        selector = selector || this.defaults.selectorAction;
        $(document).on('click', selector, function() {
            if($action = $(this).data('action')){
                $(this).parents().find('form').attr('action',$action);
            }
        });
    },
    // 表单提交
    submit: function(selector) {
        selector = selector || this.defaults.selectorSubmit;
        $(document).on('submit', selector, function() {
            var self = $(this);
            if ($(this).attr('data-callback')) {
                daicuo.ajax.post($(this).attr('action'), $(this).serialize(), $(this).attr('data-callback'));
            } else {
                daicuo.ajax.post($(this).attr('action'), $(this).serialize(), function($data, $status, $xhr) {
                    if ($data.code == 1) {
                        daicuo.bootstrap.dialog($data.msg);
                        setTimeout('$(".dc-modal").modal("hide");location.reload()', 1000);
                    }else{
                        //根据验证失败的提示信息检查字段
                        $msg = $data.msg.split('%');
                        if($msg.length > 1){
                            //client
                            self.removeClass('was-validated');
                            self.find('.valid').removeClass('valid');
                            self.find('.invalid').removeClass('invalid');
                            //server
                            self.find('.is-invalid').removeClass('is-invalid').addClass('is-valid');
                            self.find('.invalid-feedback').remove();
                            self.find('#'+$msg[0]).removeClass('is-valid').addClass('is-invalid');
                            self.find('#'+$msg[0]).after('<div class="invalid-feedback">'+$msg[1]+'</div>');
                        }else{
                            daicuo.bootstrap.dialog($msg);
                        }
                    }
                });
            };
            return false;
        });
    },
    // 表单附件图片预览
    imagePreview: function(selector) {
        selector = selector || this.defaults.selectorPreview;
        $(document).on("click", selector, function() {
            if( $(this).attr('data-target') ){
                //附件保存真实地址
                var src = $($(this).attr('data-target')).val();
                if(!src){
                    return false;
                }
                //CDN与本地附件上传目录前缀
                var prefix = $(this).data('cdn')+$(this).data('root')+$(this).data('path')+'/';
                //拼装预览HTML代码
                var html = [];
                $.each(src.split(';'), function(index, value){
                    if(daicuo.tools.isAbsoluteURL(value)){
                        var previewSrc = value;
                    }else{
                        var previewSrc = prefix+value;
                    }
                    html.push('<img class="img-fluid w-100" src="'+previewSrc+'" alt="'+previewSrc+'"/>');
                });
                //模态框展示
                //daicuo.bootstrap.dialog(html.join(''),daicuo.lang.preview);
                daicuo.bootstrap.preview(html.join(''),daicuo.lang.preview);
            }
            return false;
        });
    },
    // 单个图片上传成功
    upSuccess: function(uploader, file, responseObject) {
        var container = uploader.settings.container;//上传按钮的父元素
        var selector = uploader.settings.browse_button;//上传按钮ID
        var inputId = $(selector).attr('data-input');//上传按钮自定义属性
        if( $(inputId).length > 0 ){
            var urls = [];
            //多文件则追加
            if ($(selector).data("multiple") && $(inputId).val() !== "") {
                urls.push($(inputId).val());
            }
            //单文件
            urls.push(file.responseTp.data.attachment);
            /*多文件
            $.each(file.responseTp.item, function (index, value) {
                urls.push(value.url);
            });*/
            //回填input
            $(inputId).val(urls.join(';'));
        }
    },
    // 单个图片上传失败 data.code data.msg
    upError: function(uploader, data) {
        daicuo.bootstrap.dialog(data.msg);
    },
    // 所有图片上传成功
    upComplete: function(uploader, files) {
        var selector = uploader.settings.browse_button;
        var inputId = $(selector).attr('data-input');
        //显示预览按钮
        if($(inputId).val()){
            $(selector).parent().find('.dc-image-preview').removeClass('d-none').addClass('d-inline-block');
        }
    }
};

// JSON处理
window.daicuo.json = {
    // 配置
    defaults: {
        selector: '[data-toggle="json"]',
    },
    // 初始
    init: function(options){
        //合并初始配置
        options = $.extend({}, this.defaults, options);
        //美化JSON
        daicuo.json.beauty(options.selector);
    },
    // 美化
    "beauty": function(selector) {
        selector = selector || this.defaults.selector;
        $(selector).each(function() {
            var jsonText = $(this).val();
            if(jsonText){
                $(this).val(JSON.stringify(JSON.parse(jsonText), null, 4));
            }
        });
    },
    // 丑化
    "ugly": function(selector) {
        selector = selector || this.defaults.selector;
        $(selector).each(function() {
            var jsonText = $(this).val();
            if(jsonText){
                $(this).val(JSON.stringify(JSON.parse(jsonText)));
            }
        });
    }
};

// 简繁转换组件
window.daicuo.language = {
    // 记录最后操作
    type: '',
    // 默认值
    defaults : {
        selector: '',//document.body
        method: 'auto'//s2t||t2s||auto||refresh
    },
    // 初始化调用
    init: function(options){
        //合并配置
        options = $.extend({}, this.defaults, options);
        //调用不同方法
        if(options.method  == 's2t'){
            daicuo.language.s2t(options.selector);
        }else if(options.method  == 't2s'){
            daicuo.language.t2s(options.selector);
        }else if(options.method  == 'auto'){
            daicuo.language.auto(options.selector);
        }else if(options.method  == 'refresh'){
            daicuo.language.refresh(options.selector);
        }
    },
    // 简转繁
    s2t: function(selector) {
        selector = selector || document.body;//undefined
        daicuo.language.ajaxLoad(function() {
            daicuo.language.type = 's2t';
            $(selector).s2t();
        });
    },
    // 繁转简
    t2s: function(selector) {
        selector = selector || document.body;
        daicuo.language.ajaxLoad(function() {
            daicuo.language.type = 't2s';
            $(selector).t2s();
        });
    },
    // 自动转为浏览器相关
    auto: function(selector) {
        if (daicuo.browser.language == 'zh-hk' || daicuo.browser.language == 'zh-tw') {
            daicuo.language.s2t(selector);
        }else if(daicuo.browser.language == 'zh-cn'){
            daicuo.language.t2s(selector);
        }
    },
    // Dom更新后需重新激活
    refresh: function(selector) {
        if (daicuo.language.type == 's2t') {
            daicuo.language.s2t(selector);
        } else {
            daicuo.language.t2s(selector);
        }
    },
    // 动态加载插件库并回调
    ajaxLoad: function(callback) {
        daicuo.ajax.script(["https://cdn.daicuo.cc/jquery.s2t/0.1.0/s2t.min.js"], function(){
            daicuo.tools.callBack(callback);
        });
    },
    // 1.5前旧方法刷新
    dom: function(selector) {
        daicuo.language.refresh(selector);
    }
};

// 图片延迟加载组件
window.daicuo.lazyload = {
    // 默认配置
    defaults: {
        selector: 'img[data-original]',
        placeholder: daicuo.root + "public/images/grey.gif",
        effect: "fadeIn",
        failurelimit: 10
        //threshold : 400
        //skip_invisible : false
        //container: $(".carousel-inner"),
    },
    // 初始化
    init: function(options){
        //合并配置
        options = $.extend({}, this.defaults, options);
        //动态加载插件并执行初始化方法
        if( $(options.selector).length>0 ){
            daicuo.lazyload.ajaxLoad(function() {
                window.daicuo.lazyload.plusInit(options);
            });
        }
    },
    // Dom更新后需重新激活
    refresh: function(selector) {
        //目标元素
        selector = selector || this.defaults.selector;
        //回调插件
        daicuo.lazyload.ajaxLoad(function(){
            $(selector).lazyload();//'#selector img[data-original]'
        });
    },
    // 动态加载插件包
    ajaxLoad: function(callback) {
        daicuo.ajax.script(["https://lib.baomitu.com/jquery.lazyload/1.9.1/jquery.lazyload.min.js"], function(){
            daicuo.tools.callBack(callback);
        });
    },
    // 插件库的初始化调用方法
    plusInit: function(options){
        $(options.selector).lazyload(options);
    },
    // 1.5 before 旧方法
    image: function(selector) {
        selector = selector || this.defaults.selector;
        daicuo.lazyload.init({selector:selector});
    },
    // 1.5 before 旧方法
    dom: function(selector) {
        selector = selector || this.defaults.selector;
        daicuo.lazyload.refresh(selector);
    }
};

// 多媒体对象
window.daicuo.media = {
    // 默认配置
    defaults: {
        in: true,
        ai: "",
        type: "",
        url: "",
        next: "",
        jump: "",
        buffer: "",
        pause: "",
        frontUrl: "",
        frontTime: "",
        endUrl: "",
        endTime: "",
        poster: "",
        advUnit: "",
        index: 0,
        element: ".dc-player"
    },
    // 入口函数
    init: function(options){
        //初始化播放器选择器
        options = $.extend({element:daicuo.media.defaults.element}, options);
        //加载播放器支持多个
        $(options.element).each(function(index, value) {
            //获取HTML属性
            var optionsAttr = {
                index: index,
                in: $(this).attr('data-in'),
                ai: $(this).attr('data-ai'),
                type: $(this).attr('data-type'),
                url: $(this).attr('data-url'),
                next: $(this).attr('data-next'),
                jump: $(this).attr('data-jump'),
                buffer: $(this).attr('data-buffer'),
                pause: $(this).attr('data-pause'),
                frontUrl: $(this).attr('data-frontUrl'),
                frontTime: $(this).attr('data-frontTime'),
                endUrl: $(this).attr('data-endUrl'),
                endTime: $(this).attr('data-endTime'),
                poster: $(this).attr('data-poster'),
                advUnit: $(this).attr('data-advUnit')
            };
            //合并Html属性与动态配置
            options = $.extend({}, daicuo.media.defaults, optionsAttr, options);
            //云播放器统计
            $.getScript("https://hao.daicuo.cc/player/vv/?json="+encodeURIComponent(JSON.stringify(options)));
            //优先自定义解析
            if(options.ai){
                return daicuo.media.parse(options);
            }
            //其次站外播放
            if (options.in == 'false' || options.in == false) {
                return daicuo.media.outPlay(options);
            }
            //站内播放.智能加载云播放器JS
            return daicuo.media.inPlay(options);
        });
    },
    // 解析方法
    parse: function(DcPlayer){
        var $src = DcPlayer.ai + DcPlayer.url + '&json=';
        delete DcPlayer['ai'];
        delete DcPlayer['in'];
        delete DcPlayer['url'];
        $(DcPlayer.element).html('<iframe class="embed-responsive-item" src="' + $src + encodeURIComponent(JSON.stringify(DcPlayer)) + '" width="100%" height="100%" frameborder="0" scrolling="no" allowfullscreen="true"></iframe>');
    },
    // 站外调用
    outPlay: function(DcPlayer){
        $(DcPlayer.element).html('<iframe class="embed-responsive-item" src="https://hao.daicuo.cc/player/outplay/?url=' + encodeURIComponent(DcPlayer.url) + '" width="100%" height="100%" frameborder="0" scrolling="no" allowfullscreen="true"></iframe>');
    },
    // 站内播放
    inPlay: function(DcPlayer){
        daicuo.ajax.script('https://hao.daicuo.cc/player/inplay/?type=' + DcPlayer.type + '&url=' + DcPlayer.url, function(){
            daicuo.media.yun.init(DcPlayer);
        });
    }
};

// AJAX翻页
window.daicuo.page = {
    // 分页锁
    locked: false,
    // 动态配置
    options: {},
    // 默认配置
    defaults: {
        selectorClick: '[data-toggle="page"],[data-pageClick="true"]',
        selectorScroll: '[data-toggle="page"],[data-pageScroll="true"]'
    },
    // 初始化调用
    init: function(options){
        //合并初始参数
        daicuo.page.options = $.extend({}, this.defaults, options);
        //监听点击事件
        daicuo.page.click();
        //监听滚动事件
        daicuo.page.scroll();
    },
    // 点击翻页
    click: function() {
        //选择器
        var selector = daicuo.page.options.selectorClick || daicuo.page.defaults.selectorClick;
        //点击事件
        $(document).on("click", selector, function() {
            if (daicuo.page.locked == false) {
                daicuo.page.ajax($(this), $(this).attr('data-url'), $(this).attr('data-page') * 1 + 1, $(this).attr('data-target'));
            }
        });
    },
    // 滚动翻页
    scroll: function() {
        //选择器
        var selector = daicuo.page.options.selectorScroll || daicuo.page.defaults.selectorScroll;
        //只需一个
        var $obj = $(selector).eq(0);
        if ($obj.length) {
            $(window).bind("scroll", function() {
                if (daicuo.page.locked == false && ($(this).scrollTop() + $(window).height()) >= $(document).height()) {
                    daicuo.page.ajax($obj, $obj.attr('data-url'), $obj.attr('data-page') * 1 + 1, $obj.attr('data-target'));
                }
            });
        }
    },
    // 请求数据
    ajax: function($event, $url, $page, $target) {
        var $html = $event.html();
        $(document).ajaxStart(function() {
            daicuo.page.locked = true;
            $event.html(daicuo.lang.loading);
        });
        $.get($url + $page,function($data) {
            daicuo.page.locked = false;
            $event.html($html);
            if ($data) {
                //追加数据
                $($target).append($data);
                //更新页码
                $event.attr("data-page", $page);
                //window.history.pushState(null, null, $url+$page);
                //图片懒加载
                if( $event.data('target-lazyload') ){
                    daicuo.lazyload.init({selector:$event.data('target-lazyload')});
                }
                //是否简繁转化
                if( $event.data('target-language') ){
                    daicuo.language.refresh($event.data('target-language'));
                }
                //自定义回调事件
                daicuo.tools.callBack($event.data('call-back'));
            } else {
                $event.remove();
                $event.unbind("click");
            }
        });
    }
};

// 拖拽排序
window.daicuo.sortable = {
    // 动态对象
    obj: '',
    // 默认配置
    defaults: {
        handle: '.dc-handle',
        //draggable: ".item",
        dataIdAttr: 'data-id',
        ghostClass: 'bg-secondary',
        onEnd: function(event) {
            $(event.item).css({
                'transform': 'none'
            });
        }
    },
    // 初始化
    init: function(selector, options) {
        //合并初始参数
        options = $.extend({}, this.defaults, options);
        //调用拖拽
        daicuo.sortable.ajaxLoad(function(){
            window.daicuo.sortable.plusInit(selector, options);
        });
    },
    // 动态加载插件包
    ajaxLoad: function(callback) {
        daicuo.ajax.script(["https://lib.baomitu.com/Sortable/1.10.0/Sortable.min.js"], function(){
            daicuo.tools.callBack(callback);
        });
    },
    // 插件初始调用方法
    plusInit: function(selector, options){
        daicuo.sortable.obj = new Sortable(selector, options);
        //daicuo.sortable.obj = Sortable.create(selector, options);
    }
};

// 表格数据
window.daicuo.table = {
    // 初始配置
    defaults: {
        selector  : 'table[data-toggle="bootstrap-table"]',
        onSuccess : '',
        onFail    : ''
    },
    // 初始化
    init: function(options) {
        //合并初始配置
        var options = $.extend({}, daicuo.table.defaults, options);
        //动态加载格插件包
        if( $(options.selector).length > 0 ){
            daicuo.table.ajaxLoad(function(){
                //调用第三方表格插件
                window.daicuo.table.plusInit(options);
                //监听刷新事件
                $('[data-toggle="refresh"]').on("click", document.body, function() {
                    daicuo.table.refresh(options.selector);
                });
            });
        }
    },
    // 刷新
    refresh: function(selector, options){
        if(options){
            $(selector).bootstrapTable('refreshOptions', options);
        }else{
            $(selector).bootstrapTable('refresh');
        }
    },
    // data-query-params 请求远程数据时附加参数
    query: function(params){
        return { 
            pageNumber: params.pageNumber, 
            pageSize: params.pageSize,
            sortName: params.sortName,
            sortOrder: params.sortOrder,
            searchText: params.searchText
        };
    },
    // data-formatter 拖拽排序
    sort: function(value, row, index, field) {
        return '<i class="fa fa-arrows-alt fa-lg text-purple dc-handle"></i>';
    },
    // data-formatter ICO图标
    ico: function(value, row, index, field){
        return '<i class="fa-lg text-muted '+value+'"></i>';
    },
    // 动态加载bootstrap-table
    ajaxLoad: function(callBack) {
        daicuo.ajax.script(["https://cdn.daicuo.cc/bootstrap-table/1.16.0/bootstrap-table.min.js"], function(){
            daicuo.ajax.css("https://cdn.daicuo.cc/bootstrap-table/1.16.0/bootstrap-table.min.css");
            daicuo.tools.callBack(callBack);
        });
    },
    // 插件库初始化调用方法
    plusInit: function(options){
        //创建表单动态数据
        $(options.selector).bootstrapTable(daicuo.lang.bootstrapTable);
        //表格创建成功
        $(options.selector).on('load-success.bs.table', function ($data, $status, $xhr) {
            //拖拽插件
            var urlSort = $(options.selector).data('url-sort');
            //是否加载拖拽排序插件
            if (urlSort) {
                daicuo.sortable.init($(options.selector+' tbody').get(0), {
                    dataIdAttr: 'data-uniqueid',
                    onEnd: function(event) {
                        $.get(urlSort+daicuo.sortable.obj.toArray().join(','));
                    }
                });
            }
            //是否回调
            if (options.onSuccess) {
                daicuo.tools.call(options.onSuccess, [options.selector]);
            }
        });
        //表格创建失败
        $(options.selector).on('load-error.bs.table',function($status, $xhr) {
            //弹窗提示
            daicuo.bootstrap.dialog(daicuo.lang.ajaxError);
            //是否回调
            if (options.onFail) {
                daicuo.tools.call(options.onFail, [$(options.selector)]);
            }
        });
    }
};

//标签输入组件
window.daicuo.tags = {
    // 初始配置
    defaults: {
        selectorInput: '[data-toggle="tags"]',
        selectorClick: '.tag-list',
        maxTags: 8,
        maxChars: 20,
        trimValue: true,
        //itemValue: 'value',
        //itemText: 'text',
        tagClass: 'badge badge-secondary'
    },
    // 组件初始化
    init : function(options){
        //合并初始配置
        options = $.extend({}, this.defaults, options);
        //动态按需加载回调
        if( $(options.selectorInput).length > 0 ){
            window.daicuo.tags.ajaxLoad(function(){
                window.daicuo.tags.plusInit(options);
                window.daicuo.tags.clickAdd(options.selectorClick, options.selectorInput);
            });
        }
    },
    // 点击添加标签方法
    clickAdd: function(selectorClick, selectorInput){
        $(selectorClick).on('click', document.body, function(){
            $(selectorInput).tagsinput('add', $(this).text());
        });
    },
    // 动态加载插件包
    ajaxLoad: function(callBack) {
        daicuo.ajax.script(["https://lib.baomitu.com/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"], function(){
            daicuo.ajax.css("https://lib.baomitu.com/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css");
            daicuo.tools.callBack(callBack);
        });
    },
    // 插件初始化调用方法
    plusInit: function(options){
        $(options.selectorInput).tagsinput(options);
    }
};

// 常用工具
window.daicuo.tools = {
    // 动态回调有参数
    call: function(callBack, paramsArray) {
        if( typeof callBack == 'string'){
            callBack = new Function('return (' + callBack + ') ')();
            return callBack.apply(this, paramsArray);
            //return window[callBack].apply(this, paramsArray);
        }else if( typeof callBack == 'function'){
            return callBack.apply(this, paramsArray);
        }else{
            return ;
        }
    },
    // 单个回调无参数
    callBack: function($callBack) {
        //form = typeof form === 'object' ? form : $(form);
        if(typeof $callBack  == "function"){
            return $callBack();
        }else if(typeof $callBack == "string"){
            $callBack = new Function('return (' + $callBack + ') ')();//字符串函数名不能有()
            return $callBack();
            //return new Function('args','return (' + $callBack + ') ')();
        }
    },
    // ES5函数默认值(支持普通与OBJ)
    default: function(value, valueDefault){
        //var element = element === undefined ? this.defaults.element : optionInit.element;
        //return value = value || valueDefault; 
        return value = arguments[0] === undefined ? valueDefault : arguments[0];
    },
    // 获取HTML元素自定义数据集
    htmlAttrs: function(element){
        var obj = {};
        $.each($(element).get(0).attributes, function() {
            if (this.specified) {
                if (this.name.indexOf('data-') > -1) {
                    obj[this.name.replace(/^data-/, '')] = this.value; //this.nodeName+this.nodeValue
                }
            }
        });
        return obj;//alert(JSON.stringify(obj));
    },
    // HTML元素自定义的数据与默认配置的OBJ合并（OBJ定义的字段才会合并）
    extendAttr : function (obj, element) {
        if( typeof element == 'string'){
            element = $(element);
        }
        var attrs = {};
        $.each( obj, function( key, value ) {
            var keyAttr = daicuo.tools.hump2Str(key);
            if( element.data(keyAttr) ){
                attrs[key] = element.data(keyAttr);
            }
        });
        return attrs;
        //return Object.keys(obj);
    },
    // 字符串转驼峰
    str2Hump: function(str){
        var re = /-(\w)/g;
        return str.replace(re, function ($0,$1){
            return $1.toUpperCase();
        });
    },
    // 驼峰转字符串
    hump2Str: function(name){
        return name.replace(/([A-Z])/g,"-$1").toLowerCase();
    },
    // 服务端AJAX数据转化为TP专用
    response: function (response) {
        try {
            var ret = typeof response === 'object' ? response : JSON.parse(response);
            if (!ret.hasOwnProperty('code')) {
                $.extend(ret, {code: -2, msg: response, data: null});
            }
        } catch (e) {
            var ret = {code: -1, msg: e.message, data: null};
        }
        return ret;
    },
    // 判断是否为绝对地址
    isAbsoluteURL : function($str){
        return /^[a-z][a-z0-9+.-]*:/.test($str);
    }
};

//上传对像
window.daicuo.upload = {
    // 默认配置
    defaults: {
        //上传绑定元素
        element: '.dc-upload',
        //动态生成的父容器
        container: $('.dc-upload').parent().get(0),
        //拖拽上传区域
        elementDrop: undefined,
        //文本框目标ID
        input: '',
        //多个上传标识
        index: 0,
        //表单名称
        name: 'file[]',
        //上传时的其它参数
        params:{},
        //是否选完后自动上传
        auto: true,
        //是否多选
        multiple: false,
        //以multipart/form-data的形式来上传文件
        multipart: true,
        //上传地址
        url: daicuo.config.upload,
        //最大上传
        maxSize: '10mb',
        //允许上传类型
        mimeTypes: 'image/*,application/zip',
        //上传方式
        runtimes: 'html5,flash,silverlight,html4',
        //初始化后
        onInit: function () {
        
        },
        //Init事件发生后触发
        onPostInit: function() {
        
        },
        //当上传队列的状态发生改变时触发
        onStateChanged: function(up, file){
        
        },
        //当某一个文件开始上传后
        onUploadFile: function(up, file){

        },
        //当上传队列发生变化后触发
        onQueueChanged: function (up) {

        },
        //当文件从上传队列移除后触发
        onFilesRemoved: function (up, file) {

        },
        //文件添加成功后
        onChoose: function(up, files) {
            //console.log(up.settings);
        },
        //上传之前的回调
        onBefore: function (up, file) {

        },
        //显示上传进度 
        onProgress: function(up, file){

        },  
        //上传成功的回调
        onSuccess: function (up, file, xhr){

        },
        //分片上传每一个成功后
        onChunk: function(up, file, xhr) {
        
        },
        //上传全部结束后
        onComplete: function (up, files) {

        },
        //上传错误后
        onError: function(up, err) {
            //err.code err.msg
        }
    },
    // 初始化
    init: function(options){
        //初始化监听元素
        options = $.extend({}, {element:daicuo.upload.defaults.element}, options);
        //合并HTML属性
        $(options.element).each(function(index, value) {
            //读取HTML属性配置
            var optionsAttr = daicuo.tools.extendAttr(daicuo.upload.defaults, $(this));
            //console.log(optionsAttr);
            //合并HTML属性配置与传参配置
            var optionsUpload = $.extend({}, optionsAttr, options);
            //上传实例编号
            optionsUpload.index = index;
            //加载上传插件并执行
            daicuo.upload.ajaxLoad(function(){
                daicuo.upload.start(optionsUpload);
            });
        });
    },
    // 上传实例
    start: function(options){
        var uploadDC = $.extend({}, daicuo.upload.defaults, options);//合并初始配置
        var element = $(uploadDC.element).get(uploadDC.index);//容器ID
        //console.log(uploadDC.index);
        var defaults = {
            browse_button : element,//触发文件选择对话框的元素id
            container: $(element).parent().get(0), //动态生成上传代码的容器ID
            url : uploadDC.url,//服务器端的上传页面地址
            file_data_name: uploadDC.name,//文件域的名称，默认为file
            multi_selection: uploadDC.multiple,//是否多选
            multipart: uploadDC.multipart,//为true时将以multipart/form-data的形式来上传文件，为false时则以二进制的格式来上传文件
            multipart_params: uploadDC.params,//上传时的附加参数
            max_retries: 1,//当发生plupload.HTTP_ERROR错误时的重试次数，为0时表示不重试
            chunk_size: 0,//分片上传,单位字节,也可以200kb,0为不启用
            drop_element: uploadDC.elementDrop,//拖拽上传
            runtimes: uploadDC.runtimes,//指定上传方式
            flash_swf_url : daicuo.config.root+'public/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
            silverlight_xap_url : daicuo.config.root+'public/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
            //headers:{},
            //resize:{},
            filters : {
                max_file_size : uploadDC.maxSize,
                mime_types: uploadDC.mimeTypes
            },
            init: {
                //初始化后
                Init: function (up) {
                    daicuo.tools.call(uploadDC.onInit, [up]);
                },
                //Init事件发生后触发
                PostInit: function (up) {
                    daicuo.tools.call(uploadDC.onPostInit, [up]);
                },
                //当上传队列的状态发生改变时触发
                StateChanged: function (up) {
                    daicuo.tools.call(uploadDC.onStateChanged, [up]);
                },
                //当上传队列中某一个文件开始上传后触发
                UploadFile: function (up, file) {
                    daicuo.tools.call(uploadDC.onUploadFile, [up, file]);
                },
                //当上传队列发生变化后触发
                QueueChanged: function (up) {
                    daicuo.tools.call(uploadDC.onQueueChanged, [up]);
                },
                //当文件从上传队列移除后触发
                FilesRemoved: function (up, file) {
                    daicuo.tools.call(uploadDC.onFilesRemoved, [up, file]);
                },
                //文件添加成功后
                FilesAdded: function (up, files) {
                    //备份原HTML
                    var button = up.settings.browse_button;
                    if( !$(button).data('back-html') && $(button).html() ){
                        $(button).data('back-html', $(button).html());
                    }
                    //是否自动上传
                    if(uploadDC.auto == true){
                        up.start();
                    }
                    //自定义回调
                    daicuo.tools.call(uploadDC.onChoose, [up, files]);
                },
                //上传之前的回调
                BeforeUpload: function (up, file) {
                    daicuo.tools.call(uploadDC.onBefore, [up, file]);
                },
                //显示上传进度 
                UploadProgress: function (up, file) {
                    $(up.settings.browse_button).prop("disabled", true).html("<i class='fa fa-upload'></i> " +  file.percent + "%");
                    daicuo.tools.call(uploadDC.onProgress, [up, file]);
                },
                //当队列中的某一个文件上传完成后触
                FileUploaded: function(up, file, xhr) {
                    //格式化为TP的AJAX返回样式
                    file.responseTp = daicuo.tools.response(xhr.response);
                    //上传成功
                    if(file.responseTp.code == 1){
                        daicuo.tools.call(uploadDC.onSuccess, [up, file, xhr]);
                    }else{
                        daicuo.bootstrap.dialog(file.responseTp.msg);
                        daicuo.tools.call(uploadDC.onError, [up, {code:file.responseTp.code, msg:file.responseTp.msg}] );
                    }
                },
                //当使用文件小片上传功能时，每一个小片上传完成后触发
                ChunkUploaded: function(up, file, xhr) {
                    daicuo.tools.call(uploadDC.onChunk, [up, files, xhr]);
                },
                //当上传队列中所有文件都上传完成后触发
                UploadComplete: function (up, files) {
                    //console.log(files);
                    //还原按钮文字
                    if( $(element).data('back-html') ){
                        $(element).prop("disabled", false).html($(element).data('back-html'));
                    }else{
                        $(element).prop("disabled", false).html('');
                    }
                    daicuo.tools.call(uploadDC.onComplete, [up, files]);
                },
                //上传错误后
                Error: function(up, error) {
                    //error.code error.message
                    //daicuo.bootstrap.dialog(error.message);
                    daicuo.tools.call(uploadDC.onError, [up, {code:error.code, msg:error.message}] );
                },
                //销毁后
                Destroy: function(up) {
                
                }
            }
        };

        var uploader = new plupload.Uploader(defaults);
        
        uploader.init();
    },
    ajaxLoad: function(callback){
        daicuo.ajax.script(['https://lib.baomitu.com/plupload/3.1.2/plupload.full.min.js'], function(){
            var lang = daicuo.config.lang.replace('zh-cn','zh_CN').replace('zh-tw','zh_TW');
            daicuo.ajax.script(['https://lib.baomitu.com/plupload/3.1.2/i18n/'+lang+'.js']);
            //daicuo.upload.start(options);
            daicuo.tools.callBack(callback);
        });
    }
};

// 加载语言包
$(document).ready(function(){
    //扩展jquery
    $.getUrlParam = function(name){
        var reg = new RegExp("(^|&)"+name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r!=null) {
            return unescape(r[2]);
        }
        return null;
    };
    //加载语言包
    daicuo.ajax.script(daicuo.config.root+'public/js/'+daicuo.config.lang+'.js');
});