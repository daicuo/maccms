{extend name="./public/static/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("indexAdmin")}－{:lang('appName')}</title>
{/block}
{block name="header_addon"}
<link href="{$path_root}{$path_addon}view/theme.css" rel="stylesheet">
<script src="{$path_root}{$path_addon}view/theme.js"></script>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("indexAdmin")}
</h6>
{:DcBuildForm([
    'class'    => 'bg-white px-2 py-2',
    'action'   => DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'update'],''),
    'method'   => 'post',
    'ajax'     => true,
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'disabled' => false,
    'callback' => '',
    'items'   => [
        [
            'type'=>'text',
            'name'=>'site_title',
            'id'=>'site_title',
            'title'=>lang('site_title'),
            'placeholder'=>lang('site_title_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.site_title'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>true,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'site_keywords',
            'id'=>'site_keywords',
            'title'=>lang('site_keywords'),
            'placeholder'=>lang('site_keywords_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.site_keywords'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'textarea',
            'name'=>'site_description',
            'id'=>'site_description',
            'title'=>lang('site_description'),
            'placeholder'=>lang('site_description_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.site_description'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'select.custom',
            'name'=>'theme',
            'id'=>'theme',
            'title'=>lang('site_theme'),
            'placeholder'=>lang('site_theme_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.theme'),
            'option'=>$themes,
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'multiple'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'select.custom',
            'name'=>'theme_wap',
            'id'=>'theme_wap',
            'title'=>lang('wap_theme'),
            'placeholder'=>lang('wap_theme_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.theme_wap'),
            'option'=>$themes,
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'multiple'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'number',
            'name'=>'page_size',
            'id'=>'page_size',
            'title'=>lang('page_size'),
            'placeholder'=>lang('page_size_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.page_size'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'maxlength'=>'8',
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'image_url',
            'id'=>'image_url',
            'title'=>lang('image_url'),
            'placeholder'=>lang('image_url_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.image_url'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'api_url',
            'id'=>'api_url',
            'title'=>lang('api_url'),
            'placeholder'=>lang('api_url_placeholder'),
            'tips'=>lang('api_bind'),
            'value'=>config('maccms.api_url'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>true,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control mb-3',
            'class_right_tips'=>'h6 api-add',
        ],
        [
            'type'=>'text',
            'name'=>'api_params',
            'id'=>'api_params',
            'title'=>lang('api_params'),
            'placeholder'=>lang('api_params_placeholder'),
            'tips'=>'',
            'value'=>config('maccms.api_params'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control',
            'class_right_tips'=>'',
        ],
    ]
])}
{/block}
<!-- -->
{block name="js"}
<script>
//监听分析资源站事件
$(document).on("click", '.api-add', function() {
    if($('#api_url').val()){
        daicuo.bootstrap.dialog.tips('<span class="fa fa-spinner fa-spin"></span> Loading...');
        daicuo.ajax.get( '../addon/index?module=maccms&controll=type&action=index&apiurl='+$('#api_url').val(),function(){
            //监听提交绑定的事件
            $(document).on('submit', '.form-bind[data-toggle="form"]', function(){
                $(this).html('<span class="fa fa-spinner fa-spin"></span> Loading...');
            });
        });
    }else{
        daicuo.bootstrap.dialog.tips('请先添加资源站地址后再使用此功能！');
    }
    $('.modal-dialog').addClass('modal-dialog-scrollable modal-lg');
});
//提交绑定的回调函数
var callAjax = function($data, $status, $xhr){
    setTimeout('$(".dc-modal").modal("hide");location.href="../category/index"', 1000);
};
</script>
{/block}