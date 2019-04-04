<?php

namespace app\admin\model;

use think\Model;
/**
 * 这个模型纯粹只是为了上传到生成服务器的时候切换数据库
 */
class ProductHouse extends Model
{
    // 表名
    protected $name = 'kl_houses';
    protected $connection = 'product_config';
}
