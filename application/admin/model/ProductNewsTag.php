<?php

namespace app\admin\model;

use think\Model;
/**
 * 连接线上服务器的新闻标签表
 */
class ProductNewsTag extends Model
{
    // 表名
    protected $name = 'news_tag';
    protected $connection = 'product_config';
    protected $resultSetType = 'collection';
}
