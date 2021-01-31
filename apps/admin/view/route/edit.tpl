<div class="modal-content">
    <div class="modal-body">
    {:DcBuildForm([
        'name'     => 'route_edit',
        'class'    => 'bg-white px-2 py-2 form-edit was-validated',
        'action'   => DcUrl('admin/route/update', '', ''),
        'method'   => 'post',
        'submit'   => lang('submit'),
        'reset'    => lang('reset'),
        'close'    => lang('close'),
        'disabled' => false,
        'ajax'     => true,
        'callback' => '',
        'data'     => $data,
        'items'=>[
            [
                'type'=>'hidden',
                'name'=>'op_id',
                'value'=>$data['op_id'],
            ],
            [
                'type'=>'text',
                'name'=>'rule',
                'id'=>'rule',
                'title'=>lang('route_rule'),
                'placeholder'=>lang('route_rule_placeholder'),
                'tips'=>'',
                'value'=>$data['rule'],
                'readonly'=>false,
                'disabled'=>false,
                'required'=>true,
                'class'=>'row form-group',
                'class_left'=>'col-12',
                'class_right'=>'col-12',
                'class_right_control'=>'',
                'class_tips'=>'',
            ],
            [
                'type'=>'text',
                'name'=>'address',
                'id'=>'address',
                'title'=>lang('route_address'),
                'placeholder'=>lang('route_address_placeholder'),
                'tips'=>'',
                'value'=>$data['address'],
                'readonly'=>false,
                'disabled'=>false,
                'required'=>true,
                'class'=>'row form-group',
                'class_left'=>'col-12',
                'class_right'=>'col-12',
                'class_right_control'=>'',
                'class_tips'=>'',
            ],
            [
                'type'=>'select',
                'name'=>'method',
                'id'=>'method',
                'title'=>lang('route_method'),
                'placeholder'=>lang('route_method_placeholder'),
                'tips'=>'',
                'value'=>$data['method'],
                'option'=>['get'=>lang('route_method_option_0'),'post'=>lang('route_method_option_1'),'put'=>lang('route_method_option_2'),'delete'=>lang('route_method_option_3'),'*'=>lang('route_method_option_4')],
                'readonly'=>false,
                'disabled'=>false,
                'required'=>false,
                'multiple'=>false,
                'class'=>'row form-group',
                'class_left'=>'col-12',
                'class_right'=>'col-12',
                'class_right_control'=>'',
                'class_tips'=>'',
            ],
            [
                'type'=>'select',
                'name'=>'op_status',
                'id'=>'op_status',
                'title'=>lang('status'),
                'placeholder'=>lang('status_placeholder'),
                'tips'=>'',
                'value'=>$data['op_status'],
                'option'=>['normal'=>lang('normal'),'hidden'=>lang('hidden')],
                'readonly'=>false,
                'disabled'=>false,
                'required'=>false,
                'multiple'=>false,
                'class'=>'row form-group',
                'class_left'=>'col-12',
                'class_right'=>'col-12',
                'class_right_control'=>'',
                'class_tips'=>'',
            ],
            [
                'type'=>'json',
                'name'=>'option',
                'id'=>'option',
                'title'=>lang('route_option'),
                'placeholder'=>lang('route_option_placeholder'),
                'tips'=>'',
                'value'=>$data['option'],
                'rows'=>6,
                'readonly'=>false,
                'disabled'=>false,
                'required'=>false,
                'class'=>'row form-group',
                'class_left'=>'col-12',
                'class_right'=>'col-12',
                'class_right_control'=>'',
                'class_tips'=>'',
            ],
            [
                'type'=>'json',
                'name'=>'pattern',
                'id'=>'pattern',
                'title'=>lang('route_pattern'),
                'placeholder'=>lang('route_pattern_placeholder'),
                'tips'=>'',
                'value'=>$data['pattern'],
                'rows'=>6,
                'readonly'=>false,
                'disabled'=>false,
                'required'=>false,
                'class'=>'row form-group',
                'class_left'=>'col-12',
                'class_right'=>'col-12',
                'class_right_control'=>'',
                'class_tips'=>'',
            ],
        ]
    ])}
    </div>
</div>