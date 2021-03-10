window.daicuo = {
    // 后台扩展对像
    admin: {},
    // 插件扩展对像
    fn: {},
    // 语言包扩展对像
    lang: {},
    // 配置扩展对像.根据自定义HTML属性动态配置变量
    config: function() {
        "use strict";
        var obj = {};
        $.each($('script[data-id="daicuo"]').get(0).attributes, function() {
            if (this.specified) {
                if (this.name.indexOf('data-') > -1) {
                    obj[this.name.replace(/^data-/, '')] = this.value; //this.nodeName+this.nodeValue
                }
            }
        });
        return obj; //alert(JSON.stringify(obj));
    } ()
};

// Bootsrtap组件
window.daicuo.bootstrap = {
    // 对话框BASE
    dialog: function($html, $title) {
        $title = $title|| daicuo.lang.modalTitle;
        $('.dc-modal .modal-dialog').removeClass('modal-lg').html('<div class="modal-content"><div class="modal-header pt-2 pb-1"><h6 class="my-0 py-0">' + $title + '</h6><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-primary">&times;</span></button></div><div class="modal-body"><h5 class="text-center py-3 text-dark">' + $html + '</h5></div></div>');
        $('.dc-modal').modal('show');
        //$('.dc-modal .modal-dialog').toggleClass('modal-dialog-centered',true);
    },
    // 对话框表单
    dialogForm: function($html) {
        $('.dc-modal .modal-dialog').html($html);
        $('.dc-modal').modal('show');
    }
};

// 表格数据
window.daicuo.table = {
    // 初始配置
    "option": {
        "toggle": 'table[data-toggle="bootstrap-table"]',
        "urlSort": '',
        "urlEdit": '',
        "urlDelete": '',
        "urlPreview": '',
    },
    // 初始化
    "init": function($option, $callback) {
        //daicuo.config.file+'/'+daicuo.config.controll+'/edit?id=';
        var options = $.extend({}, daicuo.table.option, $option);
        if ($(options.toggle).get(0)) {
            daicuo.table.ajax(options, $callback);
        }
    },
    // data-formatter 拖拽排序
    "sort": function(value, row, index, field) {
        return '<i class="fa fa-arrows-alt fa-lg text-purple dc-handle"></i>';
    },
    //执行表格
    start: function(options, callback){
        var element = options.toggle;
        //创建表单动态数据
        $(element).bootstrapTable();
        //数据创建成功
        $(element).on('load-success.bs.table', function ($data, $status, $xhr) {
            //是否加载拖拽排序插件
            if (options.urlSort) {
                daicuo.sortable.init($('tbody').get(0), {
                    dataIdAttr: 'data-uniqueid',
                    onEnd: function(evt) {
                        $.get(options.urlSort + daicuo.sortable.obj.toArray().join(','));
                    }
                });
            }
            //是否回调
            daicuo.tools.call(callback);
        });
        //数据创建失败
        $(element).on('load-error.bs.table',function($status, $xhr) {
            daicuo.bootstrap.dialog(daicuo.lang.ajaxError);
        });
    },
    // 动态加载bootstrap-table
    ajax: function(options, callback) {
        daicuo.ajax.css("https://lib.baomitu.com/bootstrap-table/1.16.0/bootstrap-table.min.css");
        daicuo.ajax.script(["https://lib.baomitu.com/bootstrap-table/1.16.0/bootstrap-table.min.js"], function(){
            daicuo.ajax.script(['https://lib.baomitu.com/bootstrap-table/1.16.0/bootstrap-table-locale-all.min.js'],function(){
                daicuo.table.start(options, callback);
            });
        });
        return true;
    }
};

