<?php
/**
 * Created by PhpStorm.
 * User: peiyu
 * Date: 2018/10/11
 * Time: 15:06
 */

//use app\common\library\notification\android\AndroidBroadcast;

//use app\common\library\notification\android\AndroidListcast;
//use app\common\library\notification\android\AndroidUnicast;
//use app\common\library\notification\ios\IOSBroadcast;
//use app\common\library\notification\ios\IOSUnicast;
use think\Log;

require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidBroadcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidFilecast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidGroupcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidUnicast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidCustomizedcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSBroadcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSFilecast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSGroupcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSUnicast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSCustomizedcast.php');

/**
 * 友盟推送 TODO：还未测试
 * @ApiInternal
 */
class Umeng
{
    protected $appkey           = NULL;
    protected $appMasterSecret  = NULL;
    protected $timestamp        = NULL;
    protected $validation_token = NULL;
    protected $production_mode  = true;
    protected $start_time  = NULL;
    protected $expire_time  = NULL;
    protected $ticker  = NULL;
    protected $description  = NULL;
    protected $title  = NULL;
    protected $text  = NULL;
    protected $after_open  = NULL;
    protected $url  = NULL;
    protected $extra_key  = NULL;
    protected $extra_value  = NULL;
    protected $device_tokens  = NULL;
    protected $custom_key  = NULL;
    protected $custom_value  = NULL;
    protected $alert  = NULL;
    protected $content_link_key  = NULL;
    protected $content_link_value  = NULL;
    protected $image  = NULL;
    protected $custom  = 1;

    public function __construct($appkey,$appMasterSecret,$params)
    {
        $this->appkey = $appkey;
        $this->appMasterSecret = $appMasterSecret;
        $this->huaweiPkg = 'com.kaola.news.ui.AppStartActivity';
        $this->timestamp = strval(time());
        $this->start_time = !empty($params['start_time']) ? $params['start_time'] : 0;
        $this->expire_time = !empty($params['expire_time']) ? $params['expire_time'] : 0;
        $this->ticker = !empty($params['ticker']) ? $params['ticker'] : '';
        $this->description = !empty($params['description']) ? $params['description'] : '';
        $this->title = !empty($params['title']) ? $params['title'] : '';
        $this->text = !empty($params['text']) ? $params['text'] : '';
        $this->after_open = !empty($params['after_open']) ? $params['after_open'] : 0;
        $this->url = !empty($params['url']) ? $params['url'] : 0;
        $this->extra_key = !empty($params['extra_key']) ? $params['extra_key'] : 0;
        $this->extra_value = !empty($params['extra_value']) ? $params['extra_value'] : 0;
        $this->device_tokens = !empty($params['device_tokens']) ? $params['device_tokens'] : 0;
        $this->alert = !empty($params['alert']) ? $params['alert'] : [];
        $this->custom_key = !empty($params['custom_key']) ? $params['custom_key'] : [];
        $this->custom_value = !empty($params['custom_value']) ? $params['custom_value'] : [];
        $this->content_link_key = !empty($params['content_link_key']) ? $params['content_link_key'] : 0;
        $this->content_link_value = !empty($params['content_link_value']) ? $params['content_link_value'] : 0;
        $this->image = !empty($params['image']) ? $params['image'] : '';
    }

