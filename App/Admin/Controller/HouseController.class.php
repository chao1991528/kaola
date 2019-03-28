<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;

/**
 * @author Gary <lizhiyong2204@sina.com>
 * @date 2015年12月14日
 * @todu
 */
class HouseController extends BaseController {
	
	public function __construct(){
		parent::__construct();
		
		$this->assign('house_resource_type_list', C('HOUSE_RESOURCE_TYPE'));
		$this->assign('house_rent_type_list', C('HOUSE_RENT_TYPE'));
		$this->assign('house_room_type_list', C('HOUSE_ROOM_TYPE'));
		$this->assign('is_parlor_resident_list', C('IS_PARLOR_RESIDENT'));
		$this->assign('can_keep_pat_list', C('CAN_KEEP_PAT'));
		$this->assign('have_separate_bathroom_list', C('HAVE_SEPARATE_BATHROOM'));
		$this->assign('tenant_gender', C('TENANT_GENDER'));
		$this->assign('status_list', C('HOUSE_STATUS'));
                $house_resource_districts_id_list = M('Australia_districts')->field('id,name,city_id')->where(['is_valid' => 1])->order('name asc')->select();
                $this->assign('districts_id_json', json_encode($house_resource_districts_id_list));
                $house_type_list = M('house_type')->field('id,type_name')->select();
                $this->assign('house_type_list', $house_type_list);
                $this->assign('city_list', M('AustraliaCities')->field('id,name,name_zh')->order('name asc')->select());
                $this->assign('number_list', [0,1,2,3,4,5,6,7,8,9,10]);
	}

    public function index(){
    	$param = array();
    	$param['title'] = I('title', '');
    	$param['house_resource_type'] = I('house_resource_type', '');
    	$param['house_rent_type'] = I('house_rent_type', '');
    	$param['house_room_type'] = I('house_room_type', '');
    	$param['house_resource_city_id'] = I('house_resource_city_id', '');
    	$param['house_resource_districts_id'] = I('house_resource_districts_id', '');
    	$param['status'] = I('status', '-1');
    	
    	$where = "is_delete=0";
    	if (!empty($param['title'])){
			$where .= " AND title like '%{$param['title']}%'";
		}
    	if (!empty($param['house_resource_type'])){
    		$where .= " AND house_resource_type = '{$param['house_resource_type']}'";
		}
    	if (!empty($param['house_rent_type_list'])){
    		$where .= " AND house_rent_type_list = '{$param['house_rent_type_list']}'";
		}
    	if (!empty($param['house_room_type_list'])){
    		$where .= " AND house_room_type_list = '{$param['house_room_type_list']}'";
		}
    	if (!empty($param['house_resource_city_id'])){
                $districts = M('Australia_districts')->field('id,name')->where(['house_resource_city_id'=>$param['house_resource_city_id']])->order('name asc')->select();
                $this->assign('districts',$districts);
    		$where .= " AND house_resource_city_id = '{$param['house_resource_city_id']}'";
        }
        if (!empty($param['house_resource_districts_id'])){
    		$where .= " AND house_resource_districts_id = '{$param['house_resource_districts_id']}'";
	}
    	if (-1 != $param['status']){
    		$where .= " AND status = '{$param['status']}'";
        }
                
    	$count = D('Houses')->where($where)->count();
 	 
    	$Page= new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show = $Page->show();// 分页显示输出
    	$list = D('Houses')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        
    	$this->assign('page',$show);
    	
        $this->assign('status', $param['status']);
    	$this->assign('total', $count);
    	$this->assign('list', $list);
    	$this->display(); 
    }

