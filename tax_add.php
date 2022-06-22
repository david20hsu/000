<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
$title = "嘉南羊乳-手工開發票作業";
require_once("html/header.html");
require_once("html/menu.html");
?>
<style>
    .container {
        display: flex;
        justify-content: center;
    }

    table {
        width: 500px;
    }
    table tr td {
        /*   width:350px;*/
        /*   margin-left:-20px;*/
        border: 1px solid green;
    }

    tr {
        height: 35px;
        padding: auto;
        ;
    }

    td {
        padding: auto;
    }
    text{
        width: 10px;
    }
    .md {
        text-align: center;
    }
    div {
        align-items: center;
    }
    h3 {
        display: inline-block;
        margin: 0 auto;
    }
    input[type=text],[type=number] {
      width: 120px;
    }
    #content{
        width: 200px;
    }
</style>
<?php

if (isset($_POST['submit'])) {
    function chk_key($sql){
        require('./include/config.php'); 
        $sth = $conn->prepare($sql);
      //  echo $sql;
        $sth->execute();
        $xkey='';
        while ($rowx = $sth->fetch()) {
            $xkey=$rowx['orderCode'];
            return $xkey;
        }
        return $xkey;
    }
    require('./include/config.php');
    $xtp=$_POST['xtp'];  // 2-b2c ; 3-b2b
    $tp='1';              // 單筆帳--預收款
    $orderCode=$_POST['orderCode'];
    $totalFee=$_POST['totalFee']; //總價 , 預收金額
    $datetime=$_POST['tax_date'];
    if ($orderCode==''){
        $orderCode=substr($datetime,2,2) .substr($datetime,5,2).substr($datetime,-2).'0001'; // 單號
        $xno=substr($datetime,2,2) .substr($datetime,5,2).substr($datetime,-2).'%'; // 單號
        if ($xtp=="2"){
           $sql="select orderCode from tax_b2c where tp='1' and orderCode like '$xno' order by orderCode desc  LIMIT 1 ";
        }else{
           $sql="select orderCode from tax_b2b where tp='1' and orderCode like '$xno' order by orderCode desc  LIMIT 1";
        }
        $xkey=chk_key($sql);
       // echo  $xkey. '<br>';
        if ($xkey<>''){
            $xkey=((int)substr($xkey,-4))+1;
            $orderCode=substr($datetime,2,2) .substr($datetime,5,2).substr($datetime,-2).substr('000'.(string)$xkey,-4);
        }
       // echo   $orderCode;
       // exit();
    }
    
    if ($xtp=='2'){
        $phone=$_POST['phone0']; // 客戶載具
        $state='0';
        $donationCode='';
    }else{
        $phone=$_POST['phone']; // 客戶統邊
        $taxState =$_POST['taxState'];
        $amount=round($totalFee-($totalFee/1.05),0); //總價 , 預收金額); //總價 , 預收金額
        $sales = $totalFee-$amount;
    }
    $uncode=$_POST['comp_idno'];
    $customerName=$_POST['customerName'];
    $content="嘉南羊乳-手動".$_POST['orderCode'];
    $name=$_POST['prod'];           //商品
  
   if ($xtp=='2'){
       $sql="insert into tax_b2c(uncode,customerName,orderCode,phone,datetime,state,donationCode,totalFee,content,name,money,number,tp,tax_status) values('$uncode','$customerName','$orderCode','$phone','$datetime','$state','$donationCode','$totalFee','$content','$name','$totalFee',1,'$tp','0')";
   }else{
       $sql="insert into tax_b2b(uncode,customerName,orderCode,phone,datetime,taxState,totalFee,amount,sales,content,name,money,number,tp,tax_status) values('$uncode','$customerName','$orderCode','$phone','$datetime','$taxState','$totalFee','$amount','$sales','$content','$name','$totalFee',1,'$tp','0')";
   }
   $stmt= $conn->prepare($sql);
   $stmt->execute();
}
?>
<div class="container">
    <form id="submitForm" action="" method="post">
        <div class="row" style="height:40px;padding-top: auto; background-color:cornflowerblue; align-content: center; margin-bottom:10px; font-weight: bold; border-radius: 10px">
            <h3>手工開發票--單筆帳</h3>
        </div>
        <table id='Q1'>
            <tr class='md'>
                <td>公司別:</td>
                <td>
                    <select name="comp_idno" id="comp_idno" required style="background-color:lightskyblue">
                    <option value="">請點選</option>;
                        <?php
                        require('./include/config.php');
                        $sql = " select comp_idno,comp_names from comp where api_idno<>'' order by comp_idno";
                        $sth = $conn->prepare($sql);
                        $sth->execute();
                        while ($row = $sth->fetch()) {
                            echo '<option value="' . $row["comp_idno"] . '">' . $row["comp_names"] . '</option>' . "\n";
                        }
                        ?>
                    </select>
                </td>
                <td>類別:</td>
                <td>
                  <select name="xtp" id="xtp" style="background-color:lightskyblue">
                        <option value="2">2聯式</option>
                        <option value="3">3聯式</option>
                  </select>
                </td>

            </tr>
            <tr>
                <td class='md'>發票日期:</td>
                <td><input type="date" name="tax_date" id="tax_date" value="<?php echo date('Y-m-d'); ?>" required>
        
                <td class='md'>稅別:</td>
                <td>
                <select name="taxState" id="taxState" style="background-color:lightskyblue"  disabled>
                        <option value="0">稅內含</option>
                        <option value="1">稅外加</option>
                  </select>
                </td>
            </tr>
            <tr>
                <td class='md'>客戶代號</td>
                <td><input type="text" name="customerName" id="customerName" maxlength="8" required>
        
                <td class='md'>訂單號碼</td>
                <td>
                    <input type="text" name="orderCode" id="orderCode" maxlength="20">
                </td>
            </tr>
            <tr>
                <td class='md'>銷售商品</td>
                <td>
                <select name="prod" id="prod" style="background-color:lightskyblue">
                  <option value="乳品">乳品</option>
                  <option value="羊乳副產品">副產品</option>
                </select>
                </td>
                <td class='md'>金額</td>
                <td>
                    <input type="text" name="totalFee" id="totalFee" required>
                </td>
            </tr>
            <tr>
                <td class='md'>買方統編</td>
                <td><input type="text" name="phone" id="phone" maxlength="8">
        
                <td class='md'>載具編號</td>
                <td>
                    <input type="text" name="phone0" id="phone0" maxlength="10">
                </td>
            </tr>
            <tr>
                <td class='md'>備註</td>
                <td colspan="2"><input type="text" name="contente" id="content">
    
                <td class='md'>
                <input type="submit" name='submit' class="btn-sm btn-success" value="提交">
                &emsp;
                 <a href="index.php" class="btn btn-secondary btn-sm">返回</a>
                </td>
            </tr>
        </table>
        
    
    </form>
</div>
<?php
require("./html/ender.html");
require("./html/footer.html");
//echo file_get_contents("html/footer.html"); 
//echo file_get_contents("html/ender.html");
?>