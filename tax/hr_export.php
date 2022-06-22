<?php
session_start();
if(!isset($_SESSION["username"])){
   header("Location: login.php");
   exit(); 
}
$inv_date  =$_POST['inv_date'];
$filename='HR-'.(substr($inv_date,0,4)-1911).substr($inv_date,5,2).substr($inv_date,-2).'.txt';
//echo $filename;
require('../include/config.php'); 
$delimiter = ","; 
$f = fopen('php://memory', 'w'); 
$tp='2';
$i=0;
$content="";
$sql="Select customerName,content,tax_id,datetime,tax_status from tax_b2b where tp='$tp' and (datetime='$inv_date'  or (tax_status='1' and edate='$inv_date')) ";
$sth = $conn->prepare($sql);
$sth->execute();
while ($row= $sth->fetch()){
      $content.=$row['customerName'].",".substr($row['content'],-7).",".$row['tax_id'].",".$row['datetime'].",".$row['tax_status'].PHP_EOL;
      $i+=1;
}
$sql="Select customerName,content,content,tax_id,datetime,tax_status from tax_b2c where tp='$tp' and (datetime='$inv_date'  or (tax_status='1' and edate='$inv_date'))";
$sth = $conn->prepare($sql);
$sth->execute();
while ($row= $sth->fetch()){
      $content.=$row['customerName'].",".substr($row['content'],-7).",".$row['tax_id'].",".$row['datetime'].",".$row['tax_status'].PHP_EOL;
      $i+=1;
}
$content= mb_convert_encoding($content,"Big5" , "UTF-8");
$dir="../txt/";//設定文件路徑
$filename = $dir.$filename; //設定路徑加上要輸出的名稱 (此處以 test.txt 為例)
if(@$fp = fopen($filename, 'w+'))
{
      //寫入資料
   fwrite($fp,  $content);
   fclose($fp);
}
require_once("../html/header.html");
require_once("../html/menu.html");
?>
<p></p>
<p align='center'><span style='font-size:20px;'>宅配應收發票文字檔:<?php echo $filename;?>共:<?php echo $i;?>筆下載完成 </span></p>
<p align='center'>
<a href="<?php echo $filename; ?>" download class='btn btn-success'>下載</a> &emsp;&emsp;
<a href="../hrb2b_tax_list.php"  class="btn btn-info" role="button">返回</a>
</p>
<?php
require("../html/footer.html");
?>
</body>
</html>