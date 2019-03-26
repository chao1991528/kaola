<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
use QL\QueryList;

/**
 * @author Gary <lizhiyong2204@sina.com>
 * @date 2015年12月14日
 * @todu
 */
class HouseBannerController extends BaseController {
	
	public function __construct(){
		parent::__construct();

		$this->assign('user_mapping', D('User')->getUserMapping());
		
	}

    public function index(){
    	$param = array();
    	$param['title'] = I('title', '');
    	
    	$where = "is_delete=0";
    	if (!empty($param['title'])){
			$where .= " AND title like '%{$param['title']}%'";
		}

    	$count = D('HouseBanner')->where($where)->count();
    	 
    	$Page= new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show = $Page->show();// 分页显示输出
    	$list = D('HouseBanner')->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	 
    	$this->assign('page',$show);
    	 
    	$this->assign('total', $count);
    	$this->assign('list', $list);
    	$this->display(); 
    }

    public function add(){
    	if(IS_POST){
    		$data = array();
    		
    		$data['title'] = htmlspecialchars_decode(I('title'));
    		$data['image'] = I('image');
    		$data['content'] = I('content');
    		$data['sort'] = I('sort');
    		$data['add_time'] = time();
    		$data['add_uid'] = $_SESSION['user']['id'];
    		
    		if(empty($data['title'])){
    			$_return_data['info'] = '请选择输入标题!';
    			echo json_encode($_return_data);
    			exit;
    		}
    		if(empty($data['image'])){
    			$_return_data['info'] = '请选择上传封面!';
    			echo json_encode($_return_data);
    			exit;
    		}
    		
    
    		$_return_data = array('info'=>'添加失败!','status'=>'n');
    
    		$flag = false;
    		$flag = D('HouseBanner')->add($data);
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
    		$data['title'] = htmlspecialchars_decode(I('title'));
    		$data['image'] = I('image');
    		$data['content'] = I('content');
    		$data['sort'] = I('sort');
    		
    		if(empty($data['title'])){
    			$_return_data['info'] = '请选择输入标题!';
    			echo json_encode($_return_data);
    			exit;
    		}
    		if(empty($data['image'])){
    			$_return_data['info'] = '请选择上传封面!';
    			echo json_encode($_return_data);
    			exit;
    		}
    		
    		$_return_data = array('info'=>'保存失败!','status'=>'n');
    		
    		$flag = false;
    		$flag = D('HouseBanner')->where("id = {$data['id']}")->save($data);
    		if($flag !== false){
    			$_return_data = array('info'=>'保存成功!','status'=>'y');
    		}
    			
    		echo json_encode($_return_data);
    		exit;
    	}
    	 
    	$id = I('id');
    	if(empty($id)){
    		$this->error('参数错误!',U('HouseBanner/index'));
    		exit;
    	}
    
    	$info = D('HouseBanner')->where(array('id' => $id))->find();
    	$info['content'] = html_entity_decode($info['content']);
    	
    	
    	$this->assign('info',$info);
    	
    	$this->display();
    	
    }
    
    public function content(){
    	$id = I('id');
    	if(empty($id)){
    		$this->error('参数错误!',U('HouseBanner/index'));
    		exit;
    	}
    	
    	$info = D('HouseBanner')->where(array('id' => $id))->find();
    	
    	$info['content'] = html_entity_decode($info['content']);
    	
    	
    	$this->assign('info',$info);
    	$this->display();
    }
    
    public function ajax_del(){
    	$data = array();
    	$data['id'] = I('id');
    	$data['is_delete'] = 1;
    	$data['delete_time'] = time();
    	D('HouseBanner')->where("id = {$data['id']}")->save($data);
    	
    	$_return_data = array('info'=>'删除成功','status'=>'y');
    	
    	echo json_encode($_return_data);
    	exit;
    }
    
    
    public function ajax_changeValid(){
        $data = array();
        $data['id'] = I('id');
        $data['is_valid'] = I('valid', 0);
        D('HouseBanner')->where("id = {$data['id']}")->save($data);
        
        $_return_data = array('info'=>'','status'=>'y');
        
        echo json_encode($_return_data);
        exit;
    }

}