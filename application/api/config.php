<?php
use think\Env;
//配置文件
return [
    'exception_handle'        => '\\app\\api\\library\\ExceptionHandle',
    'api_secret_key'          => 'FTQAZ1Aa',
    'image_domain'            => Env::get('app.image_domain', 'pool.kaolanews.com')
];
