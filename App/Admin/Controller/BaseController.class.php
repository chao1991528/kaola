<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 
 * @author Gary <lizhiyong2204@sina.com>
 * @date 2015年12月14日
 * @todu
 */
class BaseController extends Controller {
	private $no_check_login = array();
	//跳过权限检测的方法
	private $no_check_priv  = array();
   public function __construct(){
   		parent::__construct();
   		header("Content-type: text/html; charset=utf-8");
   		$this->checkLogin();
   		$this->assign('session_user',$_SESSION['user']);
   		$this->assign('sys_title',C('SYS_TITLE'));

   		$this->assign('sex_list',C('SEX_LIST'));
   		$this->assign('user_mapping', D('User')->getUserMapping());
   }
   
   private function checkLogin(){
       $UserModel = D('User');
       $is_login = $UserModel->isLogin();
       if(!$is_login || !isset($is_login['role_id']))
    	{	
    		 if(CONTROLLER_NAME != 'Index'){
    		 	$url = U('Login/index');
    		 	$html = "<script type='text/javascript'>";
    		 	$html .= "window.top.location.href='$url'";
    		 	$html .= "</script>";
    		 	echo $html;
    		 	exit;
    		 }
    		//$this->error('请先登录系统',U("Login/index"),0);
    		redirect(U("Login/index"));
    	}
    }
   

    public function upload(){
    	if (!empty($_FILES)) {
    		$model = I('get.model');
    		$thumb = I('get.thumb', 0);
    		$thumb_width = I('get.thumb_width', 400);
    		$thumb_height = I('get.thumb_height', 400);
    		$tempFile = $_FILES['file']['tmp_name'];
    		$fileTypes = array('jpg','jpeg','png','gif','mp4'); // File extensions
    		$fileParts = $_FILES['file'];
    		$ex = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
    		$filename  = $model."_".date('YmdHis', NOW_TIME) . rand(10000, 99999) .'.'.$ex;
    		$filename_thumb = basename($filename, ".{$ex}") . "_thumb_{$thumb_width}." . $ex;
    		$targetDir = UPLOAD_PATH . '/'. $model . '/' . date('Ymd') . '/';
    		$targetFile =  $targetDir . $filename;
    		is_dir($targetDir) OR mkdir($targetDir, 0777, true);
    		if (in_array($ex, $fileTypes)) {
    			@move_uploaded_file($tempFile,$targetFile);
    			if (file_exists($targetFile)) {
    				$filepath = '/Uploads/'.$model. '/' . date('Ymd').'/' . $filename;
        			$image = new \Think\Image();
                                $image->open($targetFile)->water('./Public/images/water_logo.png', \Think\Image::IMAGE_WATER_SOUTHEAST)->save($targetFile);
    				if ($thumb) {
						$image->open($targetFile);
						$image->thumb($thumb_width, $thumb_height);
						$image->save($targetDir . $filename_thumb);
						
						if ($thumb == 2) {
							$thumb_width2 = I('get.thumb_width2', 750);
							$thumb_height2 = I('get.thumb_height2', 750);
							$filename_thumb2 = basename($filename, ".{$ex}") . "_thumb_{$thumb_width2}." . $ex;
							$image->open($targetFile);
							$image->thumb($thumb_width2, $thumb_height2);
							$image->save($targetDir . $filename_thumb2);
						}
    				}
    				$rs = array('code'=>1,'info'=>'文件上传成功!','filename'=>$filepath);
    				exit(json_encode($rs));
    			} else {
    				$rs = array('code'=>-3,'info'=>'文件上传失败!');
    				exit(json_encode($rs));
    			}
    		} else {
    			$rs = array('code'=>-2,'info'=>'请上传正确格式的文件!');
    			exit(json_encode($rs));
    		}
    	}
    
    	$rs = array('code'=>-1,'info'=>'文件上传失败!');
    	exit(json_encode($rs));
    }
}