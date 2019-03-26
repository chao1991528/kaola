<?php 

function alert_mes($str){
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo " <script type='text/javascript'>alert('$str');history.go(-1);</script>";
	die();
}


function refresh_alert_mes($str){
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo " <script type='text/javascript'>alert('$str'); location.reload();</script>";
	die();
}


function href_alert_mes($str,$url){
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo " <script type='text/javascript'>alert('$str'); location.href='$url';</script>";
	die();
}


function getRandom($len=4,$str=''){
	$rstr = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$rstr = empty($str) ? $rstr : $str;
	$rand_str = '';
	$rstr_len = strlen($rstr);
	for($i=0;$i<$len;$i++){
		$index = mt_rand(0, $rstr_len);
		$rand_str .= substr($rstr, $index,1);
	}
	return $rand_str;
}


function license(){
	return true;
	if (time() > 1504774800){
		return false;
	}
}

function build_order_no()
{
	return date('YmdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 4);
}

//获取系统是间 格式2016-8-19 10:20:34
function get_now_date(){
	return date('Y-m-d H:i:s',NOW_TIME);
}


function sub_date($date){
	if (empty($date)) return '';
	if (is_numeric($date)) {
		return date('Y-m-d', $date);
	}
	return date('Y-m-d', strtotime($date));
}
function sub_date_all($date){
	if (empty($date)) return '';
	if (is_numeric($date)) {
		return date('Y-m-d H:i:s', $date);
	}
	return date('Y-m-d H:i:s', strtotime($date));
}
function sub_time($date){
    if (empty($date)) return '';
	if (is_numeric($date)) {
		return date('H:i:s', $date);
	}
    return date('H:i:s', strtotime($date));
}

function second_format($second){
	if (empty($second) || $second == 0) {
		return '00:00';
	}
	$str = floor($second/60);
	if (strlen($str) < 2) {
		$str = '0'.$str;
	}
	$str2 = $second - floor($second/60) * 60;
	if (strlen($str2) < 2) {
		$str2 = '0'.$str2;
	}
	return $str . ':' . $str2;
}

function format_price($price){
	if (is_numeric($price) && substr($price, -2) == '00') {
		return intval($price);
	}
	else {
		return $price;
	}
}

function sub_picture($picture){
	if (empty($picture)) return '';
	$arr = explode(',', $picture);
	if (sizeof($arr) == 1) {
		return $picture;
	}
	else{
		return $arr[0];
	}
}

/**
 * 导出CSV文件
 * @param array $data        数据
 * @param array $header_data 首行数据
 * @param string $file_name  文件名称
 * @return string
 */
function export_csv_1($data = array(), $header_data = array(), $file_name = '')
{
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $file_name);
    if (!empty($header_data)) {
        echo iconv('utf-8','gbk//TRANSLIT','"'.implode('","',$header_data).'"'."\n");
    }
    foreach ($data as $key => $value) {
        $output = array();
        $output[] = $value['id'];
        $output[] = $value['name'];
        echo iconv('utf-8','gbk//TRANSLIT','"'.implode('","', $output)."\"\n");
    }
}
/**
 * 导出CSV文件
 * @param array $data        数据
 * @param array $header_data 首行数据
 * @param string $file_name  文件名称
 * @return string
 */
function export_csv_2($data = array(), $header_data = array(), $file_name = '')
{
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename='.$file_name);
    header('Cache-Control: max-age=0');
    $fp = fopen('php://output', 'a');
    if (!empty($header_data)) {
        foreach ($header_data as $key => $value) {
            $header_data[$key] = iconv('utf-8', 'gbk', $value);
        }
        fputcsv($fp, $header_data);
    }
    $num = 0;
    //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
    $limit = 100000;
    //逐行取出数据，不浪费内存
    $count = count($data);
    if ($count > 0) {
        for ($i = 0; $i < $count; $i++) {
            $num++;
            //刷新一下输出buffer，防止由于数据过多造成问题
            if ($limit == $num) {
                ob_flush();
                flush();
                $num = 0;
            }
            $row = $data[$i];
            foreach ($row as $key => $value) {
                $row[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $row);
        }
    }
    fclose($fp);
}


function lr_replace($param){
    return str_replace('"',"'",$param);
}

function get_user_name($uid){
	if ($uid == 0) {
		return "";
	}
	$info = D('User')->where("id='{$uid}'")->find();
	if (!$info){
		return "";
	}
	return $info['user_number'];
}

function get_member_loginname($uid){
	if ($uid == 0) {
		return "";
	}
    $info = D('MemberLogin')->where("id='{$uid}'")->find();
	if (!$info){
		return "";
	}
    return $info['loginname'];
}
function get_member_logintype($uid){
	$login_type = C('LOGIN_TYPE');
	$info = D('MemberLogin')->where("id='{$uid}'")->find();
	if (!$info){
		return "";
	}
	return $login_type[$info['login_type']];
}
function get_member_nickname($uid){
    $info = D('Member')->where("id='{$uid}'")->find();
	if (!$info){
		return "";
	}
    return $info['nick_name'];
}
function get_member_headpic($uid){
    $info = D('Member')->where("id='{$uid}'")->find();
	if (!$info){
		return "";
	}
    return $info['headpic'];
}

function genrate_umeng_sign($body,$url,$appMasterSecret)
{
	return MD5('POST'.$url.$body.$appMasterSecret);
}

function umeng_curl($url,$body)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body );
	$result = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curlErrNo = curl_errno($ch);
	$curlErr = curl_error($ch);
	curl_close($ch);
	//    print($result . "\r\n");
	//
	//    if ($httpCode == "0") {
	//         // Time out
	//        throw new \Exception("Curl error number:" . $curlErrNo . " , Curl error details:" . $curlErr . "\r\n");
	//    } else if ($httpCode != "200") {
	//        // We did send the notifition out and got a non-200 response
	//        throw new \Exception("Http code:" . $httpCode .  " details:" . $result . "\r\n");
	//    } else {
	//        return $result;
	//    }
    return $result;
}

function http_request($url){
	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL,$url);
    	curl_setopt($ch, CURLOPT_HEADER,0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//禁止调用时就输出获取到的数据
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
    	$result = curl_exec($ch);
    	curl_close($ch);
    	return $result;
}

?>