    public function add(){
    	if(IS_POST){
                $data = I('post.'); 
                if(empty($data['title'])){
                    $_return_data['info'] = '标题必填!';
                    echo json_encode($_return_data);
                    exit;
                }
    		if(empty($data['house_rent_type'])){
    			$_return_data['info'] = '请选择出租类型!';
    			echo json_encode($_return_data);
    			exit;
    		}
                if(empty($data['mem_id'])){
    			$_return_data['info'] = '会员ID必填!';
    			echo json_encode($_return_data);
    			exit;
    		}
    		if(empty($data['house_config'])){
    			$_return_data['info'] = '请勾选房屋配置!';
    			echo json_encode($_return_data);
    			exit;
    		} else {
                    $data['house_config'] = ',' . implode(',', $data['house_config']) . ',';
                }
                if(empty($data['house_type_id'])){
                    $_return_data['info'] = '户型必须选择!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['house_resource_city_id'])){
                    $_return_data['info'] = '所属城市必须选择!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['house_resource_districts_id'])){
                    $_return_data['info'] = '房源区域必须选择!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['house_resource_address'])){
                    $_return_data['info'] = '房源位置必填!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['house_resource_longitude']) || empty($data['house_resource_latitude'])){
                    $_return_data['info'] = '请点击定位按钮获取定位信息!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['content'])){
                    $_return_data['info'] = '内容不能为空!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['contact_person'])){
                    $_return_data['info'] = '联系人必填!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['mobile'])){
                    $_return_data['info'] = '手机号必填!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(mb_strlen($data['content']) > 600){
                    $_return_data['info'] = '内容不能超过600个字!';
                    echo json_encode($_return_data);
                    exit;
                }
    		if(empty($data['house_tag'])){                   
    			$_return_data['info'] = '请勾选房屋标签!';
    			echo json_encode($_return_data);
    			exit;
    		} else {
                    if(count($data['house_tag']) > 4){
                        $_return_data['info'] = '房屋标签不能超过4个!';
    			echo json_encode($_return_data);
                        exit;
                    }
                    $data['house_tag'] = ',' . implode(',', $data['house_tag']) . ',';
                }
                if($data['images']){
                    $data['image_thumbs_200'] = str_replace('.', '_thumb_200.' ,$data['images']);
                    $data['image_thumbs_750'] = str_replace('.', '_thumb_750.' ,$data['images']);
                }else{
                    $_return_data['info'] = '房屋图片必须上传!';
                    echo json_encode($_return_data);
                    exit;
                }                  
                if($data['can_reside_time']){
                    $data['can_reside_time'] = strtotime($data['can_reside_time']);
                }
    		if ($data['house_rent_type'] == 2) {
                   $data['house_room_type'] = 1;
                   $data['is_parlor_resident'] = 0;
                   $data['have_separate_bathroom'] = 0;
                   $data['tenant_gender'] = 1;
    		}
                $data['status'] = 1;
                $data['house_sn'] = '20' . date('Ymdhis',time()).rand(1000,9000);
                $data['check_time'] = $data['add_time'] = time();
    		$_return_data = array('info'=>'添加失败!','status'=>'n');
    
    		$flag = false;
    		$flag = M('Houses')->add($data);
    		if($flag)
    			$_return_data = array('info'=>'添加成功!','status'=>'y');
    		 
    		echo json_encode($_return_data);
    		exit;
    
    	}
        $coupons = M('House_coupon')->where(array('is_valid' => 1, 'delete_time' => 0))->field('id,title')->select();
        $tags = M('House_tag')->where(array('is_valid' => 1, 'delete_time' => 0))->field('id,tag_name')->select();
        $configs = M('House_config')->where(array('is_valid' => 1, 'delete_time' => 0))->field('id,config_name')->select();
        $this->assign('coupons', $coupons);
        $this->assign('tags', $tags);
        $this->assign('configs', $configs);
    	$this->display();
    }
    
