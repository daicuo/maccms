<?php
namespace app\admin\loglic;

class Tag
{
    public function fields($data)
    {
        $module   = DcEmpty($data['term_module'],'index');
        $controll = 'tag';
        $action   = DcEmpty($data['term_action'],'index');
        $fields = [
            'html_1' => [
                'order'           => 1,
                'type'            => 'html',
                'value'           => '<div class="row"><div class="col-12 col-md-8">',
            ],
            'term_id' => [
                'order'           => 2,
                'type'            => 'hidden',
                'value'           => $data['term_id'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => '80',
                'data-width-unit' => 'px',
                'data-sortable'   => true,
                'data-sort-name'  => 'term_id',
                'data-order'      => 'asc',
            ],
            'term_type' => [
                'order'           => 3,
                'type'            => 'hidden',
                'value'           => 'tag',
                'data-filter'     => false,
                'data-visible'    => false,
            ],
            'term_name' => [
                'order'           => 4,
                'type'            => 'text',
                'value'           => $data['term_name'],
                'required'        => true,
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'term_slug' => [
                'order'           => 5,
                'type'            => 'text',
                'value'           => $data['term_slug'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'term_title' => [
                'order'           => 6,
                'type'            => 'text',
                'value'           => $data['term_title'],
            ],
            'term_keywords' => [
                'order'           => 7,
                'type'            => 'text',
                'value'           => $data['term_keywords'],
            ],
            'term_description' => [
                'order'           => 8,
                'type'            => 'text',
                'value'           => $data['term_description'],
            ],
            'term_info' => [
                'order'           => 9,
                'type'            => 'text',
                'value'           => $data['term_info'],
                'data-filter'     => false,
                'data-visible'    => false,
            ],
            'html_2'      => [
                'order'           => 8,
                'type'            => 'html',
                'value'           => '</div><div class="col-12 col-md-4">',
                'order'           => 199,
            ],
            'term_status' => [
                'order'           => 0,
                'type'            => 'select',
                'value'           => DcEmpty($data['term_status'],'normal'),
                'option'          => model('common/Attr','loglic')->statusOption(),
                'data-filter'     => true,
                'data-visible'    => false,
            ],
            'term_status_text' => [
                'order'           => 505,
                'data-title'      => lang('term_status'),
                'data-visible'    => true,
                'data-width'      => '100',
            ],
            'term_parent' => [
                'order'           => 6,
                'type'            => 'text',
                'value'           => intval($data['term_parent']),
                'data-filter'     => false,
                'data-visible'    => false,
            ],
            'term_order' => [
                'order'           => 506,
                'type'            => 'text',
                'value'           => intval($data['term_order']),
                'data-filter'     => false,
                'data-visible'    => true,
                'data-sortable'   => true,
                'data-width'      => '100',
            ],
            'term_count' => [
                'order'           => 507,
                'data-filter'     => false,
                'data-visible'    => true,
                'data-sortable'   => true,
                'data-width'      => '100',
            ],
            'term_action' => [
                'order'           => 508,
                'type'            => 'text',
                'value'           => $action,
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-value'      => '',
            ],
            'term_controll' => [
                'order'           => 509,
                'type'            => 'hidden',
                'value'           => $controll,
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_module' => [
                'order'           => 510,
                'type'            => 'text',
                'value'           => $module,
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-value'      => '',
            ],
            'html_3' => [
                'order'           => 16,
                'type'            => 'html',
                'value'           => '</div></div>',
            ]
        ];
        //动态扩展字段
        $customs = model('common/Term','loglic')->metaList($module, $controll);
        //合并所有字段
        if($customs){
            $fields = DcArrayPush($fields, DcFields($customs, $data), 'html_2');
        }
        //返回所有表单字段
        return $fields;
    }
}