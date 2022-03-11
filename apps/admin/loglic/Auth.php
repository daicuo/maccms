<?php
namespace app\admin\loglic;

class Auth
{
    public function fields($data)
    {
        return [
            'op_id' => [
                'order'           => 0,
                'type'            => 'hidden',
                'value'           => $data['op_id'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => '80',
                'data-width-unit' => 'px',
                'data-sortable'   => true,
            ],
            'op_status' => [
                'order'           => 0,
                'type'            => 'select',
                'value'           => DcEmpty($data['op_status'],'normal'),
                'option'          => model('common/Attr','loglic')->statusOption(),
                'data-filter'     => true,
                'data-visible'    => false,
            ],
            'op_status_text' => [
                'order'           => 506,
                'data-title'      => lang('op_status'),
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_action' => [
                'order'           => 508,
                'type'            => 'select',
                'option'          => ['back'=>lang('auth_back'),'front'=>lang('auth_front'),'system'=>lang('auth_system')],
                'value'           => DcEmpty($data['op_action'],'back'),
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_name' => [
                'order'           => 1,
                'type'            => 'select',
                'value'           => $data['op_name'],
                'option'          => model('common/Role','loglic')->option(),
                'required'        => true,
                'title'           => lang('role_name'),
                'data-title'      => lang('role_name'),
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 150,
            ],
            'role_info' => [
                'order'           => 2,
                'data-title'      => lang('role_info'),
                'data-visible'    => true,
                'data-width'      => 150,
            ],
            'op_value' => [
                'order'           => 2,
                'type'            => 'text',
                'value'           => $data['op_value'],
                'required'        => true,
                'title'           => lang('auth_caps'),
                'placeholder'     => lang('auth_caps_tips'),
                'data-title'      => lang('auth_caps'),
                'data-filter'     => true,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'auth_info' => [
                'order'           => 3,
                'data-title'      => lang('auth_info'),
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'op_controll' => [
                'order'           => 509,
                'type'            => 'hidden',
                'value'           => 'auth',
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_module' => [
                'order'           => 510,
                'type'            => 'text',
                'value'           => DcEmpty($data['op_module'],'admin'),
                'required'        => true,
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-value'      => '',
            ],
            'op_order' => [
                'order'           => 507,
                'type'            => 'hidden',
                'value'           => intval($data['op_order']),
            ],
            'op_autoload' => [
                'order'           => 20,
                'type'            => 'hidden',
                'value'           => 'no',
            ],
        ];
    }
}