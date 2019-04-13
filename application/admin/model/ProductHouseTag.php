<?php

namespace app\admin\model;

use think\Model;
/**
 * 线上房屋标签
 */
class ProductHouseTag extends Model
{
    // 表名
    protected $name = 'house_tag';
    protected $connection = 'product_config';
}