<?php 
use Org\Api\State;

function make_order(){
    return date('YmdHis'). substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 6);
}


/**
 * 数据签名
 * @access public
 */
function authSign($request , $key){
    if(!is_array($request)){
        throw new Exception("签名参数错误",State::EXCEPT_ERROR_ONE);
        exit();
    }
    unset($request["api_sign"]);
    $data = [];
    foreach($request as $k => $v){
    	$v = strval($v);
    	if (in_array($k, C('NOT_SIGN'))) {
    		continue;
    	}
        if($v != ''){
            $data[] = $v;
        }
    }
    if(count($data) > 0){
        $sortData = sort($data, 2);
        \Think\Log::write(print_r(implode("" , $data) . $key,true));
        return md5(implode("" , $data) . $key);
    }else{
        $sortData = "";
        return md5($sortData.$key);
    }
    
}

/**
 * 验证数字签名
 */
function sign_decrypt($request)
{
    if (!isset($request['api_sign'])) {
        throw new Exception("签名数据出错1",State::EXCEPT_ERROR_ONE);
        exit();
    }
    // 密钥验证
    $authSign = authSign($request, C("ENCRYPT"));
    if ($request['api_sign'] !== $authSign) {
        throw new Exception('签名数据出错2',State::EXCEPT_ERROR_TWO);
        exit();
    }
    return true;
}


///发送邮件
function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null){
    $config = C('THINK_EMAIL');
    Vendor('PHPMailer.PHPMailerAutoload');
    // vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
    $mail             = new PHPMailer(); //PHPMailer对象
    $mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();  // 设定使用SMTP服务
    $mail->SMTPDebug  = 0; // 关闭SMTP调试功能 // 1 = errors and messages// 2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    $mail->SMTPSecure = 'tsl';                 // 使用安全协议 QQ:tsl
    $mail->Host       = $config['SMTP_HOST'];  // SMTP 服务器
    $mail->Port       = $config['SMTP_PORT'];  // SMTP服务器的端口号
    $mail->Username   = $config['SMTP_USER'];  // SMTP服务器用户名
    $mail->Password   = $config['SMTP_PASS'];  // SMTP服务器密码
    $mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
    $replyEmail       = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
    $replyName        = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject    = $subject;
    $mail->AltBody    = "";
    $mail->MsgHTML($body);
    $mail->AddAddress($to, $name);
    if(is_array($attachment)){ // 添加附件
        foreach ($attachment as $file){
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return  $mail->Send() ? true : $mail->ErrorInfo;
}

/**
 * 单播推送
 * @param $params
 * @param $type
 * @throws Exception
 */
function umeng_push_unicast($params,$type)
{
    $data['device_tokens'] = !empty($params['device_tokens']) ? $params['device_tokens'] : '';
    if (empty($data['device_tokens'])) {//device_token为推送目标标志
        throw new Exception("device_tokens不能为空",State::PUSH_DEVICES_TOKENS_NOT_EXIST_ITEM);
        exit();
    }
    if (!is_string($data['device_tokens'])) {//device_token为字符串
        throw new Exception("device_tokens必须为字符串",State::PUSH_INVALID_DEVICES_TOKENS);
        exit();
    }
    $data['event'] = !empty($params['event']) ? $params['event'] : 1;
    $data['action'] = !empty($params['action']) ? $params['action'] : 1;
    $data['after_open'] = !empty($params['after_open']) ? $params['after_open'] : ''; //推送后续操作
    $data['description'] = !empty($params['description']) ? $params['description'] : ''; //描述
    if (!empty($params['start_time'])) {//开始时间，可不传，时间格式为时间戳
        $data['start_time'] = $params['start_time'];
        if (is_string($data['start_time'])) {
            $data['start_time'] = strtotime($data['start_time']);
        }
    }
    if (!empty($params['expire_time'])) {//过期时间，时间格式为时间戳
        $data['expire_time'] = $params['expire_time'];
        if (is_string($data['expire_time'])) {
            $data['expire_time'] = strtotime($data['expire_time']);
        }
    }

    Vendor('Umeng.Umeng');
    $config = C('UMENG');

    switch ($type) {
        case 'android':
            $data['platform'] = 'android'; //手机平台
            $data['ticker'] = !empty($params['ticker']) ? $params['ticker'] : ''; //通知栏提示语
            $data['title'] = !empty($params['title']) ? $params['title'] : ''; //标题
            $data['text'] = !empty($params['text']) ? $params['text'] : ''; //内容
            $data['url'] = !empty($params['url']) ? $params['url'] : ''; //跳转路径，after_open为go_url时必传
            $data['extra_key'] = !empty($params['extra_key']) ? $params['extra_key'] : []; //自定义参数键名，after_open为go_url时必传
            $data['extra_value'] = !empty($params['extra_value']) ? $params['extra_value'] : []; //自定义参数键值，after_open为go_url时必传
            //数据库字段名同步
            $data['keys'] = implode(',',$data['extra_key']);
            $data['values'] = implode(',',$data['extra_value']);
            $umeng = new Umeng($config['ANDROID_APPKEY'],$config['ANDROID_APPMASTERSECRET'],$data);
            $callback = $umeng->sendAndroidUnicast();
            break;
        case 'ios':
            $data['platform'] = 'ios';
            $data['alert'] = [ //通知消息集合
                'title' => !empty($params['title']) ? $params['title'] : '', //标题
                'body'  => !empty($params['text']) ? $params['text'] : '', //内容
            ];
            if (!empty($params['subtitle'])) { //副标题，可不传
                $data['alert']['subtitle'] = $params['subtitle'];
            }
            $data['custom_key'] = !empty($params['custom_key']) ? $params['custom_key'] : []; //自定义参数键名，after_open为go_url时必传
            $data['custom_value'] = !empty($params['custom_value']) ? $params['custom_value'] : []; //自定义参数键值，after_open为go_url时必传
            $data['content_link_key'] = !empty($params['content_link_key']) ? $params['content_link_key'] : ''; //内容链接键名，after_open为go_url时必传
            $data['content_link_value'] = !empty($params['content_link_value']) ? $params['content_link_value'] : ''; //内容链接键值，after_open为go_url时必传

            $data['keys'] = !empty($params['custom_key']) ? implode(',',$data['custom_key']) : '';
            $data['values'] = !empty($params['custom_value']) ? implode(',',$data['custom_value']) : '';
            $data['text'] = $data['body'];
            $umeng = new Umeng($config['IOS_APPKEY'],$config['IOS_APPMASTERSECRET'],$data);
            $callback = $umeng->sendIosUnicast();
            break;
    }

    //推送成功后回调数据插入
    if (!empty($callback)) {
        $callback = json_decode($callback);
        if ($callback->ret == 'SUCCESS') {

            $data['msg_id'] = !empty($callback->data->task_id) ? $callback->data->task_id : '';
            $data['add_time'] = $_SERVER['REQUEST_TIME'];
            D('UmengEventPush')->add($data);
        } else {
            throw new Exception("推送回调失败",State::PUSH_INVALID_CALL_BACK);
            exit();
        }
    }



}
?>