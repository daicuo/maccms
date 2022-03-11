<?php
// 应用钩子(只在应用范围内有效)
return [
    'admin_index_header'  => [
        'app\\admin\\behavior\\Hook',
    ],
    'user_login_before'  => [
        'app\\admin\\behavior\\Hook',
    ],
];