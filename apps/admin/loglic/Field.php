<?php
namespace app\admin\loglic;

class Field
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
                'data-sort-name'  => 'op_id',
                'data-order'      => 'asc',
            ],
            'op_autoload' => [
                'order'           => 0,
                'type'            => 'hidden',
                'value'           => 'field',
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
                'order'           => 502,
                'data-title'      => lang('op_status'),
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_name' => [
                'order'           => 1,
                'type'            => 'text',
                'value'           => $data['op_name'],
                'title'           => lang('field_name'),
                'required'        => true,
                'placeholder'     => '',
                'data-title'      => lang('field_name'),
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'title' => [
                'order'           => 2,
                'data-visible'    => true,
                'data-align'      => 'left',
                'data-title'      => lang('field_title'),
            ],
            'type' => [
                'order'           => 3,
                'data-visible'    => true,
                'data-title'      => lang('field_type'),
            ],
            'relation' => [
                'order'           => 4,
                'data-visible'    => true,
                'data-title'      => lang('field_relation'),
            ],
            'op_value' => [
                'order'           => 0,
                'type'            => 'json',
                'value'           => DcEmpty($data['op_value'],json_encode([
                    'type'         => 'text',
                    'relation'     => 'eq',
                    'data-visible' => false,
                    'data-filter'  => false,
                ])),
                'rows'            => 5,
                'title'           => lang('field_attr'),
                'placeholder'     => 'JSON格式',
            ],
            'op_module' => [
                'order'           => 510,
                'type'            => 'text',
                'value'           => DcEmpty($data['op_module'],'index'),
                'title'           => lang('field_module'),
                'required'        => true,
                'placeholder'     => '',
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-value'      => '',
            ],
            'op_controll' => [
                'order'           => 509,
                'type'            => 'text',
                'value'           => DcEmpty($data['op_controll'],'index'),
                'title'           => lang('field_controll'),
                'required'        => true,
                'placeholder'     => '',
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-value'      => '',
            ],
            'op_action' => [
                'order'           => 508,
                'type'            => 'text',
                'value'           => DcEmpty($data['op_action'],'index'),
                'title'           => lang('field_action'),
                'placeholder'     => '',
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-value'      => '',
            ],
            'op_order' => [
                'order'           => 507,
                'type'            => 'number',
                'value'           => intval($data['op_order']),
                'placeholder'     => '',
                'title'           => lang('field_order'),
                'data-title'      => lang('field_order'),
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-sortable'   => true,
            ]
        ];
    }
}