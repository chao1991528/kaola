<?php

namespace app\admin\model;

use think\Model;
/**
 * 房屋配置
 */
class HouseConfigPro extends Model
{
    // 表名
    protected $name = 'house_config';
//    protected $connection = 'mysql://kaola_news:hrKx2hrmaGNRSXrf@47.74.65.58:3306/kaola_news#utf8';
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
        $this->connection = [
            // 数据库类型
            'type'        => 'mysql',
            // 数据库连接DSN配置
            'dsn'         => '',
            // 服务器地址
            'hostname'    => '127.0.0.1',
            // 数据库名
            'database'    => 'kaola_news2',
            // 数据库用户名
            'username'    => 'root',
            // 数据库密码
            'password'    => 'root',
            // 数据库连接端口
            'hostport'    => '',
            // 数据库连接参数
            'params'      => [],
            // 数据库编码默认采用utf8
            'charset'     => 'utf8',
            // 数据库表前缀
            'prefix'      => 'kl_'
        ];
    }
}