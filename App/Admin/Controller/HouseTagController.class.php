<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;

/**
 * @author Gary <lizhiyong2204@sina.com>
 * @date 2015年12月14日
 * @todu
 */
class HouseTagController extends BaseController {
	
	public function __construct(){
		parent::__construct();
		
	}

    public function index(){
    	$param = array();
    	$param['tag_name'] = I('tag_name', '');
    	
    	$where = "is_delete=0";
    	if (!empty($param['tag_name'])){
			$where .= " AND tag_name like '%{$param['tag_name']}%'";
		}

    	$count = D('HouseTag')->where($where)->count();
    	 
    	$Page= new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show = $Page->show();// 分页显示输出
    	$list = D('HouseTag')->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	 
    	$this->assign('page',$show);
    	 
    	$this->assign('total', $count);
    	$this->assign('list', $list);
    	$this->display(); 
    }
    
    public function add(){
    	if(IS_POST){
    		$data = array();
    		$data['tag_name'] = I('tag_name');
    		$data['sort'] = I('sort');
    		$data['add_time'] = time();
    		$data['add_uid'] = $_SESSION['user']['id'];
    
    		$_return_data = array('info'=>'添加失败!','status'=>'n');
    
    		if(empty($data['tag_name'])){
    			$_return_data['info'] = '名称不能为空!';
    			echo json_encode($_return_data);
    			exit;
    		}
    
    		$flag = false;
    		$flag = D('HouseTag')->add($data);
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
    		$data['tag_name'] = I('tag_name');
    		$data['sort'] = I('sort');
    
    		$_return_data = array('info'=>'保存失败!','status'=>'n');
    
    		if(empty($data['tag_name'])){
    			$_return_data['info'] = '名称不能为空!';
    			echo json_encode($_return_data);
    			exit;
    		}
    		
    		$flag = false;
    		$flag = D('HouseTag')->where("id = {$data['id']}")->save($data);
    		if($flag !== false){
    			$_return_data = array('info'=>'保存成功!','status'=>'y');
    		}
    			
    		echo json_encode($_return_data);
    		exit;
    	}
    	 
    	$id = I('id');
    	if(empty($id)){
    		$this->error('参数错误!',U('HouseTag/index'));
    		exit;
    	}
    
    	$info = D('HouseTag')->where(array('id' => $id))->find();
    	$this->assign('info',$info);
    	$this->display();
    }
    
    public function ajax_del(){
    	$data = array();
    	$data['id'] = I('id');
    	$data['is_delete'] = 1;
    	$data['delete_time'] = time();
    	D('HouseTag')->where("id = {$data['id']}")->save($data);
    	
    	$_return_data = array('info'=>'删除成功','status'=>'y');
    	
    	echo json_encode($_return_data);
    	exit;
    }
    
    public function ajax_changeValid(){
        $data = array();
        $data['id'] = I('id');
        $data['is_valid'] = I('valid', 0);
        D('HouseTag')->where("id = {$data['id']}")->save($data);
        
        $_return_data = array('info'=>'','status'=>'y');
        
        echo json_encode($_return_data);
        exit;
    }
}