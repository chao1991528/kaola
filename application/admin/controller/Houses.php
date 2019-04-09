<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 房源信息管理
 *
 * @icon fa fa-circle-o
 */
class Houses extends Backend
{

    /**
     * Houses模型对象
     * @var \app\admin\model\Houses
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Houses;
        $house_resource_districts_id_list = db('Australia_districts')->field('id,name,city_id')->where(['is_valid' => 1])->order('name asc')->select();
        $this->assign('districts_id_json', json_encode($house_resource_districts_id_list));
    }

    /*
     * 房源列表
     */

    public function index()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(["member", "district"])
                    ->where($where)
                    ->where('delete_time', 0)
                    ->count();
            $list = $this->model
                    ->with(["member", "district"])
                    ->where($where)
                    ->where('delete_time', 0)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {   
        $row = $this->model->get($ids);
        if (!$row){
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    if(empty($params['mem_id'])){
                        $this->error(__('会员ID不能为空', ''));
                    }
                    foreach ($params['house_config'] as $k => $v) {
                        if(empty($v)){
                            unset($params['house_config'][$k]);
                        }
                    }
                    if(empty($params['house_config'])){
                        $this->error(__('房屋配置不能为空', ''));
                    }
                    if(empty($params['house_resource_districts_id'])){
                        $this->error(__('房源区域不能为空', ''));
                    }
                    foreach ($params['house_tag'] as $k => $v) {
                        if(empty($v)){
                            unset($params['house_tag'][$k]);
                        }
                    }
                    if(count($params['house_tag']) > 4){
                        $this->error(__('房屋标签不能超过4个', ''));
                    }
                    if(empty($params['house_resource_longitude']) || empty($params['house_resource_latitude'])){
                        $this->error(__('请点击定位按钮获取定位信息', ''));
                    }
                    if(mb_strlen($params['content']) > 600){
                        $this->error(__('内容不能超过600个字符', ''));
                    }
                    $params['can_reside_time'] = strtotime($params['can_reside_time']);
                    $params['house_tag'] = ',' . implode(',', $params['house_tag']) . ',';
                    $params['house_config'] = ',' . implode(',', $params['house_config']) . ',';
                    $params['content'] = strip_tags(htmlspecialchars_decode($params['content']));
                    if($params['status'] == 1){
                        $params['check_time'] =time();
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        } else {
            $row->images = str_replace(['"', '[', ']', ' '], ['', '', '', ''], $row->images);
            $row->house_config = str_replace(['"', '[', ']', ' '], ['', '', '', ''], $row->house_config);
            $row->house_tag = str_replace(['"', '[', ']', ' '], ['', '', '', ''], $row->house_tag);
            $row->house_config = trim($row->house_config, ',');
            $row->house_tag = trim($row->house_tag, ',');
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                if (!in_array($row[$this->dataLimitField], $adminIds)) {
                    $this->error(__('You have no permission'));
                }
            }
            $status = $this->model->getStatusList();
            $resourceType = $this->model->getHouseResourceTypeList();
            $rentType = $this->model->getRentTypeList();
            $roomType = $this->model->getRoomTypeList();
            $houseType = db('house_type')->field('id,type_name')->order('sort desc')->where('is_valid', 1)->select();
            $isParlorResident = $this->model->getHasPersonList();
            $canKeepPat = $this->model->getCanKeepPatList();
            $haveSeparateBathroom = $this->model->getHaveSeparateBathroomList();
            $tenantGender = $this->model->getTenantGenderList();
            $citys = db('australia_cities')->field('id,name,name_zh')->order('is_hot desc')->where('is_valid', 1)->select();
            $houseConfig = db('house_config')->field('id,config_name')->order('sort desc')->where('is_valid', 1)->select();
            $houseTag = db('house_tag')->field('id,tag_name')->order('sort desc')->where('is_valid', 1)->select();
            $this->assign([
                'status' => $status,
                'resourceType' => $resourceType,
                'rentType' => $rentType,
                'roomType' => $roomType,
                'isParlorResident' => $isParlorResident,
                'canKeepPat' => $canKeepPat,
                'haveSeparateBathroom' => $haveSeparateBathroom,
                'tenantGender' => $tenantGender,
                'citys' => $citys,
                'houseType' => $houseType,
                'houseConfig' => $houseConfig,
                'houseTag' => $houseTag,
                'row' => $row
            ]);
            return $this->view->fetch();
        }       
    }
    
    public function del($ids="")
    {
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $count = $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();
            $count = 0;
            foreach ($list as  $v) {
                $count += $v->save(['delete_time'=> time()]);
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }
    
    /**
     * 列表页筛选联动用
     */
    public function getRegion()
    {
        $type = $this->request->get('type');
        if($type == 'city'){
            $list = db('australia_cities')->field('id as value,name,name_zh')->order('is_hot desc')->where('is_valid', 1)->select();
            foreach ($list as $k => $v){
                $list[$k]['name'] = $v['name'] . ' ' . $v['name_zh']; 
            }
        } else {
            $city_id = $this->request->get('city_id');
            $list = db('Australia_districts')->field('id as value,name')->where(['is_valid' => 1, 'city_id' => $city_id])->order('name asc')->select();
        }
        
        $this->success('', null, $list);
    }
    
    /**
     * 上传到正式服务器
     */
    public function uploadToProduct($ids)
    {
        if(empty($ids)){
            $this->error('id不能为空！');
        }
        $ids = explode(',', $ids);
        $data = [];
        foreach ($ids as $id) {
            $house = db('houses')->where('id', $id)->field('id,house_sn,add_time,update_time,delete_time,email_img', true)->find();
            if ($house) {
                if($house['status'] !== 1){
                    $this->error('上传失败：该房源信息尚未审核通过!',null);
                }
                $house['house_sn'] = 30 . date('YmdHis') . rand(1000, 9999);
                $house['add_time'] = time();
                $house['update_time'] = time();
                $data[] = $house;
            }
        }
        if(empty($data)){
            $this->error('上传失败：房源信息为空!');
        }
        model('ProductHouse')->saveAll($data);
        db('houses')->where('id', 'in', $ids)->update(['delete_time' => time()]);
        $this->success('上传成功!',null);       
    }
}
