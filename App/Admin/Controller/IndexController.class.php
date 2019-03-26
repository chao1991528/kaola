<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;

/**
 * @author Gary <lizhiyong2204@sina.com>
 * @date 2015年12月14日
 * @todu
 */
class IndexController extends BaseController {

    public function index(){
    	
		$this->display(); 
    }
    
    public function welcome(){
    	$start_time = time() - 7*24*60*60;
    	$end_time = time();
    	
    	$news_list = D('MemberVisit')->where("type = 'news' and add_time between {$start_time} and {$end_time}")->field('count(item_id) as c, item_id')->group('item_id')->order('c desc')->limit('0,10')->select();
    	foreach ($news_list as &$item){
    		$info = D('News')->where("id = {$item['item_id']}")->field('news_title')->find();
    		$item['title'] = $info['news_title'];
    	}
    	
    	$svideo_list = D('MemberVisit')->where("type = 's_video' and add_time between {$start_time} and {$end_time}")->field('count(item_id) as c, item_id')->group('item_id')->order('c desc')->limit('0,10')->select();
    	foreach ($svideo_list as &$item){
    		$info = D('SVideo')->where("id = {$item['item_id']}")->field('video_title')->find();
    		$item['title'] = $info['video_title'];
    	}
    	
    	$video_list = D('MemberVisit')->where("type = 'video' and add_time between {$start_time} and {$end_time}")->field('count(item_id) as c, item_id')->group('item_id')->order('c desc')->limit('0,10')->select();
    	foreach ($video_list as &$item){
    		$info = D('Video')->where("id = {$item['item_id']}")->field('video_title')->find();
    		$item['title'] = $info['video_title'];
    	}
    	
    	
    	$this->assign('news_list', $news_list);
    	$this->assign('svideo_list', $svideo_list);
    	$this->assign('video_list', $video_list);
    	$this->display();
    }

}