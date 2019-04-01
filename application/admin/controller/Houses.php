<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 房源信息管理
 *
 * @icon fa fa-circle-o
 */
class Houses extends Backend
{
    
    /**
     * Houses模型对象
     * @var \app\admin\model\Houses
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Houses;

    }
    
    /*
     * 房源列表
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(["member","district"])
                    ->where($where)
                    ->count();
            $list = $this->model
                    ->with(["member", "district"])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

}
