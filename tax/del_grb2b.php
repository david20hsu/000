<?php
session_start();

if(!isset($_SESSION["username"])){
   header("Location: ../login.php");
   exit(); 
}
require_once("../html/header.html");
require_once("../html/menu.html");
$msg="";  
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
    return $return_content;
}
function msectime()
 {
     list($msec, $sec) = explode(' ', microtime());
     $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
     return $msectime;
 }
if (isset($_GET)) {
    $tax_id = $_GET['tax_id']; // 發票號碼
    $uncode = $_GET['uncode']; // 賣方統編
    $sql="select a.*,b.api_sign,b.api_idno from tax_b2b a left join comp b on a.uncode=b.comp_idno where a.tp='3' and uncode='$uncode' and a.tax_id='$tax_id'";
    require('../include/config.php'); 
    $sth = $conn->prepare($sql);
    $sth->execute();
    $Rowcount=$sth->rowCount();
    if ($Rowcount>0){
        $row=$sth->fetch();
        $sing= strtoupper(md5((string)msectime().$row['api_idno'].$row['api_sign']));
        $json_data=array();//create the array  
        $json_data=[
            "timeStamp"=>(string)msectime(), //當前時間毫秒數（五分鐘內有效）
            "uncode"=>$row['uncode'],      // $row[0]; // 賣方統編 企業統一編碼
            "idno"=>$row['api_idno'],    // 登入身份證號碼
            "sign"=>$sing,
            "code"=>$row['tax_id'],
            "remark"=>"資料有誤，作廢"
        ];
    
        $data_string = json_encode($json_data,JSON_UNESCAPED_UNICODE);
        $json= post_json_data('https://www.giveme.com.tw/invoice.do?action=cancelInvoice', $data_string);
        $obj = json_decode($json);
        if ($obj->{'success'}=='true'){
            $tax_id= $obj->{'code'};
            $data = [
                'uncode'=>$row['uncode'], 
                'tax_id'=>$tax_id,
                'content'=>"資料有誤，作廢",
                'tax_status'=>"1",
                'edate'=>date('Y-m-d'),
                'tp' =>'3',
                "orderCode"=>$row['orderCode']
            ]; 
            $cql = "UPDATE `tax_b2b` SET `tax_id`=:tax_id,`content`=:content,`tax_status`=:tax_status,`edate`=:edate where `uncode`=:uncode and  `tp`=:tp  and `orderCode`=:orderCode  and `totalFee`=:totalFee and `tax_status`='0' ";
            $stmt = $conn->prepare($cql); 
            $stmt->execute($data);
            $msg="團戶應收B2B發票:".$tax_id.',作廢成功';  
        }else{
            $msg="團戶應收B2B發票:".$tax_id.',作廢失敗';  
        }
    }

}
?>
<p></p>
<div class='err'>
<p align='center'><span style='font-size:20px;'>
   <?php
     if($msg<>''){
       echo $msg;
     }
   ?>
</span></p>
</div>
<p></p>
  <p align='center'><a href="../grb2b_tax_list.php"  class="btn btn-info" role="button">返回</a> </p>
  <?php
  require("../html/footer.html");
  ?>
  </body>
  </html>