<?php
session_start();
if(!isset($_SESSION["username"])){
   header("Location: login.php");
   exit(); 
}
$title="嘉南羊乳-宅配發票";
require_once("html/header.html");
require_once("html/menu.html");
?>
<style>
    .container {
     display: flex;
     justify-content: center;
    }
    table{
        width: 500px;
    }
    table tr td{
         /*   width:350px;*/
         /*   margin-left:-20px;*/
            border: 1px solid green;
        }
        tr{
            height: 35px;
            padding: auto;;
        }
        td{
            padding:auto;
        }
        .md{
            text-align: center;
        }
        div{
            align-items: center;
        }
        h3{
          display: inline-block;
        margin: 0 auto;
        }
</style>
<?php
$tax_amt=$tax_no=$bad_no=$bad_amt=0;
$xchk=0;
$sdate=date('Y-m-01', strtotime(date("Y-m-d")));
$edate=date('Y-m-d');
$stax_id=$etax_id=$tax_status=$comp_idno='';
$stax_id=$etax_id=$tax_status='';

if (isset($_POST['submit'])){
    $sdate=$_POST['sdate'];
    $edate=$_POST['edate'];
    $comp_idno=$_POST['comp_idno'];
    $tax_status=$_POST['tax_status'];
    $stax_id=$_POST['stax_id'];
    $etax_id=$_POST['etax_id'];
    $cql="SELECT uncode,datetime,tax_id,totalFee,tax_status, 'b' as tp FROM `tax_b2b` where datetime between  '$sdate'  and '$edate'";
    $sql="SELECT uncode,datetime,tax_id,totalFee,tax_status, 'c' as tp FROM `tax_b2c` where datetime between '$sdate'  and '$edate'";
    if ($comp_idno!==''){
        $cql.=" and uncode='$comp_idno'" ;
        $sql.=" and uncode='$comp_idno'" ;
    }
    if ($tax_status!==''){
        $cql.=" and tax_status='$tax_status'" ;
        $sql.=" and tax_status='$tax_status'" ;
    }
    if ($stax_id!==''){
        $cql.=" and tax_id >='$stax_id'" ;
        $sql.=" and tax_id >='$stax_id'" ;
    }
    if ($etax_id!==''){
        $cql.=" and tax_id <='$etax_id'" ;
        $sql.=" and tax_id <='$etax_id'" ;
    }
    $sql .=" UNION ".$cql ." order by tax_id ";
    require('./include/config.php');
    $xchk=1;
    $sth= $conn->prepare($sql);
    $sth->execute();
    while ($row = $sth->fetch()) {
         if ($row['tax_status']=="1"){
              $bad_no +=1;
              $bad_amt +=$row['totalFee'];
         }else{
            $tax_no +=1;
            $tax_amt +=$row['totalFee'];
         }
    };
//    print_r($tax_amt);
}else{
    $sdate=date('Y-m-01', strtotime(date("Y-m-d")));
    $edate=date('Y-m-d');
}
?>
<div class="container">
<form id="submitForm"  action="" method="post">
    <div class="row"  style="height:40px;padding-top: auto; background-color:cornflowerblue; align-content: center; margin-bottom:10px; font-weight: bold; border-radius: 10px">
    <h3>發票查詢</h3>
    </div>
   <table id='Q1'>
    <tr class='md'>
        <td>項目</td>
        <td>起始</td>
        <td>截止</td>
    </tr>
     <tr>
         <td class='md'>發票日期</td>
         <td ><input type="date" name="sdate" id="sdate"  value="<?php echo $sdate; ?>" required>
         </td>
         <td><input type="date" name="edate" id="edate"  value="<?php echo $edate; ?>" required>
         </td>
     </tr>
     <tr>
         <td class='md'>發票號碼</td>
         <td>
         <?php if ($xchk=1 && $etax_id!=''){?>
             <input type="text" name="stax_id"  id="stax_id" maxlength="10" value="<?php echo $stax_id; ?>"> 
            <?php } else{ ?>
            <input type="text" name="stax_id"  id="stax_id" maxlength="10"> 
           <?php } ?>
         </td>
       
         <td>
         <?php if ($xchk=1 && $etax_id!=''){?>
             <input type="text" name="etax_id"  id="etax_id" maxlength="10" value="<?php echo $etax_id; ?>"> 
            <?php } else{ ?>
            <input type="text" name="etax_id"  id="etax_id" maxlength="10"> 
           <?php } ?>
         </td>
     </tr>
     <tr>
        <td class="md">公司別</td>
       <td> 
       <select name="comp_idno" id="comp_idno" style="background-color:lightskyblue">
                                <option value="">~請點選~</option>
                                <?php
                                require('./include/config.php');
                                $sql = " select comp_idno,comp_names from comp where api_idno<>'' order by comp_idno";
                                $sth = $conn->prepare($sql);
                                $sth->execute();
                                while ($row = $sth->fetch()) {
                                    if ($row['comp_idno'] == $comp_idno) {
                                        echo '<option   value="' . $row['comp_idno'] . '" selected>' . $row['comp_names'] . '</option>';
                                    } else {
                                        echo '<option value="' . $row["comp_idno"] . '">' . $row["comp_names"] . '</option>' . "\n";
                                    }
                                }
                                ?>
                            </select>

       </td>
       <td>
           狀態:
           <select name="tax_status" id="tax_status" style="background-color:lightskyblue">
               <option value="">全部</option>
               <option value="0" <?php if($tax_status=='0'){ echo 'selected'; }?>>開立</option>
               <option value="1" <?php if($tax_status=='1'){ echo 'selected'; }?>>作廢</option>
           </select>&ensp;
          <input type="submit" name='submit' class="btn-sm btn-success" value="查詢">
      </td>
     </tr>
     <tr>
         <td  class="md">查詢期間</td>
         <td  colspan="2">
             <?php if($xchk==1 && $sdate!='' && $edate!==''){
                   echo $sdate .' → '.$edate;
             }?>
         </td>
     </tr>
     <tr>
         <td  class="md">開立總數</td>
         <td  colspan="2">
         <input type="text" name="tax_no" id="tax_no" value='<?php echo number_format($tax_no,0);?>'>
            <?php if($xchk==1 ){
                 echo number_format($tax_no,0);
             }?>
         </td>
     </tr>
     <tr>
         <td  class="md" >開立金額</td>
         <td colspan="2">
         <input type="text" name="tax_amt" id="tax_amt" value='<?php echo number_format($tax_amt,0);?>'>
         <?php if($xchk==1 ){
                 echo number_format($tax_amt,0);
             }?>
         </td>
     </tr>
     <tr>
         <td  class="md">作廢總數</td>
         <td colspan="2">
            <input type="text" name="bad_no" id="bax_no" value='<?php echo number_format($bad_no,0);?>'>
            <?php if($xchk==1){
                 echo number_format($bad_no,0);
             }?>
         </td>
     </tr>
     <tr>
         <td  class="md">作廢金額</td>
         <td colspan="2">
         <input type="text" name="bad_amt" id="bad_amt" value='<?php echo number_format($bad_amt,0);?>'>
             <?php if($xchk==1){
                 echo number_format($bad_amt,0);
             }?>
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