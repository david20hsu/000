<?php
session_start();
if(!isset($_SESSION["username"])){
   header("Location: login.php");
   exit(); 
}
require_once("../html/header.html");
require_once("../html/menu.html");
$msg="";    // 訊息

if (isset($_POST['submit'])) 
{
    $comp_idno=$_POST['comp_idno'];
    $yy=$_POST['yy'];
    $month=$_POST['month'];
    $xfile="B2C".$comp_idno.'_'.($yy-1911).$month.'H.csv';
    if(is_uploaded_file($_FILES['csv_file']['tmp_name'])){
       $filename = basename($_FILES['csv_file']['name']);
       if ($filename <>$xfile){
         $msg="上傳檔名:".$filename.' 與 正確檔名:'.$xfile. '..不一致';
      }else{
         $msg=upload_csv();    // 執行程式
       }
     }
}
function chk_key($sql){
    require('../include/config.php'); 
    $sth = $conn->prepare($sql);
    $sth->execute();
    $count = $sth->rowCount();
    return $count;
}
function upload_csv(){
    $tmpfile = $_FILES['csv_file']['tmp_name'];
    if (($fh = fopen($tmpfile, "r")) !== FALSE) {
        require('../include/config.php'); 
        $i = 0;     // 成功筆數
        $j=0;       // 失敗筆數
        while (($items = fgetcsv($fh, 10000, ",")) !== FALSE) {
            $row= mb_convert_encoding($items , "UTF-8", "BIG5");             //原始編碼為BIG5轉UTF-8
            $tax_id="";
            $orderCode=$row[2].'-'.($_POST['yy']-1911).$_POST['month'];      // 客戶代號 YYYMM
            $totalFee=$row[7];
            $sql="select tax_id from tax_b2c where tp='2' and orderCode='$orderCode' and totalFee='$totalFee'";
          //  echo $sql;
          //  exit();
            $rowcount=chk_key($sql);
            if ($rowcount==0){
                $data=array();
                $data = [
                    "uncode"=>$row[0],               // 賣方統編 企業統一編碼
                    "customerName"=> $row[2],        // 客戶代號 API文件(錯)..改放 載具
                    "phone"=>$row[8],                //載具 
                    "orderCode"=>$row[2].'-'.($_POST['yy']-1911).$_POST['month'],       // 客戶代號 YYYMM
                    "datetime"=>date('Y-m-d'),     //  發票日期
                    "email"=>$row[4],              // 客戶郵件
                    "state"=>$row[9],             //發票捐贈 無-0  捐贈-1*
                    "donationCode" =>$row[10],     // 捐贈碼（當發票捐贈為1是必填）
                    "totalFee"=>(string)(double)$row[7], //總價 , 預收金額
                    "content"=>"嘉南羊乳".$row[1],
                    "name"=>$row[5],
                    "money"=>$row[7],
                    "number"=>$row[6],
                    "mtel"=>$row[3],
                    "tax_id"=>$tax_id,
                    "tp"=>"2",
                    "comp_con"=>$row[11],
                    "edate"=>$row[12]
                   ]; 
        //           dh = "<賣方統編|<應收單號|<客戶代號|<手機號碼|<Email|<商品|數量|應收款|<載具|<捐贈|<愛心碼|<超商碼|<繳費期限"
        //         '            0         1        2         3     4      5    6     7     8    9     10     11       12
                  $cql = "insert into  tax_b2c(`uncode`,`customerName`,`orderCode`,`phone`,`datetime`,`email`,`state`,`donationCode`,`totalFee`,`content`,`name`,`money`,`number`,`mtel`,`tax_id`, `tp`,`comp_con`,`edate`)";
                  $cql = $cql." VALUES(:uncode,:customerName,:orderCode,:phone,:datetime,:email ,:state,:donationCode ,:totalFee,:content,:name,:money,:number,:mtel,:tax_id, :tp,:comp_con, :edate) ";
                  $stmt= $conn->prepare($cql);
                  $stmt->execute($data);
             $i+=1;
            }else{
             $j+=1;
          }
        }
        $msg="宅配應收B2C資料(CSV) 上傳 : 成功 ".$i." 筆, 失敗 ".$j." 筆";
    }else{
        $msg="CSV 檔案格式不正確,..請重新上傳";
    }
    return $msg;
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
<p align='center'><a href="../hrb2c_tax_list.php"  class="btn btn-info" role="button">返回</a> </p>
<?php
require("../html/footer.html");
?>
</body>
</html>