// 表单对象
window.daicuo.form = {
    // 初始化
    init: function(){
        daicuo.form.back();
        daicuo.form.reload();
        daicuo.form.create();
        daicuo.form.delete();
        daicuo.form.edit();
        daicuo.form.submit();
        daicuo.form.imagePreview();
    },
    // 后退
    back: function() {
        $(document).on("click", '[data-toggle="back"]', function() {
            window.history.back(); //history.go(-1);
        });
    },
    // 刷新
    reload: function() {
        $(document).on("click", '[data-toggle="reload"]', function() {
            location.reload();
        });
    },
    // 新增
    create: function() {
        $(document).on("click", '[data-toggle="create"]', function() {
            if( $(this).attr('data-modal-lg')=='true'){
                $('.modal-dialog').addClass('modal-dialog-scrollable modal-lg');
            }
            daicuo.ajax.get($(this).attr('href'), $(this).attr('data-callback'));
            return false;
        });
    },
    // 删除
    delete: function() {
        $(document).on("click", '[data-toggle="delete"]', function() {
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
    edit: function() {
        $(document).on('click', '[data-toggle="edit"]', function() {
            if( $(this).attr('data-modal-lg')=='true' ){
                $('.modal-dialog').addClass('modal-dialog-scrollable modal-lg');
            }
            daicuo.ajax.get($(this).attr('href'), $(this).attr('data-callback'));
            return false;
        });
    },
    // 提交
    submit: function() {
        //按钮动态改变表单提交地址
        //<button data-toggle="submit" data-action="#">submit</button>
        $(document).on('click', 'button[data-toggle="submit"]', function() {
            if($action = $(this).data('action')){
                $(this).parents().find('form').attr('action',$action);
            }
        });
        //表单提交事件
        $(document).on('submit', '[data-toggle="form"]', function() {
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
    // 单个图片上传成功
    upSuccess: function(uploader, file, responseObject) {
        var container = uploader.settings.container;//上传按钮的父元素
        var element = uploader.settings.browse_button;//上传按钮ID
        var inputId = $(element).attr('data-input');//上传按钮自定义属性
        if( $(inputId).length > 0 ){
            var urls = [];
            //多文件则追加
            if ($(element).data("multiple") && $(inputId).val() !== "") {
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
        var element = uploader.settings.browse_button;
        var inputId = $(element).attr('data-input');
        //显示预览按钮
        if($(inputId).val()){
            $(element).parent().find('.dc-image-preview').removeClass('d-none').addClass('d-inline-block');
        }
    },
    // 表单附件图片预览
    imagePreview: function() {
        $(document).on("click", '[data-toggle="imagePreview"]', function() {
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
                    html.push('<div class="border rounded my-2 p-2"><img class="img-fluid" src="'+prefix+value+'" /></div>');
                });
                //模态框展示
                daicuo.bootstrap.dialog(html.join(''),daicuo.lang.preview);
            }
            return false;
        });
    }
};

//上传对像
window.daicuo.upload = {
    //初始化
    init: function(options){
        //初始化监听元素
        options = $.extend({}, {element:daicuo.upload.defaults.element}, options);
        //合并HTML属性
        $(options.element).each(function(index, value) {
            //读取HTML属性配置
            var optionsAttr = daicuo.tools.extendAttr(daicuo.upload.defaults, $(this));
            console.log(optionsAttr);
            //合并HTML属性配置与传参配置
            var optionsUpload = $.extend({}, optionsAttr, options);
            //上传实例编号
            optionsUpload.index = index;
            //加载上传实例
            daicuo.upload.ajax(optionsUpload);
        });
    },
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
    ajax: function(options){
        daicuo.ajax.script(['https://lib.baomitu.com/plupload/3.1.2/plupload.full.min.js'], function(){
            var lang = daicuo.config.lang.replace('zh-cn','zh_CN').replace('zh-tw','zh_TW');
            daicuo.ajax.script(['https://lib.baomitu.com/plupload/3.1.2/i18n/'+lang+'.js']);
            daicuo.upload.start(options);
        });
    }
};

// 验证码
window.daicuo.captcha = {
    //初始化
    init: function(options) {
        //初始化监听元素
        options = $.extend({}, {element:daicuo.captcha.defaults.element}, options);
        //获取HTML属性
        var optionsAttr = daicuo.tools.extendAttr(daicuo.captcha.defaults, $(options.element));
        //监听点击事件
        daicuo.captcha.start( $.extend({}, optionsAttr, options) );
    },
    //监听点击图片的方法
    start: function(options){
        //合并初始参数
        options = $.extend({}, daicuo.captcha.defaults, options);
        //增加手势
        $(options.element).css({cursor:'pointer'});
        //事件监听
        $(document).on('click', options.element, function(){
            daicuo.tools.call(options.onClick, [$(this)]);
        });
    },
    //默认配置
    defaults: {
        element: '.dc-captcha',
        onClick: function(e) {
            e.attr('src', daicuo.config.root+'captcha?'+Math.random()).css({cursor:'pointer'});
        }
    }
};

// 多媒体对象
window.daicuo.media = {
    init: function(options){
        //初始化监听元素
        options = $.extend({}, {element:daicuo.media.defaults.element}, options);
        //合并HTML属性
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
            //加载播放器
            daicuo.media.start( $.extend({}, optionsAttr, options) );
        });
    },
    start: function(options){
        //合并初始参数
        var DcPlayer = $.extend({}, daicuo.media.defaults, options);//window.***声明的全局对象外部JS才可以调用
        //自定义解析
        if(DcPlayer.ai){
            return daicuo.media.parse(DcPlayer);
        }
        //站外播放
        if (DcPlayer.in == 'false' || DcPlayer.in == false) {
            return daicuo.media.outPlay(DcPlayer);
        }
        //站内播放.智能加载云播放器JS
        return daicuo.media.inPlay(DcPlayer);
    },
    parse: function(DcPlayer){
        var $src = DcPlayer.ai + DcPlayer.url + '&json=';
        delete DcPlayer['ai'];
        delete DcPlayer['in'];
        delete DcPlayer['url'];
        $(DcPlayer.element).html('<iframe class="embed-responsive-item" src="' + $src + encodeURIComponent(JSON.stringify(DcPlayer)) + '" width="100%" height="100%" frameborder="0" scrolling="no" allowfullscreen="true"></iframe>');
    },
    outPlay: function(DcPlayer){
        $(DcPlayer.element).html('<iframe class="embed-responsive-item" src="https://hao.daicuo.cc/player/outplay/?url=' + encodeURIComponent(DcPlayer.url) + '" width="100%" height="100%" frameborder="0" scrolling="no" allowfullscreen="true"></iframe>');
    },
    inPlay: function(DcPlayer){
        daicuo.ajax.script('https://hao.daicuo.cc/1.4/video/?type=' + DcPlayer.type + '&url=' + DcPlayer.url, function(){
            daicuo.media.yun.init(DcPlayer);
        });
    },
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
    }
};

// JSON处理
window.daicuo.json = {
    // 初始
    init: function(options){
        //初始化监听元素
        options = $.extend({}, daicuo.json.defaults, options);
        //美化JSON
        daicuo.json.beauty(options.element);
    },
    // 美化
    "beauty": function(element) {
        element = element || daicuo.json.defaults.element;
        $(element).each(function() {
            var jsonText = $(this).val();
            if(jsonText){
                $(this).val(JSON.stringify(JSON.parse(jsonText), null, 4));
            }
        });
    },
    // 丑化
    "ugly": function(element) {
        element = element || daicuo.json.defaults.element;
        $(element).each(function() {
            var jsonText = $(this).val();
            if(jsonText){
                $(this).val(JSON.stringify(JSON.parse(jsonText)));
            }
        });
    },
    // 配置
    defaults: {
        element: '.dc-json',
    },
};

// 拖拽排序
window.daicuo.sortable = {
    obj: '',
    init: function(element, options) {
        //合并初始参数
        options = $.extend({}, this.defaults, options);
        //调用拖拽
        daicuo.ajax.script('//lib.baomitu.com/Sortable/1.10.0/Sortable.min.js', function() {
            daicuo.sortable.obj = Sortable.create(element, options);
        });
    },
    defaults: {
        handle: '.dc-handle',
        //draggable: ".item",
        dataIdAttr: 'data-id',
        ghostClass: 'bg-secondary',
        onStart: function(event) {
            //
        },
        onEnd: function(event) {
            $(event.item).css({
                'transform': 'none'
            });
        }
    }
};

// 延迟加载
window.daicuo.lazyload = {
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
        $.getScript("//lib.baomitu.com/jquery.lazyload/1.9.1/jquery.lazyload.min.js", function(data, status, jqxhr) {
            daicuo.lazyload.ajaxEnd = true;
            if (typeof($callback) == "function") {
                $callback();
            }
        });
    }
};

// 轮播滑动
window.daicuo.carousel = {
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
        $.getScript("//lib.baomitu.com/flickity/2.2.0/flickity.pkgd.min.js", function(data, status, jqxhr) {
            daicuo.carousel.ajaxEnd = true;
            if (typeof($callback) == "function") {
                $callback();
            }
        });
    }
};

