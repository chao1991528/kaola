<?php

namespace app\admin\model;

use think\Model;

class Live extends Model
{

    // 表名
    protected $name = 'live';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    // 追加属性
    protected $append = [
        'delete_time_text',
        'add_time_text',
        'update_time_text',
        'status_text',
        'is_top_text',
        'is_ensure_text',
    ];

    protected static function init()
    {
        self::event('before_update', function ($live) {
            $tag_ids = implode(',', $live->tag_ids);
            $live->tag_ids = $tag_ids ? ',' . $tag_ids . ',' : '';
            $live->top_end_date = $live->top_end_date ? strtotime($live->top_end_date) : 0;
            
            $imageArr = explode(',', $live->images);
            $image200 = $image750 = '';
            foreach ($imageArr as $image) {
                $position = strrpos($image, '.'); 
                $image200 = $image200 ? $image200 . ',' . substr_replace($image, '_thumb_200', $position, 0) : substr_replace($image, '_thumb_200', $position, 0);
                $image750 = $image750 ? $image750 . ',' . substr_replace($image, '_thumb_750', $position, 0) : substr_replace($image, '_thumb_750', $position, 0);
            }
            $live->image_thumbs_200 = $image200;
            $live->image_thumbs_750 = $image750;
        });
    }

    public function getDeleteTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['delete_time']) ? $data['delete_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getAddTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['add_time']) ? $data['add_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getUpdateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['update_time']) ? $data['update_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setDeleteTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setAddTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setUpdateTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    public function category()
    {
        return $this->belongsTo('LiveCategory', 'category_id', 'id', '', 'left')->setEagerlyType(0);
    }

    public function city()
    {
        return $this->belongsTo('City', 'city_id', 'id', '', 'left')->setEagerlyType(0);
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1'), '2' => __('Status 2')];
    }

    public function getStatusTextAttr($value, $data)
    {
        $arr = $this->getStatusList();
        return $arr[$data['status']];
    }

    public function getIsTopList()
    {
        return ['0' => __('Is_top 0'), '1' => __('Is_top 1')];
    }

    public function getIsTopTextAttr($value, $data)
    {
        $arr = $this->getIsTopList();
        return $arr[$data['is_top']];
    }

    public function getIsEnsureList()
    {
        return ['0' => __('Is_ensure 0'), '1' => __('Is_ensure 1')];
    }

    public function getIsEnsureTextAttr($value, $data)
    {
        $arr = $this->getIsEnsureList();
        return $arr[$data['is_ensure']];
    }

}
