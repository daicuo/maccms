$(function() {
    //扩展daicuo.admin
    $.extend(daicuo.admin, {
        // 初始化
        init: function(){
            //侧栏事件
            this.sideBar();
            //版本检测
            this.version();
            //表格数据
            this.table.init('table[data-toggle="bootstrap-table"]');
            //通用方法
            window.daicuo.ajax.init();
            window.daicuo.form.init();
            window.daicuo.upload.init();
            window.daicuo.json.init();
            window.daicuo.tags.init();
        },
        // 侧栏点击事件
        sideBar: function() {
            $(document).on('click', '[data-toggle="main-left"]', function() {
                $('.main-left').toggleClass('open');
                $('.main-left').toggleClass('d-block');
                $('.main-right').toggleClass('col-12');
            });
        },
        // 版本检测 获取服务器最新版本jsonp格式
        version: function() {
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
                            $dom[key].html('<a class="badge badge-purple" href="' + $json.upgrade + '" data-toggle="get"><i class="fa fa-arrow-circle-up"></i> ' + $json.version + '</a> <a class="badge badge-secondary" href="' + $json.update + '" target="_blank">' + $json.msg + '</a>');
                        }
                    }
                });
                $dom[key].removeClass();
            });
        },
        dialog: function(){
            //var modalXl = $('data-toggle="create"').data['modal-xl'];
            //$(event.currentTarget).data('modal-xl',0);
        },
        //表格
        table : {
            uniqueId   : '',
            urlSort    : '',
            urlEdit    : '',
            urlDelete  : '',
            urlPreview : '',
            // 表格初始化
            init : function(selector){
                //常用属性
                daicuo.admin.table.uniqueId   = $(selector).data('unique-id');
                daicuo.admin.table.urlSort    = $(selector).data('url-sort');
                daicuo.admin.table.urlEdit    = $(selector).data('url-edit');
                daicuo.admin.table.urlDelete  = $(selector).data('url-delete');
                daicuo.admin.table.urlPreview = $(selector).data('url-preview');
                //表格初始化
                window.daicuo.table.init({selector: selector});
                //筛选条件
                daicuo.admin.table.filter(selector);
                //daicuo.browser.console(table);
            },
            // 表格列格式器 data-formatter
            formatter : function(value, row, index, field){
                var $url = daicuo.config.file + '/' + daicuo.config.controll + '/' + daicuo.config.action + '/?'+ field +'='+value;
                return '<a class="text-purple" href="'+$url+'">'+value+'</a>';
            },
            // 表格列点击事件 data-events
            events : {
                'click [data-toggle="edit"]': function (event, value, row, index) {
                    //daicuo.browser.console(event);
                    //$(event.currentTarget).attr('data-callback','daicuo.' + daicuo.config.module + '.' + daicuo.config.controll + '.edit');
                    var $btnCreate = $('a[data-toggle="create"]');
                    if($btnCreate.length < 1){
                        $(event.currentTarget).removeAttr('data-toggle');
                        return false;
                    }
                    //窗口大小
                    $(event.currentTarget).data('modal-xl',$btnCreate.data('modal-xl'));
                    //是否回调
                    if($btnCreate.data('callback')){
                        $(event.currentTarget).data('callback',$btnCreate.data('callback'));
                    }
                }
            },
            // 筛选事件
            filter : function(selector){
                $('#filter-row').on('shown.bs.collapse', function () {
                    $(selector).bootstrapTable('refreshOptions', {
                        queryParams: daicuo.admin.table.query
                    });
                });
                $('#filter-row').on('hidden.bs.collapse', function () {
                    $(selector).bootstrapTable('refreshOptions', {
                        queryParams: daicuo.table.query
                    });
                });
            },
            // 回调函数－操作列
            operate : function(value, row, index, field){
                var id = row[daicuo.admin.table.uniqueId];
                var array = new Array(); 
                array.push('<div class="btn-group btn-group-sm">');
                if(daicuo.admin.table.urlPreview){
                    array.push('<a class="btn btn-outline-secondary" href="'+daicuo.admin.table.urlPreview+id+'" data-toggle="preview" target="_blank"><i class="fa fa-fw fa-link"></i></a>');
                }
                if(daicuo.admin.table.urlEdit){
                    array.push('<a class="btn btn-outline-secondary" href="'+daicuo.admin.table.urlEdit+id+'" data-toggle="edit" data-modal-xl="1"><i class="fa fa-fw fa-pencil"></i></a>');
                }
                if(daicuo.admin.table.urlDelete){
                    array.push('<a class="btn btn-outline-secondary" href="'+daicuo.admin.table.urlDelete+id+'" data-toggle="delete"><i class="fa fa-fw fa-trash-o"></i></a>');
                }
                array.push('</div>');
                return array.join('');
            },
            // 回调函数－筛选参数
            query : function(params){
                var filter = {};
                $('.dc-filter').each( function () {
                    if( $(this).val() ){
                        filter[$(this).attr('name')] = $(this).val();
                    }
                });
                return $.extend(filter, {
                    pageSize: params.pageSize,
                    pageNumber: params.pageNumber,
                    sortName: params.sortName,
                    sortOrder: params.sortOrder,
                    searchText: params.searchText
                });
            }
        },
        // 应用模块
        store : {
            //筛选选项
            query: function(params){
              return {
                 pageNumber: params.pageNumber, 
                 pageSize: params.pageSize,
                 sortName: params.sortName,
                 sortOrder: params.sortOrder,
                 searchText: params.searchText,
                 termId: $("#term_id").val(),
                 price: $("#price").val()
              };
            }
        }
    }); //extend
    //初始化后台JS
    window.daicuo.admin.init();
}); //jquery