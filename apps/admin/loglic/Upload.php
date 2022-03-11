<?php
namespace app\admin\loglic;

class Upload
{
    public function fields()
    {
        $fields = [
            'upload_path' => [
                'type'        => 'text',
                'value'       => config('common.upload_path'),
                'required'    => true,
                'placeholder' => lang('upload_path_placeholder'),
            ],
            'upload_save_rule'  => [
                'type'        => 'text',
                'value'       => DcEmpty(config('common.upload_save_rule'),'date'),
                'placeholder' => lang('upload_save_rule_placeholder'),
            ],
            'upload_max_size'  => [
                'type'        => 'text',
                'value'       => config('common.upload_max_size'),
                'placeholder' => lang('upload_max_size_placeholder'),
            ],
            'upload_file_ext'  => [
                'type'        => 'text',
                'value'       => config('common.upload_file_ext'),
                'placeholder' => lang('upload_file_ext_placeholder'),
            ],
            'upload_mime_type' => [
                'type'        => 'text',
                'value'       => config('common.upload_mime_type'),
                'placeholder' => lang('upload_mime_type_placeholder'),
            ],
            'upload_referer' => [
                'type'        => 'text',
                'value'       => config('common.upload_referer'),
                'placeholder' => lang('upload_referer_placeholder'),
            ],
            'upload_host' => [
                'type'        => 'text',
                'value'       => config('common.upload_host'),
                'placeholder' => lang('upload_host_placeholder'),
            ],
            'upload_cdn' => [
                'type'        => 'text',
                'value'       => config('common.upload_cdn'),
                'placeholder' => lang('upload_cdn_placeholder'),
            ],
        ];
        //合并动态扩展字段
        if($customs = model('common/Config','loglic')->metaList('admin', 'upload')){
            $fields = array_merge($fields,DcFields($customs, config('common')));
        }
        return $fields;
    }
}