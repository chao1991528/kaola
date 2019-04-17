<?php

namespace app\admin\model;

use think\Model;
/**
 * 连接线上服务器的会员表
 */
class ProductCoupon extends Model
{
    // 表名
    protected $name = 'house_coupon';
    protected $connection = 'product_config';
    protected $resultSetType = 'collection';
}
