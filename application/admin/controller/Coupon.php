<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 优惠券管理
 *
 * @icon fa fa-circle-o
 */
class Coupon extends Backend
{
    protected $noNeedLogin = ['index', 'selectpage'];
    /**
     * Houses模型对象
     * @var \app\admin\model\Houses
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Coupon;
    }

    /*
     * 优惠券列表
     */

    public function index()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->where('is_valid', 1)
                    ->count();
            $list = $this->model
                    ->where($where)
                    ->where('is_valid', 1)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->field('id,title')
                    ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
}
