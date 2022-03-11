<?php
namespace app\maccms\model;

class Api{

    //获取最后一集播放地址
    protected function play_last($tid=0, $id=0, $array=[])
    {
        $i = 1;
        foreach($array as $key=>$value){
            if($i == 1){
                $maxKey = $key;
                $max = 1;
            }
            $i++;
            if( count($value) > $max){
                $max = count($value);
                $maxKey = $key;
            }
        }
        return ['tid'=>intval($tid), 'id'=>$id, 'ep'=>$max, 'from'=>$maxKey];
        //return DcUrl('maccms/api/play', ['tid'=>intval($tid), 'id'=>$id, 'ep'=>$max, 'from'=>$maxKey]);
    }
    
    //字典转换
    protected function data_fields($array=[])
    {
        //$array = ['a'=>1,'b'=>2,'c'=>3];
        $fields = array_flip($this->fields);
        $data = array();
        foreach($array as $key=>$value){
            if( isset($fields[$key]) ){
                $data[$fields[$key]] = $value;
            }
        }
        //安全过滤
        foreach($data as $key=>$value){
            if(!is_array($value)){
                $data[$key] = DcHtml($value);
            }
        }
        return $data;
    }
    
    //格式化分隔符
    protected function data_explode($string='')
    {
        if(!$string){
            return '';
        }
        $string = str_replace(array('/','，','|','、',',,,',',,',';'), ',', $string);
        return explode(',', $string);
    }
}
