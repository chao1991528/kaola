<?php

namespace app\admin\validate;

use think\Validate;

class News extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'news_title|新闻标题'  => 'require',
        'type_id|新闻类型'  => 'require',
        'category_id|新闻分类'  => 'require',
        'source_id|新闻来源'  => 'require',
        'layout_id|新闻布局'  => 'require',
//        'title_tag_id|标题标签'  => 'require',
        'news_url|原文链接' => 'require',
//        'search_key|搜索标签' => 'require',
//        'remark|新闻摘要' => 'require',
//        'is_top|是否置顶' => 'require|checkTopTime',
//        'is_publish|是否定时发布' => 'require|checkPublishTime', 
        'content|新闻内容'  => 'require',
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['news_title', 'type_id', 'category_id', 'source_id', 'layout_id', 'title_tag_id', 'news_url'],
        'edit' => ['type_id', 'category_id', 'layout_id'],
    ];

    // 自定义验证规则
    protected function checkTopTime($value,$rule,$data)
    {
        return $data['is_top'] && empty($data['top_end_date']) ? '置顶时间不能为空！' : true;
    }

    // 自定义验证规则
    protected function checkPublishTime($value,$rule,$data)
    {
        return $data['is_publish'] && empty($data['publish_time']) ? '发布时间不能为空！' : true;
    }
    
}
