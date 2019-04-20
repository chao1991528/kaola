<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class News extends Backend
{
    
    /**
     * News模型对象
     * @var \app\admin\model\News
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\News;
        $this->view->assign("isValidList", $this->model->getIsValidList());
        $this->view->assign("declareList", $this->model->getDeclareList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function add(){
        $data['categories'] = db('news_category')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,category_name');
        $data['types'] = db('news_type')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,type_name');
        $data['sources'] = db('news_source')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,source_name');
        $data['layouts'] = db('news_layout')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,layout_name');
        $this->assign($data);
        return parent::add();
    }

}
