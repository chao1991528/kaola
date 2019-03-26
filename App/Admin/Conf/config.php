<?php
return array(
	'SYS_TITLE' 			 => '考拉资讯后台管理系统',
	'DEFAULT_THEME'      => 'Default',	// 默认模板主题名称
	'TMPL_PARSE_STRING'  =>array(
			'__PUBLIC__' => '/Public',
			'__JS__'     => '/Public/js',
			'__CSS__'    => '/Public/css',
			'__IMAGE__'  => '/Public/images',
			'__LIB__'	 => '/Public/lib', 
			'__UPLOAD__' => '/Uploads',
	),
    
    "SEX_LIST" => array(
        0   => '保密',
        1   => '男',
        2   => '女',
    ),
		
	"ROLE_LIST"	=> array(
		1	=> '系统管理员',
	    20  => '运维管理员',
	    21  => '运维专员'
	),
    
	"LOGIN_TYPE" => array(
		1	=> '手机号',
		2	=> '微信',
		3	=> 'QQ',
		4	=> '微博'
	),
	"VIDEO_STATUS_LIST" => array(
		0	=> '等待审核',
		1	=> "审核通过",
		5	=> "用户删除",
		6	=> "审核未通过",
		7	=> "被举报",
	),
	"VERSION_TYPE_LIST" => array(
		1	=> "IOS",
		2	=> "Android",
	),
		
	"DECLARE_LIST" => array(
		1	=> '编译声明',
		2	=> '转载声明',
		3	=> '原创声明'
	),
	
	"HOUSE_AD_TYPE_LIST" => array(
		0	=> '',
		1	=> '左侧',
		2	=> '右上',
		3	=> '右下',
	),
);