{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("database_transform")}－{:lang('appName')}</title>
{/block}
{block name="header_addon"}
<link href="{$path_root}{$path_addon}view/theme.css">
<script src="{$path_root}{$path_addon}view/theme.js"></script>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("database_transform")}
</h6>
{:DcBuildForm([
    'class'    => 'bg-white px-2 py-2',
    'action'   => DcUrlAddon(['module'=>'database','controll'=>'transform','action'=>'update'],''),
    'method'   => 'post',
    'target'   => '_blank',
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'disabled' => false,
    'ajax'     => true,
    'callback' => '',
    'items'   => [
        [
            'type'=>'text',
            'name'=>'hostname',
            'id'=>'hostname',
            'title'=>lang('mysql_hostname'),
            'placeholder'=>lang('mysql_hostname_placeholder'),
            'tips'=>'',
            'value'=>'127.0.0.1',
            'readonly'=>false,
            'disabled'=>false,
            'required'=>true,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'database',
            'id'=>'database',
            'title'=>lang('mysql_database'),
            'placeholder'=>lang('mysql_database_placeholder'),
            'tips'=>'',
            'value'=>'',
            'readonly'=>false,
            'disabled'=>false,
            'required'=>true,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'username',
            'id'=>'username',
            'title'=>lang('mysql_username'),
            'placeholder'=>lang('mysql_username_placeholder'),
            'tips'=>'',
            'value'=>'',
            'readonly'=>false,
            'disabled'=>false,
            'required'=>true,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'password',
            'id'=>'password',
            'title'=>lang('mysql_password'),
            'placeholder'=>lang('mysql_password_placeholder'),
            'tips'=>'',
            'value'=>'',
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'hostport',
            'id'=>'hostport',
            'title'=>lang('mysql_hostport'),
            'placeholder'=>lang('mysql_hostport_placeholder'),
            'tips'=>'',
            'value'=>'3306',
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_right_tips'=>'',
        ],
        [
            'type'=>'text',
            'name'=>'prefix',
            'id'=>'prefix',
            'title'=>lang('mysql_prefix'),
            'placeholder'=>lang('mysql_prefix_placeholder'),
            'tips'=>'',
            'value'=>'dc_',
            'readonly'=>false,
            'disabled'=>false,
            'required'=>false,
            'class'=>'row form-group',
            'class_left'=>'col-md-2',
            'class_right'=>'col-md-6',
            'class_right_control'=>'',
            'class_right_tips'=>'',
        ],
    ]
])}
{/block}
<!-- -->
{block name="js"}
<script>
$(document).on('submit', '[data-toggle="form"]', function() {
    daicuo.bootstrap.dialog('<span class="fa fa-spinner fa-spin"></span> 请稍等');
});
</script>
{/block}