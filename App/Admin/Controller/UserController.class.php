<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;

/**
 * @author Gary <lizhiyong2204@sina.com>
 * @date 2015年12月14日
 * @todu
 */
class UserController extends BaseController {
	
	public function __construct(){
		parent::__construct();
		
		$this->assign('role_list', C('ROLE_LIST'));
	}

    public function index(){
    	$param = array();
    	$param['user_number'] = I('user_number', '');
    	$param['role_id'] = I('role_id', '');
    	$param['is_valid'] = I('is_valid', '');
    	
    	$where = "id > 1";
    	if (!empty($param['user_number'])){
			$where .= " AND user_number like '%{$param['user_number']}%'";
		}
		if (!empty($param['role_id']) && $param['role_id'] != 0){
			$where .= " AND role_id = {$param['role_id']}";
		}
		if (!empty($param['is_valid'])){
			$where .= " AND is_valid = {$param['is_valid']}";
		}
		
		$count = D('User')->where($where)->count();
    	
    	$Page= new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show = $Page->show();// 分页显示输出
    	$list = D('User')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
    	
    	$this->assign('page',$show);
    	
    	$this->assign('total', $count);
    	$this->assign('list', $list);
    	$this->display(); 
    }
    
    public function add(){
    	if(IS_POST){
	    	$data = array();
	    	$data['user_number'] = I('user_number');
	    	$data['user_password'] = md5(I('password'));
	    	$data['role_id'] = I('role_id');
	    	$data['user_manager'] = I('user_manager');
	    	$data['user_cellphone'] = I('user_cellphone');
	    	$data['add_time'] = date('Y-m-d H:i:s');
	    	$data['add_uid'] = $_SESSION['user']['id'];
	    	
	    	$_return_data = array('info'=>'添加失败!','status'=>'n');
	    	
    		if(empty($data['user_number'])){
				$_return_data['info'] = '登录账号不能为空!';
				echo json_encode($_return_data);
				exit;
			}
			
			if(empty($data['user_password'])){
				$_return_data['info'] = '密码不能为空!';
				echo json_encode($_return_data);
				exit;
			}
	    	
	    	$flag = false;
			$flag = D('User')->add($data);
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
    		$data['id'] = I('userid');
    		$data['user_number'] = I('user_number');
    		$data['role_id'] = I('role_id');
	    	$data['user_manager'] = I('user_manager');
	    	$data['user_cellphone'] = I('user_cellphone');
	    	
	    	$_return_data = array('info'=>'保存失败!','status'=>'n');
	    	
    		if(empty($data['user_number'])){
				$_return_data['info'] = '登录账号不能为空!';
				echo json_encode($_return_data);
				exit;
			}
			
			$password = I('password');
			if(!empty($password)){
				$data['user_password'] = md5(I('password'));
			}
	    	$flag = false;
			$flag = D('User')->where("id = {$data['id']}")->save($data);
			if($flag !== false){
				$_return_data = array('info'=>'保存成功!','status'=>'y');
			}
			
			echo json_encode($_return_data);
			exit;
    	}
    	
    	$userid = I('userid');
		if(empty($userid)){
			$this->error('参数错误!',U('User/index'));
			exit;
		}
		
		$user = D('User')->where(array('id' => $userid))->find();
		$this->assign('user',$user);
    	$this->display();
    }
    
    public function modifypwd(){
    	if (IS_POST){
    		$pwd = I('password');
    		$_return_data = array('info'=>'修改密码失败','status'=>'n');
    		
    		if (empty($pwd)){
    			echo json_encode($_return_data);
	    		exit;
    		}
    		
	    	$flag = D('User')->where("id = '{$_SESSION['user']['id']}'")->save(array('user_password' => md5($pwd)));
	    	
	    	
	    	if($flag !== false)
	    		$_return_data = array('info'=>'修改密码成功','status'=>'y');
	    	
	    	echo json_encode($_return_data);
	    	exit;
    	}
    	$this->display();
    }
    
    public function operatelog(){
    	$id = I('get.id');
    	$list = M('UsersOperateLog')->where("uid = '{$id}'")->order('id desc')->select();
    	$this->assign('list',$list);
    	$this->display();
    }
    
    public function loginlog(){
    	$param = array();
    	$param['uid'] = I('uid', '');
    	$param['datemin'] = I('datemin', '');
    	$param['datemax'] = I('datemax', '');
    	
    	$where = "1=1";
    	if (!empty($param['uid']) && $param['uid'] != 0){
			$where .= " AND login_uid = '{$param['uid']}'";
		}
		if (!empty($param['datemin'])){
			$where .= " AND login_time >= '{$param['datemin']} 00:00:01'";
		}
		if (!empty($param['datemax'])){
			$where .= " AND login_time <= '{$param['datemax']} 23:59:59'";
		}
		
		$count = D('UserLoginLog')->where($where)->count();
    	
    	$Page= new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show = $Page->show();// 分页显示输出
    	$list = D('UserLoginLog')->where($where)->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
    	
    	$this->assign('page',$show);
    	
    	$this->assign('total', $count);
    	$this->assign('list', $list);
    	$this->display(); 
    }
    
    public function role(){
    	$param = array();
    	
    	$where = "1=1";
		
		$count = D('Role')->where($where)->count();
    	
    	$Page= new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show = $Page->show();// 分页显示输出
    	$list = D('Role')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
    	
    	$this->assign('page',$show);
    	
    	$this->assign('total', $count);
    	$this->assign('list', $list);
    	$this->display(); 
    }
    
    public function ajax_changeValid(){
    	$data = array();
    	$data['id'] = I('id');
    	$data['is_valid'] = I('valid', 1);
    	D('User')->where("id = {$data['id']}")->save($data);
    	
    	$_return_data = array('info'=>'','status'=>'y');
    	
    	echo json_encode($_return_data);
    	exit;
    }

    public function ajax_checkUserName(){
    	$uname = I('param');
    	$id = I('get.userid');
    	
    	$_return_data = array('info'=>'该账号已被使用','status'=>'n');
    	
    	if ($id == 0){
    		$userInfo = D('User')->where("user_number = '{$uname}'")->find();
    	}
    	else {
    		$userInfo = D('User')->where("user_number = '{$uname}' and id != {$id}")->find();
    	}
    	if(empty($userInfo))
    		$_return_data = array('info'=>'','status'=>'y');
    	
    	echo json_encode($_return_data);
    	exit;
    }
    
	public function ajax_delUser(){
		$id = I('post.id');
    	$_return_data = array('info'=>'删除失败!','status'=>'n');
		$flag = D('User')->where(array('id' => $id))->delete();
		if ($flag)
			$_return_data = array('info'=>'删除成功!','status'=>'y');
    	echo json_encode($_return_data);
    	exit;
	}
}