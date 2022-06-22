<?php
/*
* post 傳送JSON 格式資料
* @param $url string URL
* @param $data_string string 請求的具體內容
* @return array
* code 狀態碼
* result 返回結果
*/
function post_json_data($url, $data_string) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json; charset=utf-8',
'Content-Length: ' . strlen($data_string))
);
ob_start();
curl_exec($ch);
$return_content = ob_get_contents();
ob_end_clean();
$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
return array('code'=>$return_code, 'result'=>$return_content);
}
$arr = array('a'=>'00A','b'=>'中文');
var_dump(post_json_data('http://localhost/000/receive.php', json_encode($arr,JSON_UNESCAPED_UNICODE)));
?>