<?php

namespace app\admin\model;

use think\Model;
/**
 * 连接线上服务器的新闻分类表
 */
class ProductNewsCategory extends Model
{
    // 表名
    protected $name = 'news_category';
    protected $connection = 'product_config';
    protected $resultSetType = 'collection';
}
