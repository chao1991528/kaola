<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;

/**
 * @author Gary <lizhiyong2204@sina.com>
 * @date 2015年12月14日
 * @todu
 */
class HouseComplaintController extends BaseController {
	
	public function __construct(){
		parent::__construct();
		$this->assign('house_complaint_type_list', C('HOUSE_COMPLAINT_TYPE'));
		$this->assign('house_complaint_status_list', C('HOUSE_COMPLAINT_STATUS'));
                $this->assign('house_resource_type_list', C('HOUSE_RESOURCE_TYPE'));
		$this->assign('house_rent_type_list', C('HOUSE_RENT_TYPE'));
		$this->assign('house_room_type_list', C('HOUSE_ROOM_TYPE'));
		$this->assign('is_parlor_resident_list', C('IS_PARLOR_RESIDENT'));
		$this->assign('can_keep_pat_list', C('CAN_KEEP_PAT'));
		$this->assign('have_separate_bathroom_list', C('HAVE_SEPARATE_BATHROOM'));
		$this->assign('tenant_gender', C('TENANT_GENDER'));
		$this->assign('status_list', C('HOUSE_STATUS'));
	}

    public function index(){
    	$param = array();
    	$param['content'] = I('content', '');
    	$param['status'] = I('status', '-1');
        
    	$where = "is_delete=0";
    	if (!empty($param['content'])){
			$where .= " AND content like '%{$param['content']}%'";
		}
    	if (-1 != $param['status']){
    		$where .= " AND status = '{$param['status']}'";
        }
     
    	$count = M('House_complaint')->where($where)->count();

    	 
    	$Page= new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show = $Page->show();// 分页显示输出
    	$list = M('House_complaint')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	 
    	$this->assign('page',$show);
    	 
        $this->assign('status', $param['status']);
    	$this->assign('total', $count);
    	$this->assign('list', $list);
    	$this->display(); 
    }
    
    public function edit(){
    	if (IS_POST){
    		$data = array();
    		$data = I('post.');
    
    		$_return_data = array('info'=>'保存失败!','status'=>'n');
    		
    		$flag = false;
    		$flag = M('House_complaint')->where("id = {$data['id']}")->save($data);
                if($data['status'] == '1'){
                    M('Houses')->where("id = {$data['house_id']}")->save(['check_time' => time(), 'status'=>1]);
                } 
                if($data['status'] == '2'){
                    M('Houses')->where("id = {$data['house_id']}")->save(['check_time' => time(), 'status'=>5]);
                }
                
    		if($flag !== false){
    			$_return_data = array('info'=>'保存成功!','status'=>'y');
    		}
    			
    		echo json_encode($_return_data);
    		exit;
    	}

    	$id = I('id');
    	if(empty($id)){
    		$this->error('参数错误!',U('HouseComplaint/index'));
    		exit;
    	}
    
    	$info = M('House_complaint')->where(array('id' => $id))->find();
        $this->assign('info',$info);     

        $this->display();
    }
    
    public function ajax_del(){
    	$data = array();
    	$data['id'] = I('id');
    	$data['is_delete'] = 1;
    	$data['delete_time'] = time();
    	D('House_complaint')->where("id = {$data['id']}")->save($data);
    	
    	$_return_data = array('info'=>'删除成功','status'=>'y');
    	
    	echo json_encode($_return_data);
    	exit;
    }
}