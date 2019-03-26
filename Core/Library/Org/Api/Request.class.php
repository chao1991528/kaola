<?php
/**
 * Created by PhpStorm.
 * User: lizhiyong
 * Date: 2018/1/14
 * Time: 下午1:38
 */
namespace Org\Api;

class Request{
    /**
     * get请求
     * @param type $url
     * @param type $timeout
     * @param type $ssl
     * @param type $ipv4
     * @return type
     */
    public static function get($url, $timeout = 5, $ssl = false, $ipv4 = false){
        $result = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); //设置访问的url地址
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);  //设置超时
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //返回结果
        $ssl or curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $ipv4 && curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result['data'] = curl_exec($ch);
        if($errno = curl_errno($ch)){ //返回CURL错误信息
            $error = curl_error($ch);
            $result['error'] = ['errno' => $errno, 'msg' => $error];
        }
        curl_close($ch);
        return $result;
    }


    /**
     * 发送post请求
     * @param $post_data
     * @param string $url
     * @param bool $postOrg
     * @param bool $forceIPV4
     * @return string
     */
    public static function post($url, $post_data,$timeout = 5, $ssl = false, $ipv4 = false){
        if(is_array($post_data)){
            $post_data = http_build_query($post_data);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);  //设置超时
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ssl or curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $ipv4 && curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result['data'] = curl_exec($ch);
        //var_dump($result['data']);exit;
        if($errno = curl_errno($ch)){ //返回CURL错误信息
            $error = curl_error($ch);
            $result['error'] = ['errno' => $errno, 'msg' => $error];
        }
        curl_close($ch);
        return $result;
    }

}
