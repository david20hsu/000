<?php
session_start();
if(!isset($_SESSION["username"])){
   header("Location: login.php");
   exit(); 
}
require_once("../html/header.html");
require_once("../html/menu.html");
$msg="";
if (isset($_POST['submit'])) 
{
    $yy=$_POST['yy'];
    $month=$_POST['month'];
    $comp_idno=$_POST['comp_idno'];
    $xfile ='B2B'.$comp_idno.'_'.($yy-1911).$month.'G.csv';
    if(is_uploaded_file($_FILES['csv_file']['tmp_name'])){
       $filename = basename($_FILES['csv_file']['name']);
       if ($filename <> $xfile){
         $msg="上傳檔名:".$filename.' 與 正確檔名:'.$xfile. '..不一致';
       }else{
         $msg= upload_csv();    // 執行程式
      }
    }
}
function chk_key($sql){
   require('../include/config.php'); 
   $sth = $conn->prepare($sql);
   $sth->execute();
   $count = $sth->rowCount();
   $conn=null;
   return $count;
 }
function upload_csv(){
    $tmpfile = $_FILES['csv_file']['tmp_name'];
    if (($fh = fopen($tmpfile, "r")) !== FALSE) {
      require('../include/config.php'); 
      $i=0;
      $j=0;
      while (($items = fgetcsv($fh, 10000, ",")) !== FALSE) {
        $row= mb_convert_encoding($items , "UTF-8", "BIG5");           //原始編碼為BIG5轉UTF-8
        $tax_id="";
        $orderCode=$row[1];       // 編號載具 --Key 已經轉好奶區-YYYMM國曆
        $totalFee=$row[7];                                        
        $sql="select tax_id from tax_b2b where tp='3' and orderCode='$orderCode' and totalFee='$totalFee' ";
        $rowcount=chk_key($sql);
        if ($rowcount==0){
           $data=array();
           $data = [
            'uncode'=>$row[0],                      // 賣方統編 企業統一編碼
            'customerName' => $row[2],              // 客戶代號
            "phone"=>$row[8],                       // 客戶統一編碼 
            "orderCode"=>$row[1],                   // 編號載具 --Key
            "datetime"=>date('Y-m-d'),              //  發票日期
            "email"=>$row[4],                       // 客戶郵件
            "taxState"=>$row[9],                    //
            "totalFee"=>(string)(double)$row[7],    //總價 , 預收金額
            "amount"=>(string)(double)$row[10],     //稅額
            "sales"=>(string)(double)$row[11],      //銷售額(未稅)
            "content"=>"嘉南羊乳".$row[1],
            "name"=>$row[5],    // 品名 "乳品"
            "money"=>round($row[7],0),   // 單價 $row[11]
            "number"=>$row[6],  // 數量
            "tax_id"=>$tax_id,        
            "tp"=>"3"
         ]; 
//                  dh = "<賣方統編|<應收單號|<團戶區號|<手機號碼|<Email|<商品|數量|應收款|<買方統編|<稅別|稅額|銷售額"
//                  '          0          1       2      3        4      5    6    7       8      9   10   11
         $cql = "insert into  tax_b2b(`uncode`,`customerName`,`phone`,`orderCode`,`datetime`,`email`,`taxState`,`totalFee`,`amount`,`sales`,`content` ,`name`,`money`,`number`,`tax_id`, `tp`)";
         $cql = $cql." VALUES(:uncode,:customerName,:phone,:orderCode,:datetime,:email ,:taxState ,:totalFee,:amount,:sales,:content,:name,:money,:number,:tax_id, :tp) ";                
         $stmt= $conn->prepare($cql);
         $stmt->execute($data);
           $i+=1;
        }else{
           $j+=1;
        }
      }
      $msg="團戶應收B2B資料(CSV) 上傳 : 成功 ".$i." 筆, 失敗 ".$j." 筆";
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
<p align='center'><a href="../grb2b_tax_list.php"  class="btn btn-info" role="button">返回</a> </p>
<?php
require("../html/footer.html");
?>
</body>
</html>