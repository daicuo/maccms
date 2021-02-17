<?php
return array (
  'cache' => 
  array (
    'type' => 'Redis',
    'prefix' => '',
    'path' => 'datas/cache',
    'db' => 'datas/db/#cache.s3db',
    'host' => '127.0.0.1',
    'port' => '6379',
    'expire' => 0,
    'expire_detail' => 120,
    'expire_item' => 120,
  ),
);
?>