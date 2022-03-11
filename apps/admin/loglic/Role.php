<?php
namespace app\admin\loglic;

class Role
{
    public function fields($data)
    {
        return [
            'op_id' => [
                'order'           => 1,
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
            'op_name' => [
                'order'           => 2,
                'type'            => 'text',
                'value'           => $data['op_name'],
                'required'        => true,
                'title'           => lang('role_name'),
                'placeholder'     => lang('role_name_tips'),
                'data-title'      => lang('role_name'),
                'data-filter'     => true,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'op_value' => [
                'order'           => 3,
                'type'            => 'text',
                'value'           => $data['op_value'],
                'multiple'        => true,
                'size'            => 15,
                'title'           => lang('role_info'),
                'data-title'      => lang('role_info'),
                'data-filter'     => false,
                'data-visible'    => true,
            ],
            'op_action' => [
                'order'            => 508,
                'type'             => 'hidden',
                'value'            => 'system',
                'class_right'      => 'col-12',
                'class_right_check'=> 'form-check form-check-inline',
                'data-visible'     => true,
                'data-width'       => 100,
            ],
            'op_controll' => [
                'order'           => 509,
                'type'            => 'hidden',
                'value'           => 'role',
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_module' => [
                'order'           => 510,
                'type'            => 'hidden',
                'value'           => DcEmpty($data['op_module'],'admin'),
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_order' => [
                'order'           => 507,
                'type'            => 'text',
                'value'           => intval($data['op_order']),
                'data-visible'    => true,
                'data-width'      => 100,
                'data-sortable'   => true,
            ],
            'op_autoload' => [
                'order'           => 20,
                'type'            => 'hidden',
                'value'           => 'no',
            ],
        ];
    }
}