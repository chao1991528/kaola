<?php

namespace app\admin\model;

use think\Model;
/**
 * 线上服务器的后台管理员
 */
class ProductUser extends Model
{
    // 表名
    protected $name = 'user';
    protected $connection = 'product_config';
}
