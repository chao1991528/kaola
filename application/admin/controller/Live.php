<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Live extends Backend
{
    
    /**
     * Live模型对象
     * @var \app\admin\model\Live
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Live;
        $tagList = db('live_tag')->where(['is_valid' => 1, 'is_delete' => 0])->select();
        $statusList = $this->model->getStatusList();
        $isEnsureList = $this->model->getIsEnsureList();
        $isTopList = $this->model->getIsTopList();
        $this->assign(['liveTagList' => $tagList, 'statusList' => $statusList, 'isTopList' => $isTopList, 'isEnsureList' => $isEnsureList]);
    }

    /**
     * 列表页
     * @return type
     */
    public function index()
    {       
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->alias('news')
                    ->with(["city", "category"])
                    ->where($where)
                    ->count();
            $list = $this->model
                    ->alias('news')
                    ->with(["city", "category"])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    
    public function ajax_get_category()
    {
        $page = $this->request->request("pageNumber");
        $pagesize = $this->request->request("pageSize");
        $city_id = $this->request->request("city_id");
        $where = $city_id ? ['is_valid' => 1, 'is_delete' => 0, 'city_id' => $city_id] : ['is_valid' => 1, 'is_delete' => 0];
        $total = db('live_category')->where($where)->count();
        $list = db('live_category')->where($where)
                ->field('id,category_name as name')
                ->page($page, $pagesize)
                ->select();
        return json(['list' => $list, 'total' => $total]);
//        $this->success(__('success'), null, $categories);
    }

}
