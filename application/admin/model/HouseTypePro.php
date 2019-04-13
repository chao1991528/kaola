<?php

namespace app\admin\model;

use think\Model;

/**
 * 线上房间类型
 */
class HouseTypePro extends Model
{

    // 表名
    protected $name = 'house_type';
    protected $resultSetType = 'collection';
    protected $connection = [
        // 数据库类型
        'type' => 'mysql',
        // 数据库连接DSN配置
        'dsn' => '',
        // 服务器地址
        'hostname' => '127.0.0.1',
        // 数据库名
        'database' => 'kaola_news2',
        // 数据库用户名
        'username' => 'root',
        // 数据库密码
        'password' => 'root',
        // 数据库连接端口
        'hostport' => '',
        // 数据库连接参数
        'params' => [],
        // 数据库编码默认采用utf8
        'charset' => 'utf8',
        // 数据库表前缀
        'prefix' => 'kl_'
    ];

}
