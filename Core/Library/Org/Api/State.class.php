<?php
/**
 * Created by PhpStorm.
 * User: gary.li
 * Date: 2018/1/13 0013
 * Time: 18:12
 */
namespace Org\Api;

class State{
    const SUCCESS_CODE  = 0;//成功请求
    /*系统级别错误定义在1000以下*/
    const CRYPTO_ERROR  = 400;//请求解析失败
    const PERM_ERROR    = 403;//无权访问API
    const API_NOT_FOUND = 404;//API不存在
    const API_EXPIRE    = 410;//API接口已过期
    const REQUEST_EXPIRE= 455;//请求时间戳超过10分钟
    const SYSTEM_ERROR  = 500;//系统异常
    
    const EXCEPT_ERROR_ONE = 301;//签名数据出错     
    const EXCEPT_ERROR_TWO = 302;//签名数据出错2

    const MYSQL_ERROR_OPERATE = 1062;//数据库操作失败
    /*系统级别错误定义在1000以下*/

    /**Member模块**/
    const MEMBER_INVALID_MOBILE			= 21001;//手机号不合法
    const MEMBER_INVALID_PASSWORD		= 21002;//密码不合法
    const MEMBER_INVALID_OPENID			= 21003;//第三方授权失败
    const MEMBER_INVALID_MEMBER			= 21005;//账户被禁用
    const MEMBER_NOT_EXIST_MEMBER		= 21006;//账户不存在
    const MEMBER_EXIST_MEMBER			= 21007;//账户已存在
    const MEMBER_INVALID_CODE			= 21008;//验证码已失效
    const MEMBER_DONOT_REPEAT			= 21002;//不要重复发送短信
    const MEMBER_INVALID_PWD_ERROR		= 21010;//密码错误
    const MEMBER_LOGIN_ERROR			= 21020;//没有登录
    const MEMBER_NO_FILE				= 21030;//没有收到文件
    const MEMBER_NOT_RIGHT_FILE			= 21031;//文件格式不正确
    
    /**News模块**/
    const NEWS_INVALID_CATEGORY_ID		= 20001;//category_id不合法
    const NEWS_INVALID_NEWS_TYPE		= 20002;//news_type不合法
    const NEWS_INVALID_NEWS_ID			= 20003;//id 不合法
    const NEWS_NOT_VALID				= 20010;//news未启用
    const NEWS_IS_DELETE				= 20011;//news被删除
    
    /**Video模块**/
    const Video_INVALID_CATEGORY_ID		= 20001;//category_id不合法
    const Video_INVALID_TITLE			= 21002;//video_title不合法
    const Video_INVALID_SECOND			= 21003;//video_second不合法
    

    /**SVideo模块**/
    const SVideo_INVALID_CATEGORY_ID	= 20001;//category_id不合法
    
    
    /**公共模块**/
    const PUBLIC_INVALID_ID				= 22001;//ID不合法
    const PUBLIC_INVALID_TYPE			= 22002;//类型不合法
    const PUBLIC_NOT_EXIST_ITEM			= 22003;//内容不存在
    const PUBLIC_INVALID_CONTENT		= 22004;//内容不合法
    const PUBLIC_INVALID_PID			= 22001;//PID不合法

    /**推送模块**/
    const PUSH_DEVICES_TOKENS_NOT_EXIST_ITEM = 22101;
    const PUSH_INVALID_DEVICES_TOKENS        = 22102;
    const PUSH_INVALID_CALL_BACK             = 22103;

    /**租房模块**/
    const HOUSE_NOT_EXIST_TITLE                    = 22201;
    const HOUSE_NOT_EXIST_CONTENT                  = 22202;
    const HOUSE_INVALID_MOBILE                     = 22203;
    const HOUSE_NOT_EXIST_CONTACT_PERSON           = 22204;
    const HOUSE_NOT_EXIST_HOUSE_RESOURCE_ADDRESS   = 22205;
    const HOUSE_NOT_EXIST_MIN_LEASE_PERIOD         = 22206;
    const HOUSE_NOT_EXIST_RENT_AMOUNT              = 22207;
    const HOUSE_NOT_EXIST_CAN_RESIDE_TIME          = 22208;
    const HOUSE_NOT_EXIST_WEIXIN_NO                = 22209;
    const HOUSE_NOT_EXIST_EMAIL                    = 22210;
    const HOUSE_INVALID_ID                         = 22211;
    const HOUSE_NOT_EXIST_INFO                     = 22212;
    const HOUSE_INVALID_HOUSE_COMPLAINT_TYPE_ID    = 22213;
    const HOUSE_INVALID_SEARCH_KEY                 = 22214;
    const HOUSE_INVALID_CITY_ID                    = 22215;
    const HOUSE_CAN_NOT_COMPLAINT_YOURSELF         = 22216;
    const HOUSE_IS_NOT_APPROVED                    = 22217;
    const HOUSE_NOT_EXIST_IMAGE_FILES              = 22218;
    const HOUSE_NOT_EXIST_HOUSE_TYPE               = 22219;
    const HOUSE_NOT_EXIST_HOUSE_RESOURCE_DISTRICTS = 22220;
    const HOUSE_NOT_EXIST_HOUSE_RESOURCE_LONGITUDE = 22221;
    const HOUSE_NOT_EXIST_HOUSE_RESOURCE_LATITUDE  = 22223;

}