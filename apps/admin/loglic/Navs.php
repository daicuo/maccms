<?php
namespace app\admin\loglic;

class Navs
{
    public function fields($data)
    {
        $module   = DcEmpty($data['navs_module'],'index');
        $controll = 'navs';
        $fields = [
            'html_1' => [
                'order'           => 0,
                'type'            => 'html',
                'value'           => '<div class="row"><div class="col-12 col-md-8">',
            ],
            'navs_id' => [
                'order'           => 1,
                'type'            => 'hidden',
                'value'           => $data['navs_id'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 60,
                'data-width-unit' => 'px',
                'data-sortable'   => true,
                'data-sort-name'  => 'navs_id',
                'data-order'      => 'asc',
            ],
            'navs_name' => [
                'order'           => 2,
                'type'            => 'text',
                'value'           => $data['navs_name'],
                'required'        => true,
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'navs_url' => [
                'order'           => 3,
                'type'            => 'text',
                'value'           => $data['navs_url'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-align'      => 'left',
            ],
            'navs_active' => [
                'order'           => 4,
                'type'            => 'text',
                'value'           => $data['navs_active'],
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'navs_ico' => [
                'order'           => 5,
                'type'            => 'text',
                'value'           => $data['navs_ico'],
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'navs_image' => [
                'order'           => 0,
                'type'            => 'image',
                'multiple'        => false,
                'value'           => $data['navs_image'],
            ],
            'navs_info' => [
                'order'           => 0,
                'type'            => 'text',
                'value'           => $data['navs_info'],
            ],
            'html_2'      => [
                'order'           => 0,
                'type'            => 'html',
                'value'           => '</div><div class="col-12 col-md-4">',
            ],
            'navs_parent' => [
                'order'           => 506,
                'type'            => 'select',
                'value'           => $data['navs_parent'],
                'option'          => DcTermOption(['module'=>$data['navs_module'],'controll'=>$controll]),
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'navs_status' => [
                'order'           => 0,
                'type'            => 'select',
                'value'           => DcEmpty($data['navs_status'],'normal'),
                'option'          => model('common/Attr','loglic')->status(),
                'data-filter'     => true,
                'data-visible'    => false,
            ],
            'navs_status_text' => [
                'order'           => 505,
                'data-title'      => lang('navs_status'),
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'navs_type' => [
                'order'           => 504,
                'type'            => 'select',
                'option'          => model('common/Attr','loglic')->navsType(),
                'value'           => $data['navs_type'],
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'navs_target' => [
                'order'           => 508,
                'type'            => 'select',
                'option'          => model('common/Attr','loglic')->target(),
                'value'           => $data['navs_target'],
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-value'      => '',
            ],
            'navs_order' => [
                'order'           => 507,
                'type'            => 'number',
                'value'           => intval($data['navs_order']),
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-sortable'   => true,
            ],
            'navs_controll' => [
                'order'           => 509,
                'type'            => 'hidden',
                'value'           => 'navs',
                'placeholder'     => '',
                'data-filter'     => false,
                'data-visible'    => true,
                'data-width'      => 100,
            ],
            'navs_module' => [
                'order'           => 510,
                'type'            => 'text',
                'value'           => $module,
                'data-filter'     => true,
                'data-visible'    => true,
                'data-width'      => 100,
                'data-value'      => '',
            ],
            'html_3' => [
                'order'           => 0,
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