// AJAX翻页
window.daicuo.page = {
    locked: false,
    click: function($callback) {
        $(document).on("click", '[data-toggle="pageClick"],[data-pageClick]', function() {
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
};

// 简繁转换API
window.daicuo.language = {
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
        $.getScript("//cdn.daicuo.cc/jquery.s2t/0.1.0/s2t.min.js", function(data, status, jqxhr) {
            daicuo.language.ajaxEnd = true;
            if (typeof($callback) == "function") {
                $callback();
            }
        });
    }
};

// ajax请求封装
window.daicuo.ajax = {
    //初始调用或重载
    init: function(options){
        //合并初始参数
        var _options = $.extend({
            onReady: '[data-toggle="script"],[data-toggle="css"]',
            onClick: '[data-toggle="get"]',
        }, options);
        //监听.动态加载
        daicuo.ajax.onReady(_options.onReady);
        //监听.点击事件
        daicuo.ajax.onClick(_options.onClick);
    },
    //方法.监听动态加载
    onReady: function($element='[data-toggle="script"]'){
        $($element).each(function() {
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
    //方法.监听点击事件
    onClick: function($element='[data-toggle="get"]'){
        $(document).on("click", $element, function() {
            daicuo.bootstrap.dialog('<span class="fa fa-spinner fa-spin"></span> Loading...');
            daicuo.ajax.get($(this).attr('href'), $(this).attr('data-callback'));
            return false;
        });
    },
    //方法.回调任意函数
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
    //方法.浮动窗口
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
    //方法.表单提交
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
    //方法.动态加载样式表
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
    //方法.动态加载脚本
    script: function(urls, success, fail){
        // 请求池
        var cache = daicuo.ajax.script.cache = daicuo.ajax.script.cache || {};
        //console.log(cache);
        var getDeferred = function(url) {
            if( url in cache) return cache[url];
            return cache[url] = $.ajax({cache: true, dataType: "script", url: url});
            //return cache[url] = $.getScript( url );
        }
        // 参数适配，可以支持string|Array类型的参数
        urls = $.isArray(urls) ? urls : urls.split(/,|;|\s+/);
        var ajaxs = [];
        $.each(urls, function(index, value){
            ajaxs.push( getDeferred( value ) );
        });
        // 延迟对像
        $.when.apply(this, ajaxs).done(function(){
            daicuo.tools.call(success);
        }).fail(function(){
            daicuo.tools.call(fail);
        });
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
    // html自定义属性与对象合并
    extendAttr : function (obj, element) {
        if( typeof element == 'string'){
            element = $(element);
        }
        var attrs = [];
        $.each( obj, function( key, value ) {
            keyAttr = daicuo.tools.hump2Str(key);
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
    }
};

// 浏览器对象属性
window.daicuo.browser = {
    'url': document.URL,
    'domain': document.domain,
    'title': document.title,
    // zh-tw|zh-hk|zh-cn
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
    }()
};

// 加载语言包
$(document).ready(function () {
    daicuo.ajax.script(daicuo.config.root+'public/js/'+daicuo.config.lang+'.js');
});