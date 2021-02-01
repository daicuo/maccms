{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("execute_sql")}－{:lang('appName')}</title>
{/block}
{block name="header_addon"}
<link href="{$path_root}{$path_addon}view/theme.css">
<script src="{$path_root}{$path_addon}view/theme.js"></script>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("execute_sql")}
</h6>
{:DcBuildForm([
    'name'     => 'execute',
    'class'    => 'bg-white py-2',
    'action'   => DcUrlAddon(['module'=>'database','controll'=>'execute','action'=>'update'],''),
    'method'   => 'post',
    'submit'   => lang('submit'),
    'reset'    => lang('reset'),
    'close'    => false,
    'disabled' => false,
    'ajax'     => true,
    'callback' => '',
    'items'   => [
        [
            'type'                => 'textarea',
            'name'                => 'database_sql',
            'id'                  => 'database_sql',
            'title'               => lang('execute_sql'),
            'placeholder'         => lang('execute_sql_placeholder'),
            'tips'                => '',
            'value'               => '',
            'readonly'            => false,
            'disabled'            => false,
            'required'            => true,
            'rows'                => 20,
            'class'               => 'row form-group',
            'class_left'          => 'd-none',
            'class_right'         => 'col-12',
            'class_right_control' => 'form-control',
            'class_right_tips'    => '',
        ],
    ]
])}
{/block}
<!-- -->
{block name="js"}
{/block}