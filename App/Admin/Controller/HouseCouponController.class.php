<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;

/**
 * @author Gary <lizhiyong2204@sina.com>
 * @date 2015年12月14日
 * @todu
 */
class HouseCouponController extends BaseController {
	
	public function __construct(){
		parent::__construct();
		
	}

    public function index(){
    	$param = array();
    	$param['title'] = I('title', '');
    	
    	$where = "is_delete=0";
    	if (!empty($param['title'])){
			$where .= " AND title like '%{$param['title']}%'";
		}

    	$count = D('HouseCoupon')->where($where)->count();
    	 
    	$Page= new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show = $Page->show();// 分页显示输出
    	$list = D('HouseCoupon')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
    	 
    	$this->assign('page',$show);
    	 
    	$this->assign('total', $count);
    	$this->assign('list', $list);
    	$this->display(); 
    }
    
    public function add(){
    	if(IS_POST){
    		$data = array();
    		$data['title'] = I('title');
    		$data['icon'] = I('icon');
    		$data['add_time'] = time();
    		$data['add_uid'] = $_SESSION['user']['id'];
    
    		$_return_data = array('info'=>'添加失败!','status'=>'n');
    
    		if(empty($data['title'])){
    			$_return_data['info'] = '名称不能为空!';
    			echo json_encode($_return_data);
    			exit;
    		}
    
    		$flag = false;
    		$flag = D('HouseCoupon')->add($data);
    		if($flag)
    			$_return_data = array('info'=>'添加成功!','status'=>'y');
    			
    		echo json_encode($_return_data);
    		exit;
    
    	}
    	$this->display();
    }
    
    public function edit(){
    	if (IS_POST){
    		$data = array();
    		$data['id'] = I('id');
    		$data['title'] = I('title');
    		$data['icon'] = I('icon');
    
    		$_return_data = array('info'=>'保存失败!','status'=>'n');
    
    		if(empty($data['title'])){
    			$_return_data['info'] = '名称不能为空!';
    			echo json_encode($_return_data);
    			exit;
    		}
    		
    		$flag = false;
    		$flag = D('HouseCoupon')->where("id = {$data['id']}")->save($data);
    		if($flag !== false){
    			$_return_data = array('info'=>'保存成功!','status'=>'y');
    		}
    			
    		echo json_encode($_return_data);
    		exit;
    	}
    	 
    	$id = I('id');
    	if(empty($id)){
    		$this->error('参数错误!',U('HouseCoupon/index'));
    		exit;
    	}
    
    	$info = D('HouseCoupon')->where(array('id' => $id))->find();
    	$this->assign('info',$info);
    	$this->display();
    }
    
    public function ajax_del(){
    	$data = array();
    	$data['id'] = I('id');
    	$data['is_delete'] = 1;
    	$data['delete_time'] = time();
    	D('HouseCoupon')->where("id = {$data['id']}")->save($data);
    	
    	$_return_data = array('info'=>'删除成功','status'=>'y');
    	
    	echo json_encode($_return_data);
    	exit;
    }
    
    public function ajax_changeValid(){
        $data = array();
        $data['id'] = I('id');
        $data['is_valid'] = I('valid', 0);
        D('HouseCoupon')->where("id = {$data['id']}")->save($data);
        
        $_return_data = array('info'=>'','status'=>'y');
        
        echo json_encode($_return_data);
        exit;
    }
}