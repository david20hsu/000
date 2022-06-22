<?php
session_start();

if(!isset($_SESSION["username"])){
   header("Location: ../login.php");
   exit(); 
}
require_once("../html/header.html");
require_once("../html/menu.html");
$msg="";  
$xfile="";
if (isset($_GET)) {
    $xfile=$_GET['xfile']; // 發票號碼
    $orderCode = $_GET['orderCode']; // 發票號碼
    $tp = $_GET['tp'];        // 賣方統編
    $uncode = $_GET['uncode'];        // 賣方統編
    require('../include/config.php'); 
    $cql = "delete from tax_b2b where `tp`='$tp'  and `orderCode`='$orderCode' and `uncode`='$uncode'";
    $stmt = $conn->prepare($cql); 
    $stmt->execute();
    $msg="未取號:".$orderCode.',刪除成功';  
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
<p align='center'><a href="<?php echo $xfile ?>"  class="btn btn-info" role="button">返回</a> </p>
<?php
require("../html/footer.html");
?>
</body>
 </html>