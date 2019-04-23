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
                    ->alias('news')
                    ->with(["category", "type", "source", "layout"])
                    ->where($where)
                    ->where('news.delete_time', 0)
                    ->count();
            $list = $this->model
                    ->alias('news')
                    ->with(["category", "type", "source", "layout"])
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
        $this->assign($data);
        return parent::add();
    }

    public function edit($ids = NULL){
        $data['categories'] = db('news_category')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,category_name');
        $data['types'] = db('news_type')->where(['is_valid' => 1, 'is_delete' => 0])->where('type_name', 'not like', '图片%')->column('id,type_name');
        $data['sources'] = db('news_source')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,source_name');
        $data['layouts'] = db('news_layout')->where(['is_valid' => 1, 'is_delete' => 0])->column('id,layout_name');
        $this->assign($data);
        return parent::edit($ids);
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
        
        $html = file_get_contents($url);
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
}
