<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use QL\QueryList;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class News extends Backend
{
    protected $noNeedRight = ['ajax_collect_wechat', 'getCategory', 'getSource', 'getType', 'getAdmin'];
    /**
     * News模型对象
     * @var \app\admin\model\News
     */
    protected $model = null;
    protected $modelValidate = true;

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
    public function index()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(["category", "type", "source", "layout", "user"])
                    ->where($where)
                    ->where('news.delete_time', 0)
                    ->count();
            $list = $this->model
                    ->with(["category", "type", "source", "layout", "user"])
                    ->where($where)
                    ->where('news.delete_time', 0)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function add(){
        $data['categories'] = db('news_category')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,category_name');
        $data['types'] = db('news_type')->where(['is_valid' => 1, 'is_delete' => 0])->where('type_name', 'not like', '图片%')->column('id,type_name');
        $data['sources'] = db('news_source')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,source_name');
        $data['layouts'] = db('news_layout')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,layout_name');
        $data['admin_ids'] = model('User')->where(['is_valid' => 1])->column('id,user_number');
        $this->assign($data);
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $params['add_time'] = time();
                    $params['delete_time'] = 0;
                    $params['content'] = htmlentities($params['content']);
                    $result = $this->model->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }


    /**
     * 编辑
     */
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
                    $params['content'] = htmlentities($params['content']);
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
        $row->news_picture = str_replace(['"', '[', ']', ' '], ['', '', '', ''], $row->news_picture);
        $row->news_image = str_replace(['"', '[', ']', ' '], ['', '', '', ''], $row->news_image);
        $row->content = html_entity_decode($row->content);
        if(!empty($row->news_picture)){
            $picture_arr = explode(',' , $row->news_picture);
            foreach ($picture_arr as $key => $value) {
                if($value == 'http://pool.kaolanews.com/upload'){
                    unset($picture_arr[$key]);
                }
            }
            $row->news_picture = empty($picture_arr) ? '' : implode(',', $picture_arr);
        }
        $data['categories'] = db('news_category')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,category_name');
        $data['types'] = db('news_type')->where(['is_valid' => 1, 'is_delete' => 0])->where('type_name', 'not like', '图片%')->column('id,type_name');
        $data['sources'] = db('news_source')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,source_name');
        $data['layouts'] = db('news_layout')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,layout_name');
        $data['admin_ids'] = model('User')->where(['is_valid' => 1])->column('id,user_number');
        $this->assign($data);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    public function ajax_collect_wechat()
    {
        $url = input('url');
        if(empty($url)){
            $this->error('微信采集地址不能为空！');
        }
        $_host = parse_url($url, PHP_URL_HOST);  //获取主机名
    	if($_host !== 'mp.weixin.qq.com') {
            $this->error('文章链接来源不属于微信');
        }

        $html = \fast\Http::get($url);
        if(empty($html)){
            $this->error('文章内容为空');
        }
        $html = str_replace("<!--headTrap<body></body><head></head><html></html>-->", "", $html);  //去除微信干扰元素!!!否则乱码
        preg_match("/var msg_cdn_url = \".*\"/", $html, $matches);   //获取微信封面图
        $coverImgUrl = $matches[0];
        $coverImgUrl = substr(explode('var msg_cdn_url = "', $coverImgUrl)[1], 0, -1);

        $rules = [  //设置QueryList的解析规则
            'title'   => ['#activity-name', 'text'],  //文章标题
            'content' => ['#js_content', 'html'],  //文章内容
            //'author'  => ['#js_profile_qrcode .profile_nickname','text'],  //公众号
        ];
        $data = QueryList::get($url)->rules($rules)->queryData(function($item){
                //利用回调函数下载文章中的图片并替换图片路径为本地路径
                //使用本例请确保当前目录下有image文件夹，并有写入权限
                $content = QueryList::html($item['content']);
                $content->find('img')->map(function($img){
                    $src     = $img->attrs('data-src')[0];
                    $imgpath = saveFileFromUrl($src);

                    $img->attr('src',$imgpath);
                    $img->removeAttr('data-src');
                    $img->removeAttr('data-ratio');
                    $img->removeAttr('data-w');

                });
                $item['content'] = $content->find('')->html();
                return $item;
            }
        );
        $data[0]['news_picture'] = saveFileFromUrl($coverImgUrl);

        $this->success('成功',null, $data[0]);
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
            $field = 'category_id,source_id,title_tag_id,news_tag_id,layout_id,type_id,news_title,news_picture,content,news_url,search_key,remark,declare_id,is_valid,is_recommend,is_hot,top_end_date,add_time,add_uid,is_applet,is_publish,publish_time,is_uploaded';
            $news = db('news')->field($field)->where('id', $id)->find();
            if ($news) {
                if($news['is_uploaded']){
                    $this->error('已经上传过了，请勿重复上传!');
                }
                $news['news_image'] = '';
                $news['publish_time'] = time();
                $news['is_publish'] = 1;
                unset($news['is_uploaded']);
                $data[] = $news;
            }
        }
        if(empty($data)){
            $this->error('上传失败：新闻信息为空!');
        }
        $newsModel = model('ProductNews');
        $newsModel->saveAll($data);
        db('news')->where('id', 'in', $ids)->update(['is_uploaded' => 1]);
        $this->success('上传成功!',null);
    }

    public function getSource()
    {
        $data =  model('NewsSource')->where('is_valid = 1 and is_delete = 0')->field('id,source_name as name')->select();
        return $this->success('ok', '', ['searchlist' => $data]);
    }

    public function getCategory()
    {
        $data =  model('NewsCategory')->where('is_valid = 1 and is_delete = 0')->field('id,category_name as name')->select();
        return $this->success('ok', '', ['searchlist' => $data]);
    }

    public function getType()
    {
        $data =  model('NewsType')->where('is_valid = 1 and is_delete = 0')->where('type_name', 'not like', '图片%')->field('id,type_name as name')->select();
        return $this->success('ok', '', ['searchlist' => $data]);
    }

    public function getAdmin()
    {
        $data =  model('User')->field('id,user_number as name')->select();
        return $this->success('ok', '', ['searchlist' => $data]);
    }
}
