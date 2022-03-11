<?php
namespace app\admin\loglic;

class Video
{
    public function fields()
    {
        $fields = [
            'video_in'   => [
                'type'   => 'switch',
                'value'  => config('common.video_in'),
            ],
            'video_size' => [
                'type'   => 'custom',
                'value'  => config('common.video_size'),
                'option' => [
                    '16by9'=>lang('video_size_16by9'),
                    '21by9'=>lang('video_size_21by9'),
                    '4by3'=>lang('video_size_4by3'),
                    '1by1'=>lang('video_size_1by1')
                ],
                'class_left'  => 'col-md-2',
                'class_right' => 'col-auto',
            ],
            'video_ai' => [
                'type'        => 'text',
                'value'       => config('common.video_ai'),
                'placeholder' => lang('video_ai_placeholder'),
            ],
            'video_frontUrl'  => [
                'type'        => 'text',
                'value'       => config('common.video_frontUrl'),
                'placeholder' => lang('video_frontUrl_placeholder'),
            ],
            'video_frontTime' => [
                'type'        => 'text',
                'value'       => config('common.video_frontTime'),
                'placeholder' => lang('video_frontTime_placeholder'),
            ],
            'video_endUrl' => [
                'type'        => 'text',
                'value'       => config('common.video_endUrl'),
                'placeholder' => lang('video_endUrl_placeholder'),
            ],
            'video_endTime' => [
                'type'        => 'text',
                'value'       => config('common.video_endTime'),
                'placeholder' => lang('video_endTime_placeholder'),
            ],
            'video_pause' => [
                'type'        => 'text',
                'value'       => config('common.video_pause'),
                'placeholder' => lang('video_pause_placeholder'),
            ],
            'video_buffer' => [
                'type'        => 'text',
                'value'       => config('common.video_buffer'),
                'placeholder' => lang('video_buffer_placeholder'),
            ],
            'video_advUnit' => [
                'type'        => 'hidden',
                'value'       => config('common.video_advUnit'),
                'placeholder' => lang('video_advUnit_placeholder'),
            ],
        ];
        //合并动态扩展字段
        if($customs = model('common/Config','loglic')->metaList('admin', 'video')){
            $fields = array_merge($fields,DcFields($customs, config('common')));
        }
        //返回所有表单字段
        return $fields;
    }
}