    public function edit(){
    	if (IS_POST){
    		$data = array();
    		$data = I('post.');
    
    		$_return_data = array('info'=>'保存失败!','status'=>'n');
                if(empty($data['title'])){
                    $_return_data['info'] = '标题必填!';
                    echo json_encode($_return_data);
                    exit;
                }
    		if(empty($data['house_rent_type'])){
    			$_return_data['info'] = '请选择出租类型!';
    			echo json_encode($_return_data);
    			exit;
    		}

                if(!empty($data['house_tag'])){
                    if(count($data['house_tag']) > 4){
                        $_return_data['info'] = '房屋标签不能超过4个!';
    			echo json_encode($_return_data);
                        exit;
                    }
                    $data['house_tag'] = ',' . implode(',', $data['house_tag']) . ',';
                } else {
                    $_return_data['info'] = '请勾选房屋标签!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(!empty($data['house_config'])){
                    $data['house_config'] = ',' . implode(',', $data['house_config']) . ',';
                }else {
                    $_return_data['info'] = '请勾选房屋配置!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['house_type_id'])){
                    $_return_data['info'] = '户型必须选择!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['house_resource_districts_id'])){
                    $_return_data['info'] = '房源区域必须选择!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['house_resource_address'])){
                    $_return_data['info'] = '房源位置必填!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['house_resource_longitude']) || empty($data['house_resource_latitude'])){
                    $_return_data['info'] = '请点击定位按钮获取定位信息!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['content'])){
                    $_return_data['info'] = '内容不能为空!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['contact_person'])){
                    $_return_data['info'] = '联系人必填!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(empty($data['mobile'])){
                    $_return_data['info'] = '手机号必填!';
                    echo json_encode($_return_data);
                    exit;
                }
                if(!empty($data['can_reside_time'])){
                    $data['can_reside_time'] = strtotime($data['can_reside_time']);
                }
                if(!empty($data['images'])){
//                    $data['image_thumbs_200'] = str_replace('.', '_thumb_200.' ,$data['images']);
//                    $data['image_thumbs_750'] = str_replace('.', '_thumb_750.' ,$data['images']);
                } else {
                    $_return_data['info'] = '房屋图片必须上传!';
                    echo json_encode($_return_data);
                    exit;
                }
    		if(mb_strlen($data['content']) > 600){
                    $_return_data['info'] = '内容不能超过600个字!';
                    echo json_encode($_return_data);
                    exit;
                }
    		$flag = false;
    		$flag = M('Houses')->where("id = {$data['id']}")->save($data);
    		if($flag !== false){
    			$_return_data = array('info'=>'保存成功!','status'=>'y');
    		}
    			
    		echo json_encode($_return_data);
    		exit;
    	}

    	$id = I('id');
    	if(empty($id)){
    		$this->error('参数错误!',U('House/index'));
    		exit;
    	}
    
    	$info = M('Houses')->where(array('id' => $id))->find();
        if($info){
            $house_config_new = explode(',', $info['house_config_new']);
            $configs = M('House_config')->where(array('is_valid' => 1, 'delete_time' => 0))->field('id,config_name')->select();
            $info['house_config'] = '';
            foreach ($house_config_new as $v) {
                foreach ($configs as $v2) {
                    if($v2['config_name'] == $v){
                        $info['house_config'] .= $v2['id'] . ',';
                    }
                }
            }
            $info['house_tag'] = explode(',', trim($info['house_tag'], ','));
            $info['house_config'] = explode(',', trim($info['house_config'], ','));
            $info['images_list'] = $info['images'] ? explode(',', trim($info['images'])) : array();
            $info['can_reside_time'] = $info['can_reside_time'] ? date('Y-m-d', $info['can_reside_time']) : '';
        } else {
            $this->error('房屋信息不存在！');
        }
        $this->assign('info',$info);
        $coupons = M('House_coupon')->where(array('is_valid' => 1, 'delete_time' => 0))->field('id,title')->select();
        $tags = M('House_tag')->where(array('is_valid' => 1, 'delete_time' => 0))->field('id,tag_name')->select();
        $configs = M('House_config')->where(array('is_valid' => 1, 'delete_time' => 0))->field('id,config_name')->select();
        $this->assign('images_list', $info['images_list']);
        $this->assign('coupons', $coupons);
        $this->assign('tags', $tags);
        $this->assign('configs', $configs);
        $this->display();
    }
    
    public function ajax_del(){
    	$data = array();
    	$data['id'] = I('id');
    	$data['is_delete'] = 1;
    	$data['delete_time'] = time();
    	D('Houses')->where("id = {$data['id']}")->save($data);
    	
    	$_return_data = array('info'=>'删除成功','status'=>'y');
    	
    	echo json_encode($_return_data);
    	exit;
    }
    
    public function ajax_change_status(){
        $data = array();
        $data['id'] = I('id');
        $data['status'] = I('status', 0);
        $data['check_time'] = time();
        M('Houses')->where("id = {$data['id']}")->save($data);
        
        $_return_data = array('info'=>'','status'=>'y');
        
        echo json_encode($_return_data);
        exit;
    }
    
    public function uploadToProduct(){
        $data = array();
    	$data['id'] = I('id');
    	$data['is_delete'] = 1;
    	$data['delete_time'] = time();
    	$insertData = D('Houses')->where("id = {$data['id']}")->field('email_img,house_config_new,id,mem_id', true)->select();
        $Houser = M('houses','kl_','mysql://root:root@localhost/kaola_news2#utf8');
        $Houser->add($insertData[0]);   	
    	$_return_data = array('info'=>'操作成功','status'=>'y');
    	
    	echo json_encode($_return_data);
    	exit;
    }
}