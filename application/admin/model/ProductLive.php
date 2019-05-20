<?php

namespace app\admin\model;

use think\Model;
/**
 * 连接线上服务器的新闻表
 */
class ProductLive extends Model
{
    // 表名
    protected $name = 'live';
    protected $connection = 'product_config';
}