    public function sendAndroidBroadcast()
    {
        try {
            $brocast = new AndroidBroadcast();
            $brocast->setAppMasterSecret($this->appMasterSecret);
            $brocast->setPredefinedKeyValue("appkey",           $this->appkey);
            $brocast->setPredefinedKeyValue("mipush",           true);
            $brocast->setPredefinedKeyValue("mi_activity",      $this->huaweiPkg);
            $brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);
            $brocast->setPredefinedKeyValue("description",      $this->description);
            $brocast->setPredefinedKeyValue("ticker",           $this->ticker);
            $brocast->setPredefinedKeyValue("title",            $this->title);
            $brocast->setPredefinedKeyValue("text",             $this->text);
            $brocast->setPredefinedKeyValue("after_open",       $this->after_open);
            // Set 'production_mode' to 'false' if it's a test device.
            // For how to register a test device, please see the developer doc.
            $brocast->setPredefinedKeyValue("production_mode", $this->production_mode);
            if (!empty($this->after_open) && $this->after_open == 'go_custom') {
                $brocast->setPredefinedKeyValue("custom", $this->custom);
            }
            if (!empty($this->url)) {
                $brocast->setPredefinedKeyValue("url", $this->url);
            }
            if (!empty($this->start_time)) {
                $brocast->setPredefinedKeyValue("start_time", $this->start_time);
            }
            if (!empty($this->expire_time)) {
                $brocast->setPredefinedKeyValue("expire_time", $this->expire_time);
            }
            // [optional]Set extra fields
            if (!empty($this->extra_key) && !empty($this->extra_value)) {
                $extraKey = $this->extra_key;
                $extraValue = $this->extra_value;
                foreach ($extraKey as $key=>$item) {
                    $brocast->setExtraField($item, $extraValue[$key]);
                }
            }

//            print("Sending broadcast notification, please wait...\r\n");
            return $brocast->send();
//            print("Sent SUCCESS\r\n");
        } catch (\Exception $e) {
            print("Caught exception: " . $e->getMessage());
        }
    }

    public function sendAndroidListcast($deviceToken)
    {
        try {
            $brocast = new AndroidListcast();
            $brocast->setAppMasterSecret($this->appMasterSecret);
            $brocast->setPredefinedKeyValue("appkey",           $this->appkey);
            $brocast->setPredefinedKeyValue("device_tokens",    $deviceToken);
            $brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);
            $brocast->setPredefinedKeyValue("description",      $this->description);
            $brocast->setPredefinedKeyValue("ticker",           $this->ticker);
            $brocast->setPredefinedKeyValue("title",            $this->title);
            $brocast->setPredefinedKeyValue("text",             $this->text);
            $brocast->setPredefinedKeyValue("after_open",       $this->after_open);
            // Set 'production_mode' to 'false' if it's a test device.
            // For how to register a test device, please see the developer doc.
            $brocast->setPredefinedKeyValue("production_mode", $this->production_mode);
            if (!empty($this->url)) {
                $brocast->setPredefinedKeyValue("url", $this->url);
            }
            if (!empty($this->start_time)) {
                $brocast->setPredefinedKeyValue("start_time", $this->start_time);
            }
            if (!empty($this->expire_time)) {
                $brocast->setPredefinedKeyValue("expire_time", $this->expire_time);
            }
            // [optional]Set extra fields
            if (!empty($this->extra_key) && !empty($this->extra_value)) {
                $extraKey = $this->extra_key;
                $extraValue = $this->extra_value;
                foreach ($extraKey as $key=>$item) {
                    $brocast->setExtraField($item, $extraValue[$key]);
                }
            }

//            print("Sending broadcast notification, please wait...\r\n");
            return $brocast->send();
//            print("Sent SUCCESS\r\n");
        } catch (\Exception $e) {
            print("Caught exception: " . $e->getMessage());
        }
    }

    public function sendAndroidUnicast()
    {
        try {
            $unicast = new AndroidUnicast();
            $unicast->setAppMasterSecret($this->appMasterSecret);
            $unicast->setPredefinedKeyValue("appkey",           $this->appkey);
            $unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);
            $unicast->setPredefinedKeyValue("mipush",           true);
            $unicast->setPredefinedKeyValue("mi_activity",      $this->huaweiPkg);
            // Set your device tokens here
            $unicast->setPredefinedKeyValue("device_tokens",    $this->device_tokens);
            $unicast->setPredefinedKeyValue("ticker",           $this->ticker);
            $unicast->setPredefinedKeyValue("description", $this->description);
            $unicast->setPredefinedKeyValue("title",            $this->title);
            $unicast->setPredefinedKeyValue("text",             $this->text);
            $unicast->setPredefinedKeyValue("after_open",       $this->after_open);
            if (!empty($this->after_open) && $this->after_open == 'go_custom') {
                $unicast->setPredefinedKeyValue("custom", $this->custom);
            }
            if (!empty($this->start_time)) {
                $unicast->setPredefinedKeyValue("start_time", $this->start_time);
            }
            if (!empty($this->expire_time)) {
                $unicast->setPredefinedKeyValue("expire_time", $this->expire_time);
            }
            // Set 'production_mode' to 'false' if it's a test device.
            // For how to register a test device, please see the developer doc.
            $unicast->setPredefinedKeyValue("production_mode", $this->production_mode);
            if (!empty($this->url)) {
                $unicast->setPredefinedKeyValue("url", $this->url);
            }
            // [optional]Set extra fields
            if (!empty($this->extra_key) && !empty($this->extra_value)) {
                $extraKey = $this->extra_key;
                $extraValue = $this->extra_value;
                foreach ($extraKey as $key=>$item) {
                    $unicast->setExtraField($item, $extraValue[$key]);
                }
            }
//            print("Sending unicast notification, please wait...\r\n");
            return $unicast->send();
//            print("Sent SUCCESS\r\n");
        } catch (\Exception $e) {
            print("Caught exception: " . $e->getMessage());
        }
    }

    public function sendIOSBroadcast()
    {
        try {
            $brocast = new IOSBroadcast();
            $brocast->setAppMasterSecret($this->appMasterSecret);
            $brocast->setPredefinedKeyValue("appkey",           $this->appkey);
            $brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);

            $brocast->setPredefinedKeyValue("alert", $this->alert);
            $brocast->setPredefinedKeyValue("badge", 0);
            $brocast->setPredefinedKeyValue("sound", "chime");
            $brocast->setPredefinedKeyValue("description", $this->description);
            // Set 'production_mode' to 'true' if your app is under production mode
            $brocast->setPredefinedKeyValue("production_mode", $this->production_mode);
            if (!empty($this->start_time)) {
                $brocast->setPredefinedKeyValue("start_time", $this->start_time);
            }
            if (!empty($this->expire_time)) {
                $brocast->setPredefinedKeyValue("expire_time", $this->expire_time);
            }
            if (!empty($this->image)) {
                $brocast->setPredefinedKeyValue("mutable-content", 1);
                $brocast->setPredefinedKeyValue("image", $this->image);
            }
            if (!empty($this->content_link_key) && !empty($this->content_link_value)) {
                $brocast->setPredefinedKeyValue($this->content_link_key, $this->content_link_value);
                if (!empty($this->custom_key) && !empty($this->custom_value)) {
                    // Set customized fields
                    $customValue = $this->custom_value;
                    foreach ($this->custom_key as $key=>$item) {
                        $brocast->setCustomizedField($item,$customValue[$key]);
                    }
                }
            }
            return $brocast->send();
        } catch (\Exception $e) {
            print("Caught exception: " . $e->getMessage());
        }
    }

    public function sendIOSUnicast()
    {
        try {
            $unicast = new IOSUnicast();
            $unicast->setAppMasterSecret($this->appMasterSecret);
            $unicast->setPredefinedKeyValue("appkey",           $this->appkey);
            $unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);
            // Set your device tokens here
            $unicast->setPredefinedKeyValue("device_tokens",    $this->device_tokens);
            $unicast->setPredefinedKeyValue("alert", $this->alert);
            $unicast->setPredefinedKeyValue("description", $this->description);
            $unicast->setPredefinedKeyValue("badge", 0);
            $unicast->setPredefinedKeyValue("sound", "chime");
            // Set 'production_mode' to 'true' if your app is under production mode
            $unicast->setPredefinedKeyValue("production_mode", $this->production_mode);
            if (!empty($this->start_time)) {
                $unicast->setPredefinedKeyValue("start_time", $this->start_time);
            }
            if (!empty($this->expire_time)) {
                $unicast->setPredefinedKeyValue("expire_time", $this->expire_time);
            }
            if (!empty($this->image)) {
                $unicast->setPredefinedKeyValue("mutable-content", 1);
                $unicast->setPredefinedKeyValue("image", $this->image);
            }
            if (!empty($this->custom_key) && !empty($this->custom_value)) {
                // Set customized fields
                $customValue = $this->custom_value;
                foreach ($this->custom_key as $key=>$item) {
                    $unicast->setCustomizedField($item,$customValue[$key]);
                }
            }
            return $unicast->send();
        } catch (\Exception $e) {
            print("Caught exception: " . $e->getMessage());
        }
    }
}