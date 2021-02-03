<?php
return [
    'config' => [
        'session_name' => 'PHPSESSION',
        'session_path' => '/',
        'session_id_length' => 32,
        //sessionid类型 1数字+字母 2纯数字
        'session_id_type' => 1,
        //redis的key = session_storage_prefix + sessionid
        'session_storage_prefix' => 'yd_session_',
        'session_expire' => 60,
    ],
];
