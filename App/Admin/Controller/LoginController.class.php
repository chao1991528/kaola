<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 登录操作
 * @author Gary <lizhiyong2204@sina.com>
 * @date 2015年12月14日
 * @todu
 */
class LoginController extends Controller {
	private $_verify_config = array(
		'length' => 4,
		'fontSize' => 28,
		'useCurve'=>false
	);
	private $verify = null;
	public function __construct(){
		parent::__construct();
		//$this->assign("web_title",C('COMPANY_NAME'));
		$this->verify =   new \Think\Verify($this->_verify_config);
	}
	
    public function index(){
    	$this->assign("web_title",'系统登录');
    	$this->display("Public/login");
    }

    public function checkLogin(){
    	$_return_data = array('info'=>'登录失败','status'=>'n','url'=>'');
    	if(IS_POST && IS_AJAX){
    		$username = I("post.username");
    		$password = I("post.password");    		
    		$code = I("post.code");
    		
    		$username = trim($username);
    		$password = trim($password);
    		$Verify =   new \Think\Verify($this->_verify_config);
    		if(!$Verify->check($code) && !$_SESSION['isMobile']){
    			$_return_data['status'] = -2;
    			$_return_data['info'] = '验证码错误';
    			$this->ajaxReturn($_return_data,'JSON');
    		}
                
    		$is_login = false;
    		$is_login = D('User')->checkLogin($username,$password); 
    		//登录成功
    		if($is_login){
   				$_return_data['status'] = 'y';
   				$_return_data['info'] = '登录成功';
   				$_return_data['url'] = U("Index/index");
    		}else{
    			//登录失败
    			$_return_data['status'] = 'n';
    			$_return_data['info'] = '用户名或密码错误';
    		}
    	}

    	$this->ajaxReturn($_return_data,'JSON');
    }
	
    
    /**
     * 生成验证码
     */
    public function verify(){    	
    	$Verify =     new \Think\Verify($this->_verify_config);
    	$Verify->entry();
    }
    
    /**
     * 退出
     */
    public function loginOut(){
    	session_destroy();
        redirect(U("Login/index"));
    }
    
}