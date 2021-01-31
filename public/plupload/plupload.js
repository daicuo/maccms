window.daicuo.upload = {
    //初始化
    init: function(options){
        if(options){
            var element = options.element;
        }else{
            var element = daicuo.upload.defaults.element;
        }
        $(element).each(function(index, value) {
            //读取HTML属性配置
            var optionsAttr = daicuo.tools.extendAttr(daicuo.upload.defaults, $(this));
            //console.log(optionsAttr);
            //合并HTML属性配置与传参配置
            var optionsUpload = $.extend({}, optionsAttr, options);
            console.log(optionsUpload);
            //上传实例编号
            optionsUpload.index = index;
            //加载上传实例
            daicuo.upload.ajax(optionsUpload);
        });
    },
    start: function(options){
        var uploadDC = $.extend({}, daicuo.upload.defaults, options);//合并初始配置
        var element = $(uploadDC.element).get(uploadDC.index);//容器ID
        var defaults = {
            browse_button : element,//触发文件选择对话框的按钮，为那个元素id
            container: $(element).parent().get(0), //取按钮的上级元素
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
                    daicuo.tools.call(uploadDC.onInit,[up]);
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
                    if(uploadDC.auto){
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
                        if($(uploadDC.input).length > 0){
                            var urls = [];
                            //单文件
                            urls.push(file.responseTp.data.url);
                            //多文件
                            $.each(file.responseTp.item, function (index, value) {
                                urls.push(value.url);
                            });
                            //回填input
                            $(uploadDC.input).val(urls.join(','));
                        }
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
                    $(element).prop("disabled", false).html($(element).data('back-html'));
                    daicuo.tools.call(uploadDC.onComplete, [up, files]);
                },
                //上传错误后
                Error: function(up, error) {
                    //error.code error.message
                    daicuo.bootstrap.dialog(error.message);
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
        //拖拽上传区域
        elementDrop: undefined,
        //文本框目标ID
        input: '',
        //多个上传标识
        index: 0,
        //表单名称
        name: 'file',
        //上传时的其它参数
        params:{},
        //是否选完后自动上传
        auto: true,
        //是否多选
        multiple: false,
        //以multipart/form-data的形式来上传文件
        multipart: true,
        //上传地址
        url: '/demo/form/save',
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