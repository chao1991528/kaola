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
    protected $noNeedRight = ['ajax_get_category'];

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
                    ->with(["city", "category", "member", "user"])
                    ->where($where)
                    ->where(['live.is_delete' => 0, 'live.delete_time' => 0])
                    ->count();
            $list = $this->model
                    ->with(["city", "category", "member", "user"])
                    ->where($where)
                    ->where(['live.is_delete' => 0, 'live.delete_time' => 0])
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $data['admin_ids'] = model('ProductUser')->where(['is_valid' => 1])->column('id,user_number');
        $this->assign($data);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * ajax获取分类
     * @return type
     */
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

    /**
     * 上传到正式服务器
     */
    public function uploadToProduct($ids)
    {
        if(empty($ids)){
            $this->error('id不能为空！');
        }
        $ids = explode(',', $ids);
        $data = [];
        foreach ($ids as $id) {
            $lives = db('live')->field('id,source_url,email_image', true)->where('id', $id)->find();
            if (!empty($lives)) {
                if($lives['is_uploaded']){
                    $this->error('已经上传过了，请勿重复上传!');
                }else{
                    unset($lives['is_uploaded']);
                }
                $data[] = $lives;
            }
        }
        if(empty($data)){
            $this->error('上传失败：新闻信息为空!');
        }
        $liveModel = model('ProductLive');
        $liveModel->saveAll($data);
        db('live')->where('id', 'in', $ids)->update(['is_uploaded' => 1]);
        $this->success('上传成功!',null);
    }

}
