<?php

namespace app\admin\model;

use think\Model;
/**
 * 房屋配置
 */
class ProductHouseConfig extends Model
{
    // 表名
    protected $name = 'house_config';
    protected $connection = 'product_config';
}