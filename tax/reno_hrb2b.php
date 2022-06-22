<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: ../login.php");
  exit();
}
ini_set("max_execution_time", "6000");
$title = "嘉南羊乳-宅配應收B2B(3聯)取號";
require_once("../html/header.html");
require_once("../html/menu.html");
$msg = "";    // 訊息
$i = 0;     // 成功筆數
$j = 0;       // 失敗筆數
if (isset($_POST['submit'])) {
  $comp_idno = $_POST['comp_idno'];
  $listno = $_POST['listno'];
  if ($listno == 0) {
    $listno = 10;
  }
  $yy = $_POST['yy'];
  $month = $_POST['month'];
  $ym = $yy . '-' . $month . '-%';
  if ($comp_idno != '' && $yy != '' && $month != '') {
    $sql = "select a.*,b.api_idno,api_sign from tax_b2b a left join comp b on a.uncode=b.comp_idno where a.uncode='$comp_idno' and a.datetime like '$ym' and a.tp='2' and a.tax_status='0' and  (a.tax_id='' or a.tax_id is null)  limit $listno ";

    $msg = Rebud_json($sql);
  } else {
    $msg = "請正確點選 公司及輸入，年度、月份";
  }
}
function post_json_data($url, $data_string)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    array(
      'Content-Type: application/json; charset=utf-8',
      'Content-Length: ' . strlen($data_string)
    )
  );
  ob_start();
  curl_exec($ch);
  $return_content = ob_get_contents();
  ob_end_clean();
  $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  // return array($return_code, $return_content);
  return $return_content;
}

function msectime()
{
  list($msec, $sec) = explode(' ', microtime());
  $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
  return $msectime;
}
function Rebud_json($sql)
{
  $i = 0;
  $j = 0;
  require('../include/config.php');
  $sth = $conn->prepare($sql);
  $sth->execute();
  while ($row = $sth->fetch()) {
    $json_data = array(); //create the array  
    $sing = strtoupper(md5((string)msectime() . $row['api_idno'] . $row['api_sign']));
    //$sing =strtoupper(md5((string)msectime().'H127633930'.'96kf28')); 
    $json_data = [
      "timeStamp" => (string)msectime(), //當前時間毫秒數（五分鐘內有效）
      "uncode" => $row['uncode'],      // $row[0]; // 賣方統編 企業統一編碼
      "idno" => $row['api_idno'],    // 登入身份證號碼
      // "uncode"=>"53418005",       // $row[0]; // 賣方統編 企業統一編碼
      // "idno"=>"H127633930",       // 登入身份證號碼
      "sign" => $sing,
      "customerName" => $row['customerName'],    // 客戶代號
      "phone" => $row['phone'],                 //載具 
      "datetime" => date('Y-m-d'),              //  發票日期
      "email" => $row['email'],                // 客戶郵件
      "taxState" => $row['taxState'],           //發票捐贈 無-0  捐贈-1*
      "totalFee" => (string)$row['totalFee'],  //總價 , 應收金額
      "amount" => (string)$row['amount'],      //稅額
      "sales" => (string)$row['sales'],        //稅額
      "content" => $row['content'],
      "items" => [
        [
          "name" => $row['name'],
          "money" => (float)$row['money'],
          "number" => $row['number']
        ]
      ]
    ];
    $tax_id = "";
    $data_string = json_encode($json_data, JSON_UNESCAPED_UNICODE);
    $json = post_json_data('https://www.giveme.com.tw/invoice.do?action=addB2B', $data_string);
    $obj = json_decode($json);
    if ($obj->{'success'} == 'true') {
      $tax_id = $obj->{'code'};
      $i += 1;
    } else {
      $j += 1;
    }
    $data = [
      'tax_id' => $tax_id,
      'tax_date' => date('Y-m-d'),
      'tp' => '2',
      "orderCode" => $row['orderCode'],
      "totalFee" => (string)$row['totalFee']
    ];
    $cql = "UPDATE `tax_b2b` SET `tax_id`=:tax_id,`datetime`=:tax_date where `tp`=:tp  and `orderCode`=:orderCode and `totalFee`=:totalFee";
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
    $msg = "宅配應收B2B重傳(取號): " . $i . ' 筆, 失敗: ' . $j . ' 筆';
  }
  if (isset($msg)) {
    return $msg;
  }
}
?>
<p></p>
<div class='err'>
  <p align='center'><span style='font-size:20px;'>
      <?php
      if (isset($msg) && $msg <> '') {
        echo $msg;
      }
      ?>
    </span></p>
</div>
<p></p>
<p align='center'><a href="../hrb2b_tax_list.php" class="btn btn-info" role="button">返回</a> </p>
<?php
require("../html/footer.html");
?>
</body>

</html>