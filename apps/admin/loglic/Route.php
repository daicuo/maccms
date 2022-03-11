<?php
namespace app\admin\loglic;

class Route 
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
                'data-width'      => 80,
                'data-width-unit' => 'px',
                'data-sortable'   => true,
                'data-sort-name'  => 'op_id',
                'data-order'      => 'asc',
            ],
            'rule' => [
                'order'           => 3,
                'type'            => 'text',
                'value'           => $data['rule'],
                'required'        => true,
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'address'             => [
                'order'           => 4,
                'type'            => 'text',
                'value'           => $data['address'],
                'required'        => true,
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'method' => [
                'order'           => 5,
                'type'            => 'select',
                'value'           => $data['method'],
                'option'          => [
                                  'get'    => lang('method_option_0'),
                                  'post'   => lang('method_option_1'),
                                  'put'    => lang('method_option_2'),
                                  'delete' => lang('method_option_3'),
                                  '*'      => lang('method_option_4')
                                  ],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => '100',
            ],
            'op_status' => [
                'order'           => 6,
                'type'            => 'select',
                'value'           => DcEmpty($data['op_status'],'normal'),
                'option'          => model('common/Attr','loglic')->statusOption(),
                'data-filter'     => true,
            ],
            'op_status_text' => [
                'order'           => 6,
                'data-title'      => lang('op_status'),
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'option' => [
                'order'           => 7,
                'type'            => 'json',
                'value'           => $data['option'],
                'rows'            => 6,
                'data-filter'     => false,
                'data-visible'    => false,
            ],
            'pattern' => [
                'order'           => 8,
                'type'            => 'json',
                'value'           => $data['pattern'],
                'rows'            => 6,
                'data-filter'     => false,
                'data-visible'    => false,
            ],
            'op_action' => [
                'order'           => 508,
                'type'            => 'hidden',
                'value'           => 'system',
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_controll' => [
                'order'           => 509,
                'type'            => 'hidden',
                'value'           => 'route',
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_module' => [
                'order'           => 510,
                'type'            => 'hidden',
                'value'           => DcEmpty($data['op_module'],'common'),
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'op_order' => [
                'order'           => 2,
                'type'            => 'text',
                'value'           => intval($data['op_order']),
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 60,
                'data-sortable'   => true,
                'data-sort-name'  => 'op_order',
                'data-order'      => 'asc',
                'data-formatter'  => 'daicuo.table.sort',
            ],
        ];
    }
}