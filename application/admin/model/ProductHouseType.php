<?php

namespace app\admin\model;

use think\Model;

/**
 * 线上房间类型
 */
class ProductHouseType extends Model
{

    // 表名
    protected $name = 'house_type';
    protected $resultSetType = 'collection';
    protected $connection = 'product_config';

}
