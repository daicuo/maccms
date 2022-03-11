<?php
namespace app\admin\loglic;

class Lang
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
                'order'           => 506,
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
                'title'           => lang('lang_name'),
                'placeholder'     => '',
                'data-title'      => lang('lang_name'),
                'data-align'      => 'left',
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 150,
            ],
             'op_value' => [
                'order'           => 12,
                'type'            => 'text',
                'value'           => $data['op_value'],
                'required'        => true,
                'title'           => lang('lang_value'),
                'placeholder'     => '',
                'data-title'      => lang('lang_value'),
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'op_action' => [
                'order'           => 508,
                'type'            => 'text',
                'value'           => DcEmpty($data['op_action'],'zh-cn'),
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_controll' => [
                'order'           => 509,
                'type'            => 'hidden',
                'value'           => 'lang',
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_module' => [
                'order'           => 510,
                'type'            => 'text',
                'value'           => DcEmpty($data['op_module'],'common'),
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_order' => [
                'order'           => 507,
                'type'            => 'hidden',
                'value'           => intval($data['op_order']),
                'data-filter'     => false,
                'data-visible'    => false,
            ],
            'op_autoload' => [
                'order'           => 20,
                'type'            => 'hidden',
                'value'           => 'no',
            ],
        ];
    }
}