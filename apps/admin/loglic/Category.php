<?php
namespace app\admin\loglic;

class Category
{
    public function fields($data)
    {
        $module   = DcEmpty($data['term_module'],'index');
        $controll = 'category';
        $action   = DcEmpty($data['term_action'],'index');
        $fields = [
            'html_1' => [
                'order'           => 0,
                'type'            => 'html',
                'value'           => '<div class="row"><div class="col-12 col-md-8">',
            ],
            'term_id' => [
                'order'           => 1,
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
            'term_name' => [
                'order'           => 2,
                'type'            => 'text',
                'value'           => $data['term_name'],
                'required'        => true,
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'term_slug' => [
                'order'           => 3,
                'type'            => 'text',
                'value'           => $data['term_slug'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'term_title' => [
                'order'           => 0,
                'type'            => 'text',
                'value'           => $data['term_title'],
            ],
            'term_keywords' => [
                'order'           => 0,
                'type'            => 'text',
                'value'           => $data['term_keywords'],
            ],
            'term_description' => [
                'order'           => 0,
                'type'            => 'text',
                'value'           => $data['term_description'],
            ],
            'term_info' => [
                'order'           => 0,
                'type'            => 'text',
                'value'           => $data['term_info'],
            ],
            'html_2'      => [
                'order'           => 0,
                'type'            => 'html',
                'value'           => '</div><div class="col-12 col-md-4">',
            ],
            'term_parent' => [
                'order'           => 503,
                'type'            => 'select',
                'value'           => $data['term_parent'],
                'option'          => DcTermOption(['module'=>$module,'controll'=>$controll]),
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_status' => [
                'order'           => 0,
                'type'            => 'select',
                'value'           => DcEmpty($data['term_status'],'normal'),
                'option'          => model('common/Attr','loglic')->status(),
                'data-filter'     => true,
                'data-visible'    => false,
            ],
            'term_status_text' => [
                'order'           => 502,
                'data-title'      => lang('term_status'),
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_type' => [
                'order'           => 52,
                'type'            => 'select',
                'option'          => model('common/Attr','loglic')->categoryType(),
                'value'           => $data['term_type'],
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_order' => [
                'order'           => 504,
                'type'            => 'number',
                'value'           => intval($data['term_order']),
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-sortable'   => true,
            ],
            'term_count' => [
                'order'           => 505,
                'data-visible'    => true,
                'data-sortable'   => true,
                'data-width'      => 100,
            ],
            'term_action' => [
                'order'           => 506,
                'type'            => 'text',
                'value'           => $action,
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-value'      => '',
            ],
            'term_controll' => [
                'order'           => 507,
                'type'            => 'hidden',
                'value'           => $controll,
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'term_module' => [
                'order'           => 508,
                'type'            => 'text',
                'value'           => $module,
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-value'      => '',
                //'data-formatter'  => 'daicuo.admin.table.formatter',
            ],
            'html_3' => [
                'order'           => 0,
                'type'            => 'html',
                'value'           => '</div></div>',
            ]
        ];
        //动态扩展字段（可精确到操作名）
        $customs = model('common/Term','loglic')->metaList($module, $controll, $action);
        //合并所有字段
        if($customs){
            $fields = DcArrayPush($fields, DcFields($customs, $data), 'html_2');
        }
        //返回所有表单字段
        return $fields;
    }
}