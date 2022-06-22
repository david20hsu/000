<?php
session_start();
if(!isset($_SESSION["username"])){
   header("Location: login.php");
   exit(); 
}
$title="嘉南羊乳-文件管理";
require_once("html/header.html");
require_once("html/menu.html");
?>
<script>
    var Norder='0,6';
</script>
<table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
    <thead>
        <tr>
            <th>文件</th>
            <th>文件名稱</th>
            <th>類別</th>
            <th>類別名稱</th>
            <th>版本</th>
            <th>建檔日</th>
            <th>編輯 &emsp;
                <a href="#addModal" data-toggle="modal" class="btn btn-outline-success btn-sm mt-1">新增</a>
                &emsp;
                <a href="index.php" class="btn btn-outline-secondary btn-sm mt-1">返回</a>
               
            </th>
        </tr>
    </thead>
    <tbody>
    
    <?php
    
        require('include/config.php'); 
        $sth = $conn->prepare("SELECT a.*,b.tp_name from doc a left join doc_tp b using(tp_id) where doc_active='1' ");
        $sth->execute();
         while ($row= $sth->fetch()){
        ?>
        <tr>
            <td><?php echo $row["doc_id"]; ?> </td>
            <td><?php echo $row["doc_name"]; ?></td>
            <td><?php echo $row["tp_id"]; ?></td>
            <td><?php echo $row["tp_name"]; ?></td>
            <td><?php echo $row["doc_ver"]; ?></td>
            <td><?php echo $row["mdate"]; ?></td>
            <td>
                <a href="#updModal<?php echo $row['doc_ser']; ?>" data-toggle="modal"
                    class="btn  btn-outline-success btn-sm mt-1">編修</a>
                <?php if ($role <=1) { ?>
                <a onclick="return confirm('確定要作廢嗎 ?')" href="doc_del.php?doc_ser=<?php echo $row['doc_ser']; ?>"
                    class='btn btn-outline-danger btn-sm mt-1'>作廢</a>
                <?php } ?>
            </td>
        </tr>
        <?php  } ?>
           
    </tbody>
</table>
<?php require('doc/doc_modal_add.php');?>
<?php 
require("./html/ender.html");
require("./html/footer.html");
//echo file_get_contents("html/footer.html"); 
//echo file_get_contents("html/ender.html");
?>