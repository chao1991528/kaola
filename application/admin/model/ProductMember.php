<?php

namespace app\admin\model;

use think\Model;
/**
 * 连接线上服务器的会员表
 */
class ProductMember extends Model
{
    // 表名
    protected $name = 'kl_member';
    protected $connection = 'product_config';
    protected $resultSetType = 'collection';
}
