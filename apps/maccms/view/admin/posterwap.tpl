{extend name="./apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin_poster_wap")}－{:lang('appName')}</title>
{/block}
{block name="header_addon"}
<link href="{$path_root}{$path_addon}view/theme.css" rel="stylesheet">
<script src="{$path_root}{$path_addon}view/theme.js"></script>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("admin_poster_wap")}
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
            'type'=>'textarea',
            'name'=>'header_wap',
            'id'=>'header_wap',
            'title'=>lang('header_wap'),
            'placeholder'=>lang('header_wap_placeholder'),
            'tips'=>'<p>调用代码</p><p>'.DcTplLabelOp('maccms','header_wap').'</p>',
            'value'=>config('maccms.header_wap'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>4,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control form-control-sm',
            'class_tips'=>'',
        ],
        [
            'type'=>'textarea',
            'name'=>'thread_wap',
            'id'=>'thread_wap',
            'title'=>lang('thread_wap'),
            'placeholder'=>lang('thread_wap_placeholder'),
            'tips'=>'<p>调用代码</p><p>'.DcTplLabelOp('maccms','thread_wap').'</p>',
            'value'=>config('maccms.thread_wap'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>4,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control form-control-sm',
            'class_tips'=>'',
        ],
        [
            'type'=>'textarea',
            'name'=>'footer_wap',
            'id'=>'footer_wap',
            'title'=>lang('footer_wap'),
            'placeholder'=>lang('footer_wap_placeholder'),
            'tips'=>'<p>调用代码</p><p>'.DcTplLabelOp('maccms','footer_wap').'</p>',
            'value'=>config('maccms.footer_wap'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>4,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control form-control-sm',
            'class_tips'=>'',
        ],
        [
            'type'=>'textarea',
            'name'=>'left_wap',
            'id'=>'left_wap',
            'title'=>lang('left_wap'),
            'placeholder'=>lang('left_wap_placeholder'),
            'tips'=>'<p>调用代码</p><p>'.DcTplLabelOp('maccms','left_wap').'</p>',
            'value'=>config('maccms.left_wap'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>4,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control form-control-sm',
            'class_tips'=>'',
        ],
        [
            'type'=>'textarea',
            'name'=>'center_wap',
            'id'=>'center_wap',
            'title'=>lang('center_wap'),
            'placeholder'=>lang('left_wap_placeholder'),
            'tips'=>'<p>调用代码</p><p>'.DcTplLabelOp('maccms','center_wap').'</p>',
            'value'=>config('maccms.center_wap'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>4,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control form-control-sm',
            'class_tips'=>'',
        ],
        [
            'type'=>'textarea',
            'name'=>'right_wap',
            'id'=>'right_wap',
            'title'=>lang('right_wap'),
            'placeholder'=>lang('left_wap_placeholder'),
            'tips'=>'<p>调用代码</p><p>'.DcTplLabelOp('maccms','right_wap').'</p>',
            'value'=>config('maccms.right_wap'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>4,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control form-control-sm',
            'class_tips'=>'',
        ],
        [
            'type'=>'textarea',
            'name'=>'one_wap',
            'id'=>'one_wap',
            'title'=>lang('one_wap'),
            'placeholder'=>lang('one_wap_placeholder'),
            'tips'=>'<p>调用代码</p><p>'.DcTplLabelOp('maccms','one_wap').'</p>',
            'value'=>config('maccms.one_wap'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>4,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control form-control-sm',
            'class_tips'=>'',
        ],
        [
            'type'=>'textarea',
            'name'=>'two_wap',
            'id'=>'two_wap',
            'title'=>lang('two_wap'),
            'placeholder'=>lang('two_wap_placeholder'),
            'tips'=>'<p>调用代码</p><p>'.DcTplLabelOp('maccms','two_wap').'</p>',
            'value'=>config('maccms.two_wap'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>4,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control form-control-sm',
            'class_tips'=>'',
        ],
        [
            'type'=>'textarea',
            'name'=>'three_wap',
            'id'=>'three_wap',
            'title'=>lang('three_wap'),
            'placeholder'=>lang('three_wap_placeholder'),
            'tips'=>'<p>调用代码</p><p>'.DcTplLabelOp('maccms','three_wap').'</p>',
            'value'=>config('maccms.three_wap'),
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'rows'=>4,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'form-control form-control-sm',
            'class_tips'=>'',
        ],
    ]
])}
{/block}
<!-- -->
{block name="js"}
{/block}