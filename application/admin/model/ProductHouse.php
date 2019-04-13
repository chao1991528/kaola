<?php

namespace app\admin\model;

use think\Model;
/**
 * 连接线上服务器的房源表
 */
class ProductHouse extends Model
{
    // 表名
    protected $name = 'houses';
    protected $connection = 'product_config';
}
