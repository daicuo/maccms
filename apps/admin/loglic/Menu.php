<?php
namespace app\admin\loglic;

class Menu
{

    public function fields($data=[])
    {
        return [
            'term_id' => [
                'order'           => 0,
                'type'            => 'hidden',
                'value'           => $data['term_id'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => '80',
                'data-width-unit' => 'px',
            ],
            'term_parent' => [
                'order'           => 505,
                'type'            => 'select',
                'value'           => $data['term_parent'],
                'option'          => DcTermOption(['controll'=>'menus']),
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_action' => [
                'order'           => 508,
                'type'            => 'select',
                'option'          => ['left'=>lang('left'),'top'=>lang('top')],
                'value'           => DcEmpty($data['term_action'],'left'),
                'placeholder'     => '',
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_name' => [
                'order'           => 1,
                'type'            => 'text',
                'value'           => $data['term_name'],
                'required'        => true,
                'title'           => lang('menu_name'),
                'placeholder'     => '',
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'term_slug' => [
                'order'           => 2,
                'type'            => 'text',
                'value'           => $data['term_slug'],
                'title'           => lang('menu_slug'),
                'data-title'      => lang('menu_slug'),
                'placeholder'     => 'module/controll/action',
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'term_info' => [
                'order'           => 3,
                'type'            => 'text',
                'value'           => DcEmpty($data['term_info'],'fa-gear'),
                'title'           => lang('menu_ico'),
                'placeholder'     => '',
                'data-filter'     => false,
                'data-visible'    => false,
            ],
            'term_status' => [
                'order'           => 506,
                'type'            => 'select',
                'value'           => DcEmpty($data['term_status'],'normal'),
                'option'          => model('common/Attr','loglic')->statusOption(),
                'data-filter'     => true,
                'data-visible'    => false,
            ],
            'term_status_text' => [
                'order'           => 506,
                'data-title'      => lang('term_status'),
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_type' => [
                'order'           => 4,
                'type'            => 'select',
                'option'          => model('common/Attr','loglic')->target(),
                'value'           => DcEmpty($data['term_type'],'_self'),
                'title'           => lang('menu_target'),
                'data-title'      => lang('menu_target'),
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_order' => [
                'order'           => 507,
                'type'            => 'number',
                'value'           => intval($data['term_order']),
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_controll' => [
                'order'           => 509,
                'type'            => 'hidden',
                'value'           => 'menus',
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_module' => [
                'order'           => 510,
                'type'            => 'text',
                'value'           => DcEmpty($data['term_module'],'admin'),
                'data-value'      => '',
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'html_3' => [
                'order'           => 0,
                'type'            => 'html',
                'value'           => '',
            ]
        ];
    }

}