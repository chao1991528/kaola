<?php
/*
|--------------------------------------------------------------------------
| 友盟推送
|--------------------------------------------------------------------------
|
| @author hana
|
| @created at 2018-09-07
|
*/
namespace Think;
class UMengPush
{
    // Request url
    const REQUEST_URL = 'https://msgapi.umeng.com/api/send';
    const ANDROID_APP_KEY = '5b4fe350a40fa375440002aa';
    const ANDROID_APP_MASTER_SECRET = 'lqrlqqsoasekmdcknzu8pbt0jnyhjtho';
    const IOS_APP_KEY = '5b3cad29a40fa30c21000057';
    const IOS_APP_MASTER_SECRET = '3kbyln9yoasihiac1hmfuzr5xcehbtro';

    /**
     * 统一单播消息接口
     * 
     * @param  string   $device_token   设备token（iOS: 64位，android: 44位）
     * @param  string   $ticker         通知栏提示文字
     * @param  string   $title          通知标题
     * @param  string   $text           通知文字描述
     * @param  array    $policy         发送策略
     * @return boolean
     */
    public static function unicast($device_token, $ticker, $title, $text, $policy = [])
    {
        $device = strlen($device_token) == 44 ? 'android' : 'ios';
        return self::send($device, 'unicast', self::_createPayload($device, $ticker, $title, $text), $device_token, $policy);
    }

    /**
     * Android 广播消息
     * 
     * @param  string   $ticker    提示文字
     * @param  string   $title     标题
     * @param  string   $text      文字描述
     * @param  array    $policy    发送策略
     * @return boolean
     */
    public static function androidBroadcast($ticker, $title, $text, $policy = [])
    {
        return self::send('addroid', 'broadcast', self::_createPayload('android', $ticker, $title, $text), false, $policy);
    }

    /**
     * iOS 广播消息
     * 
     * @param  string   $title          标题
     * @param  string   $subtitle       小标题
     * @param  string   $body           内容
     * @param  array    $policy         发送策略
     * @return boolean
     */
    public static function iOSBroadcast($title, $subtitle, $body, $policy = [])
    {
        return self::send('ios', 'broadcast', self::_createPayload('ios', $title, $subtitle, $body), false, $policy);
    }

    /**
     * 发送
     *
     * @param  string    $device          设置：ios / android
     * @param  string    $type            消息发送类型,其值可以为: unicast-单播，listcast-列播，broadcast-广播
     * @param  array     $payload         具体消息内容
     * @param  string    $device_tokens   当type为unicast时, 表示指定的单个设备，当type为listcast时, 要求不超过500个, 以英文逗号分隔
     * @param  array     $policy          发送策略，格式：[
     *                                                      'start_time': '2013-10-29 12:00:00',    // 定时发送
     *                                                      'expire_time": "2013-10-30 12:00:00'    // 过期时间
     *                                                  ]
     * @return boolean
     */
    public static function send($device, $type, $payload, $device_tokens = false, $policy = [])
    {;
        $params = [
            'appkey'    => $device == 'ios' ? self::IOS_APP_KEY : self::ANDROID_APP_KEY,
            'timestamp' => $_SERVER['REQUEST_TIME'],
            'type'      => $type,
            'payload'   => $payload
        ];
        $device_tokens && $params['device_tokens'] = $device_tokens;
        $policy        && $params['policy']        = $policy;


       if ($device == 'ios')
        {
            $params['sound']="default";
            $params['badge']="1";
            // iOS 测试证书需要加上这一行
          //  $params['production_mode'] = "false";
        }

        $sign = self::_createSignature($device, $params);

        $url = self::REQUEST_URL . '?sign=' . $sign;
        //return $url.json_encode($params);
        $response = self::httpResponseWithPost($url, $params);
        $result = json_decode($response);

        return $result->ret == 'SUCCESS' ? true : false;
    }

    /**
     * 生成载荷
     * 
     * @param  string   $device   设置： iOS / Android
     * @param  string   $ticker   通知栏提示文字
     * @param  string   $title    标题
     * @param  string   $text     内容
     * @return array
     */
    protected static function _createPayload($device, $ticker, $title, $text)
    {
        switch ($device)
        {
            case 'ios':
                return [
                    'aps' => [
                        'alert' => [
                            'title'    => $ticker,
                            'subtitle' => $title,
                            'body'     => $text
                        ],
                        'sound'=>'default'
                    ]
                ];
                break;

            case 'android':
                return [
                    'display_type' => 'notification',
                    'body' => [
                        'ticker' => $ticker,
                        'title'  => $title,
                        'text'   => $text
                    ]
                ];
                break;
            
            default:

                return [];
        }
    }

    /**
     * 生成签名
     * 
     * @param  array   $params  请求参数
     * @return string
     */
    protected static function _createSignature($device, $params)
    {
        $str = 'POST' . self::REQUEST_URL . json_encode($params) . ($device == 'ios' ? self::IOS_APP_MASTER_SECRET : self::ANDROID_APP_MASTER_SECRET);
        return md5($str);
    }

    /**
     * HTTP 请求 POST 模式
     *
     * @param  string   $url     请求地址
     * @param  string   $params  请求参数
     * @return mixed
     */
    public static function httpResponseWithPost($url, $params)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $responseText = curl_exec($curl);

        curl_close($curl);

        return $responseText;
    }
}