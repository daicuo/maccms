<?php
namespace app\admin\loglic;

class User
{
    public function fields($data=[])
    {
        $fields = [
            'html_1' => [
                'order'           => 0,
                'type'            => 'html',
                'value'           => '<div class="row"><div class="col-12 col-md-8">',
            ],
            'user_id' => [
                'order'           => 1,
                'type'            => 'hidden',
                'value'           => $data['user_id'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-sortable'   => true,
                'data-width'      => 80,
            ],
            'user_name' => [
                'order'           => 2,
                'type'            => 'text',
                'value'           => $data['user_name'],
                'required'        => true,
                'data-filter'     => false,
                'data-visible'    => true,
                'data-escape'     => true,
                'data-align'      => 'left',
            ],
            'user_nice_name' => [
                'order'           => 3,
                'type'            => 'text',
                'value'           => DcEmpty($data['user_nice_name'],uniqid()),
                'data-filter'     => false,
                'data-visible'    => true,
                'data-escape'     => true,
                'data-width'      => 150,
                'data-class'      => 'text-wrap',
                'data-align'      => 'left',
            ],
            'user_slug' => [
                'order'           => 0,
                'type'            => 'text',
                'value'           => $data['user_slug'],
            ],
            'user_pass' => [
                'order'           => 0,
                'type'            => 'password',
                'value'           => $data['user_pass'],
            ],
            'user_views' => [
                'order'           => 506,
                'type'            => 'text',
                'value'           => intval($data['user_views']),
                'data-sortable'   => true,
                'data-visible'    => true,
                'data-width'      => 80,
            ],
            'user_hits' => [
                'order'           => 507,
                'type'            => 'text',
                'value'           => intval($data['user_hits']),
                'data-sortable'   => true,
                'data-visible'    => true,
                'data-width'      => 80,
            ],
            'user_create_time' => [
                'order'           => 508,
                'type'            => 'text',
                'value'           => $data['user_create_time'],
            ],
            'user_update_time' => [
                'order'           => 509,
                'type'            => 'text',
                'value'           => $data['user_update_time'],
                'data-visible'    => true,
                'data-width'      => 120,
                'data-sortable'   => true,
            ],
            'user_create_ip' => [
                'order'           => 0,
                'type'            => 'text',
                'value'           => $data['user_create_ip'],
            ],
            'user_update_ip' => [
                'order'           => 510,
                'type'            => 'text',
                'value'           => $data['user_update_ip'],
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'html_2'      => [
                'order'           => 7,
                'type'            => 'html',
                'value'           => '</div><div class="col-12 col-md-4">',
            ],
            'user_status' => [
                'order'           => 0,
                'type'            => 'select',
                'value'           => DcEmpty($data['user_status'],'normal'),
                'option'          => model('common/Attr','loglic')->statusOption(),
                'data-filter'     => true,
                'data-visible'    => false,
            ],
            'user_status_text' => [
                'order'           => 500,
                'data-title'      => lang('user_status'),
                'data-visible'    => true,
                'data-width'      => 80,
            ],
            'user_email' => [
                'order'           => 4,
                'type'            => 'email',
                'value'           => $data['user_email'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-escape'     => true,
                'data-width'      => 120,
            ],
            'user_mobile' => [
                'order'           => 5,
                'type'            => 'text',
                'value'           => $data['user_mobile'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-escape'     => true,
                'data-sortable'   => true,
                'data-width'      => 120,
            ],
            'user_capabilities' => [
                'order'         => 6,
                'type'          => 'select',
                'option'        => model('common/Role','loglic')->option(),
                'value'         => $data['user_capabilities'],
                'multiple'      => true,
                'size'          => 5,
                'data-filter'   => true,
                'data-visible'  => true,
            ],
            'user_caps' => [
                'order'  => 0,
                'type'   => 'textarea',
                'value'  => $data['user_caps'],
                'rows'   => 5,
            ],
            'user_token' => [
                'order'  => 0,
                'type'   => 'text',
                'value'  => $data['user_token'],
            ],
            'user_expire' => [
                'order'   => 0,
                'type'    => 'text',
                'value'   => $data['user_expire'],
            ],
            'html_3' => [
                'order'   => 999,
                'type'    => 'html',
                'value'   => '</div></div>',
            ]
        ];
        //自动处理动态扩展字段
        $customs = model('common/Field','loglic')->forms(['controll'=>'user']);
        //合并用户模块所有字段
        if($customs){
            $fields = DcArrayPush($fields, DcFields($customs, $data), 'user_views');
        }
        //返回所有表单字段
        return $fields;
    }
}