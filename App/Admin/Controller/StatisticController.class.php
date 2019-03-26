<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;

/**
 * @author Gary <lizhiyong2204@sina.com>
 * @date 2015年12月14日
 * @todu
 */
class StatisticController extends BaseController {
	
	public function __construct(){
		parent::__construct();
		
	}

    public function news(){
    	if (empty(I("start_time"))) {
    		$_GET["start_time"] = date('Y-m-d', time());
    	}
    	if (empty(I("end_time"))) {
    		$_GET["end_time"] = date('Y-m-d', time());
    	}
    	$param = array();
    	$param['news_title'] = I('news_title', '');
    	$param['start_time'] = I('start_time', '');
    	$param['end_time'] = I('end_time', '');
    	$param['order'] = I('order', 'id');
    	
    	$where = "1=1";
    	if (!empty($param['start_time'])){
			$where .= " AND add_time >= '".strtotime($param['start_time'] . ' 00:00:00')."'";
		}
    	if (!empty($param['end_time'])){
			$where .= " AND add_time <= '".strtotime($param['end_time'] . ' 23:59:59')."'";
		}
		if (!empty($param['news_title'])){
			$where .= " AND news_title like '%{$param['news_title']}%'";
		}

    	$count = D('News')->where($where)->count();
    	 
    	$Page= new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show = $Page->show();// 分页显示输出
    	$list = D('News')->where($where)->order($param['order'] . ' desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	
    	 
    	$this->assign('page',$show);
    	 
    	$this->assign('total', $count);
    	$this->assign('list', $list);
    	
    	$this->assign('category_mapping', D('NewsCategory')->getMapping());
    	$this->assign('layout_mapping', D('NewsLayout')->getMapping());
    	$this->assign('source_mapping', D('NewsSource')->getMapping());
    	$this->assign('tag_mapping', D('NewsTag')->getMapping());
    	$this->assign('type_mapping', D('NewsType')->getMapping());
    	$this->display(); 
    }
    
}