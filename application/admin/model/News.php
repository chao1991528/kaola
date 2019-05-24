<?php

namespace app\admin\model;

use think\Model;

class News extends Model
{
    // 表名
    protected $name = 'news';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [
        'add_time_text',
        'publish_time',
        'is_applet_text',
        'is_top_text',
        'is_hot_text',
        'is_valid_text',
        'is_recommend_text',
        'declare_text',
        'status_text'
    ];

    public function getIsAppletTextAttr($value, $data)
    {
        $arr = $this->getIsAppLetList();
        return $arr[$data['is_applet']];
    }

    public function getIsTopTextAttr($value, $data)
    {
        $arr = $this->getIsTopList();
        return $arr[$data['is_top']];
    }

    public function getIsHotTextAttr($value, $data)
    {
        $arr = $this->getIsHotList();
        return $arr[$data['is_hot']];
    }

    public function getIsValidTextAttr($value, $data)
    {
        $arr = $this->getIsValidList();
        return $arr[$data['is_valid']];
    }

    public function getIsRecommendTextAttr($value, $data)
    {
        $arr = $this->getIsRecommendList();
        return $arr[$data['is_recommend']];
    }

    public function getDeclareTextAttr($value, $data)
    {
        $arr = $this->getDeclareList();
        return $data['declare_id'] ? $arr[$data['declare_id']] : '-';
    }
    public function getStatusTextAttr($value, $data)
    {
        $arr = $this->getStatusList();
        return $arr[$data['status']];
    }

    public function getAddTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['add_time']) ? $data['add_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getPublishTimeAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['publish_time']) ? $data['publish_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setAddTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setPublishTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function getTopEndDateAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['top_end_date']) ? $data['top_end_date'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setTopEndDateAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    public function getIsAppLetList()
    {
        return ['0' => __('Is_applet 0'),'1' => __('Is_applet 1')];
    }

    public function getIsTopList()
    {
        return ['0' => __('Is_top 0'),'1' => __('Is_top 1')];
    }

    public function getIsHotList()
    {
        return ['0' => __('Is_hot 0'),'1' => __('Is_hot 1')];
    }

    public function getIsValidList()
    {
        return ['0' => __('Is_valid 0'),'1' => __('Is_valid 1')];
    }

    public function getIsRecommendList()
    {
        return ['0' => __('Is_recommend 0'),'1' => __('Is_recommend 1')];
    }

    public function getDeclareList()
    {
        return ['1' => __('Declare_id 1'),'2' => __('Declare_id 2') , '3' => __('Declare_id 3')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'),'1' => __('Status 1')];
    }

    public function category(){
        return $this->belongsTo('NewsCategory', 'category_id')->setEagerlyType(0);
    }

    public function type(){
        return $this->belongsTo('NewsType', 'type_id', 'id', '', 'left')->setEagerlyType(0);
    }

    public function source(){
        return $this->belongsTo('NewsSource', 'source_id', 'id', '', 'left')->setEagerlyType(0);
    }

    public function layout(){
        return $this->belongsTo('NewsLayout', 'layout_id')->setEagerlyType(0);
    }
    
    public function user(){
        return $this->belongsTo('User', 'add_uid', 'id', '', 'left')->setEagerlyType(0);
    }
}