<?php
return array(
    'api' => array(
        'domains' => array("http://mywww.kaola.com/index.php"=>'本地',"http://test.kaolanews.com/"=>'测试机',"http://www.kaolanews.com/"=>'线上'),
        'method' => array(
            array(
                'opt_name' => '签名',
                'opt_data' => array(
                    array('url'=>'/Api/Api/getSign','name'=>'测试签名','param'=>'{"loginname":"18565661912","password":"123456","test":1}'),
                	array('url'=>'/Api/Discount/getAuthSign','name'=>'获取签名','param'=>'{"loginname":"18565661912","password":"123456"'),
                )
            ),
        		
            array(
                'opt_name' => '用户模块',
                'opt_data' => array(
                    array('url'=>'/Api/Member/login','name'=>'用户登陆','param'=>'{"login_type":"1","loginname":"18565661912","password":"123456", "prefix":"+86"}'),
                    array('url'=>'/Api/Member/register','name'=>'用户注册','param'=>'{"prefix":"86","loginname":"18565661912","password":"123456","from":"1","code":"333333"}'),
                    array('url'=>'/Api/Member/modifyPwd','name'=>'修改密码','param'=>'{"mem_id":"1","password":"123456","password2":"123456"}'),
                    array('url'=>'/Api/Member/forgotPwd','name'=>'忘记密码','param'=>'{"loginname":"18565661912","new_password":"123456","code":"333333"}'),
                    array('url'=>'/Api/Member/updateInfo','name'=>'修改个人信息','param'=>'{"sessionid":"15334753055b66f9e98e869", "nick_name":"八戒1", "sex":"1"}'),
                    array('url'=>'/Api/Member/getInfo','name'=>'获取个人信息','param'=>'{"sessionid":"15334753055b66f9e98e869"}'),
                	array('url'=>'/Api/Member/getDiscussList','name'=>'评论列表','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1", "op":"2"}'),
                    array('url'=>'/Api/Member/uploadHeadpic','name'=>'上传头像','param'=>'{"sessionid":"15334753055b66f9e98e869","headpic":""}'),
                	array('url'=>'/Api/Member/bandOpenid','name'=>'绑定授权','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"2", "openid":"123456", "op":"1"}'),
                )
            ),
            
        	array(
        		'opt_name' => '公共模块',
        		'opt_data' => array(
        			array('url'=>'/Api/Public/getMobilePrefixList','name'=>'手机国际区号','param'=>'{"is_valid" : "1"}'),
        			array('url'=>'/Api/Public/sendCode','name'=>'发送短信','param'=>'{"prefix" : "+86", "mobile":"18565661912"}'),
        			array('url'=>'/Api/Public/getCityList','name'=>'获取地址','param'=>'{"pid" : "0"}'),
        			array('url'=>'/Api/Public/like','name'=>'内容点赞','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1","op":"2"}'),
        			array('url'=>'/Api/Public/getLikeList','name'=>'内容点赞列表','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1"}'),
        			array('url'=>'/Api/Public/play','name'=>'播放视频','param'=>'{"type":"video", "id":"1"}'),
        			array('url'=>'/Api/Public/discuss','name'=>'写评论','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1", "content":"我测试", "pid":"0"}'),
        			array('url'=>'/Api/Public/discussLike','name'=>'评论点赞','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1", "op":"1"}'),
        			array('url'=>'/Api/Public/discussRemove','name'=>'删除评论','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1"}'),
        			array('url'=>'/Api/Public/getDiscussList','name'=>'评论列表','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1", "pid":"0"}'),
        			array('url'=>'/Api/Public/fans','name'=>'关注','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"member", "id":"1", "op":"1"}'),
        			array('url'=>'/Api/Public/getFansList','name'=>'关注列表','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"member", "id":"1"}'),
        			array('url'=>'/Api/Public/collect','name'=>'内容收藏','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1","op":"1"}'),
        			array('url'=>'/Api/Public/getCollectList','name'=>'内容收藏列表','param'=>'{"sessionid":"15334753055b66f9e98e869"}'),
        			array('url'=>'/Api/Public/three_in_one','name'=>'三合一','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1"}'),
        			array('url'=>'/Api/Public/getVisitList','name'=>'浏览历史列表','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news"}'),
        			array('url'=>'/Api/Public/deleteVisit','name'=>'删除浏览历史','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1"}'),
        			array('url'=>'/Api/Public/complaint','name'=>'投诉举报','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"news", "id":"1", "complaint_type_id":"2", "content":"", "op":"1"}'),
        			array('url'=>'/Api/Public/blacklist','name'=>'屏蔽','param'=>'{"sessionid":"15334753055b66f9e98e869", "type":"source", "id":"1", "op":"1"}'),
        			array('url'=>'/Api/Public/openScreen','name'=>'开屏广告','param'=>'{}'),
        		),
        	),
        	array(
        		'opt_name' => '新闻模块',
        		'opt_data' => array(
        			array('url'=>'/Api/News/getList','name'=>'新闻列表','param'=>'{"category_id" : "1", "page":"1"}'),
        			array('url'=>'/Api/News/getCategoryList','name'=>'频道列表','param'=>'{"news_type":"news"}'),
        			array('url'=>'/Api/News/getDetail','name'=>'新闻详情','param'=>'{"id":"1"}'),
        			array('url'=>'/Api/News/searchHistory','name'=>'搜索历史','param'=>'{"sessionid":"15334753055b66f9e98e869","deviceid":"123456"}'),
        			array('url'=>'/Api/News/searchClear','name'=>'清除搜索历史','param'=>'{"sessionid":"15334753055b66f9e98e869","deviceid":"123456"}'),
        			array('url'=>'/Api/News/search','name'=>'搜索','param'=>'{"sessionid":"15334753055b66f9e98e869","deviceid":"123456","category_id" : "1","search":"澳洲"}'),
        			array('url'=>'/Api/News/searchForApplet','name'=>'搜索小程序','param'=>'{"search":"澳洲"}'),
        			array('url'=>'/Api/News/getTangdouList','name'=>'获取糖豆列表','param'=>'{"page_size":"4"}'),
        			array('url'=>'/Api/News/getRoadList','name'=>'获取路况','param'=>'{}'),
        		),
        	),
        	array(
        		'opt_name' => '小视频模块',
        		'opt_data' => array(
        			array('url'=>'/Api/SVideo/getList','name'=>'小视频列表','param'=>'{"category_id" : "1", "page":"1", "page_size":"20"}'),
        			array('url'=>'/Api/SVideo/getCategoryList','name'=>'频道列表','param'=>'{}'),
        			array('url'=>'/Api/SVideo/getDetail','name'=>'小视频详情','param'=>'{"id":"1"}'),
        			array('url'=>'/Api/SVideo/search','name'=>'搜索','param'=>'{"sessionid":"15334753055b66f9e98e869","deviceid":"123456","category_id" : "-1","search":"澳洲"}'),
        			array('url'=>'/Api/SVideo/uploadVideo','name'=>'上传小视频','param'=>'{"sessionid":"15334753055b66f9e98e869","video_title":"123456","category_id" : "1"}'),
        		)
        	),
        	array(
        		'opt_name' => '视频模块',
        		'opt_data' => array(
        			array('url'=>'/Api/Video/getList','name'=>'视频列表','param'=>'{"category_id" : "1", "page":"1"}'),
        			array('url'=>'/Api/Video/getCategoryList','name'=>'频道列表','param'=>'{}'),
        			array('url'=>'/Api/Video/getDetail','name'=>'视频详情','param'=>'{"id":"1"}'),
        			array('url'=>'/Api/Video/search','name'=>'搜索','param'=>'{"sessionid":"15334753055b66f9e98e869","deviceid":"123456","category_id" : "-1","search":"澳洲"}'),
        		)
        	),
        	array(
        		'opt_name' => '版本模块',
        		'opt_data' => array(
        			array('url'=>'/Api/Version/update','name'=>'版本更新','param'=>'{"version" : "1.0.0", "type_id":"1"}'),
        		)
        	),
        	array(
        		'opt_name' => '折扣模块',
        		'opt_data' => array(
        			array('url'=>'/Api/Discount/getList','name'=>'折扣列表','param'=>'{"category_id" : "1", "page":"1"}'),
        			array('url'=>'/Api/Discount/getCategoryList','name'=>'频道列表','param'=>'{}'),
        			array('url'=>'/Api/Discount/getDetail','name'=>'折扣详情','param'=>'{"id":"1"}'),
        		),
        	),
        	array(
        		'opt_name' => '旅游模块',
        		'opt_data' => array(
        			array('url'=>'/Api/Tour/getList','name'=>'旅游列表','param'=>'{"category_id" : "1", "page":"1"}'),
        			array('url'=>'/Api/Tour/getCategoryList','name'=>'频道列表','param'=>'{}'),
        			array('url'=>'/Api/Tour/getDetail','name'=>'旅游详情','param'=>'{"id":"1"}'),
        		),
        	),
        	array(
        		'opt_name' => '话题模块',
        		'opt_data' => array(
        			array('url'=>'/Api/Topic/getTopicList','name'=>'话题列表','param'=>'{"category_id" : "1", "page":"1"}'),
        			array('url'=>'/Api/Topic/getTopicDetail','name'=>'话题详情列表','param'=>'{"id" : "1", "type_id":"1", "page":"1"}'),
        			array('url'=>'/Api/Topic/getMemberDetail','name'=>'话题用户详情','param'=>'{"sessionid":"15334753055b66f9e98e869", "mem_id" : "18", "page":"1"}'),
        			array('url'=>'/Api/Topic/getCategoryList','name'=>'频道列表','param'=>'{}'),
        			array('url'=>'/Api/Topic/getTopicSquareList','name'=>'话题广场列表','param'=>'{"page":"1","page_size":"20"}'),
        			array('url'=>'/Api/Topic/getCareList','name'=>'关注列表','param'=>'{"sessionid":"15334753055b66f9e98e869","page":"1"}'),
        			array('url'=>'/Api/Topic/fans','name'=>'话题关注','param'=>'{"sessionid":"15334753055b66f9e98e869","type":"1","id":"1","op":"1"}'),
        			array('url'=>'/Api/Topic/fansAll','name'=>'一键关注','param'=>'{"sessionid":"15353842025b841a8ae4510","ids":"1,2,4"}'),
        			array('url'=>'/Api/Topic/like','name'=>'话题内容点赞','param'=>'{"sessionid":"15334753055b66f9e98e869","id":"1","op":"1"}'),
        			array('url'=>'/Api/Topic/getDetail','name'=>'话题内容详情','param'=>'{"sessionid":"15334753055b66f9e98e869","id":"1"}'),
        			array('url'=>'/Api/Topic/discuss','name'=>'话题内容评论','param'=>'{"sessionid":"15334753055b66f9e98e869", "id":"1", "content":"我测试", "pid":"0"}'),
        			array('url'=>'/Api/Topic/discussLike','name'=>'评论点赞','param'=>'{"sessionid":"15334753055b66f9e98e869", "id":"1", "op":"1"}'),
        			array('url'=>'/Api/Topic/discussRemove','name'=>'删除评论','param'=>'{"sessionid":"15334753055b66f9e98e869", "id":"1"}'),
        			array('url'=>'/Api/Topic/getDiscussList','name'=>'评论列表','param'=>'{"sessionid":"15334753055b66f9e98e869", "id":"1", "pid":"0"}'),
        			array('url'=>'/Api/Topic/search','name'=>'搜索','param'=>'{"sessionid":"15334753055b66f9e98e869","type":"1","search":"测试"}'),
        			array('url'=>'/Api/Topic/deleteTopicSquare','name'=>'话题内容删除','param'=>'{"sessionid":"15334753055b66f9e98e869","id":"1"}'),
        		),
        	),
        )
    )
);