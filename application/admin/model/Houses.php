<?php

namespace app\admin\model;

use think\Model;

class Houses extends Model
{

    // 表名
    protected $name = 'houses';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    // 追加属性
    protected $append = [
        'can_reside_time_text',
        'check_time_text',
        'add_time_text',
        'rented_time_text',
        'status_text',
        'resource_type_text',
        'rent_type_text',
        'room_type_text',
        'house_type_text',
        'has_person_text',
        'can_keep_pat_text',
        'have_separate_bathroom_text',
        'tenant_gender_text',
    ];

    public function getCanResideTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['can_reside_time']) ? $data['can_reside_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getCheckTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['check_time']) ? $data['check_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getAddTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['add_time']) ? $data['add_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getRentedTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['rented_time']) ? $data['rented_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setCanResideTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setCheckTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setAddTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setRentedTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1'), '2' => __('Status 2'), '3' => __('Status 3'), '4' => __('Status 4'), '5' => __('Status 5')];
    }
    
    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    
    public function getHouseResourceTypeList()
    {
        return ['1' => __('ResourceType 1'), '2' => __('ResourceType 2')];
    }
    
    public function getResourceTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['house_resource_type']) ? $data['house_resource_type'] : '');
        $list = $this->getHouseResourceTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    public function getRentTypeList()
    {
        return ['1' => __('RentType 1'), '2' => __('RentType 2')];
    }
    
    public function getRentTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['house_rent_type']) ? $data['house_rent_type'] : '');
        $list = $this->getRentTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    
    public function getRoomTypeList()
    {
        return ['1' => __('RoomType 1'), '2' => __('RoomType 2'), '3' => __('RoomType 3'), '4' => __('RoomType 4')];
    }
    
    public function getRoomTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['house_room_type']) ? $data['house_room_type'] : '');
        $list = $this->getRoomTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    
    public function getHouseTypeList()
    {
        return ['1' => __('RoomType 1'), '2' => __('RoomType 2'), '3' => __('RoomType 3'), '4' => __('RoomType 4')];
    }
    
    public function getHouseTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['house_type_id']) ? $data['house_type_id'] : '');
        $list = $this->getHouseTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    
    public function getHasPersonList()
    {
        return ['0' => __('IsParlorResident 0'), '1' => __('IsParlorResident 1')];
    }
    
    public function getHasPersonTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['is_parlor_resident']) ? $data['is_parlor_resident'] : '');
        $list = $this->getHasPersonList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getCanKeepPatList()
    {
        return ['0' => __('CanKeepPat 0'), '1' => __('CanKeepPat 1')];
    }
    
    public function getCanKeepPatTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['can_keep_pat']) ? $data['can_keep_pat'] : '');
        $list = $this->getCanKeepPatList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    public function getHaveSeparateBathroomList()
    {
        return ['0' => __('Have_separate_bathroom 0'), '1' => __('Have_separate_bathroom 1')];
    }
    
    public function getHaveSeparateBathroomTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['have_separate_bathroom']) ? $data['have_separate_bathroom'] : '');
        $list = $this->getHaveSeparateBathroomList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    
    public function getTenantGenderList()
    {
        return ['1' => __('Tenant_gender 1'), '2' => __('Tenant_gender 2'), '3' => __('Tenant_gender 3')];
    }
    
    public function getTenantGenderTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['tenant_gender']) ? $data['tenant_gender'] : '');
        $list = $this->getTenantGenderList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    
    public function member()
    {
        return $this->belongsTo('Member', 'mem_id', 'id', 'member', 'LEFT')->setEagerlyType(0);
    }
    
    public function district()
    {
        return $this->belongsTo('District', 'house_resource_districts_id', 'id', 'district', 'LEFT')->setEagerlyType(0);
    }
    
    public function coupon()
    {
        return $this->belongsTo('District', 'house_resource_districts_id', 'id', 'district', 'LEFT')->setEagerlyType(0);
    }
}
