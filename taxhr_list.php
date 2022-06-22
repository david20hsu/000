<?php
session_start();
if(!isset($_SESSION["username"])){
   header("Location: login.php");
   exit(); 
}
//select concat(YEAR(CURRENT_DATE())-1911,   if( MONTH(CURRENT_DATE()) >9,MONTH(CURRENT_DATE()),concat('0',MONTH(CURRENT_DATE()))))
// 西曆轉國曆
$title="嘉南羊乳-宅配應收";
require_once("html/header.html");
require_once("html/menu.html");
?>
<table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
    <thead>
        <tr>
            <th>賣方統編</th>
            <th>發票日期</th>
            <th>發票號碼</th>
            <th>訂單號碼</th>
            <th>載具/買方統編</th>
            <th>發票金額</th>
            <th>類別</th>
            <th>捐贈</th>
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
        $sql="select *  from hcust_taxe ";
        $sth = $conn->prepare($sql);
        $sth->execute();
         while ($row= $sth->fetch()){
        ?>
        <tr>
            <td><?php echo $row["comp_idno"]; ?> </td>
            <td><?php echo $row["inv_date"]; ?></td>
            <td><?php echo $row["tax_id"]; ?></td>
            <td><?php echo $row["orderCode"]; ?></td>
            <td><?php echo $row["phone"]; ?></td>
            <td><?php echo $row["totalFree"]; ?></td>
            <td><?php echo $row["state"]; ?></td>
            <td><?php echo $row["donationCode"]; ?></td>
            
            <td>
            <!--
                <a href="#updModal<?php echo $row['orderCode']; ?>" data-toggle="modal"
                    class="btn  btn-outline-success btn-sm mt-1">編修</a>
                <?php if ($role <=1) { ?>
                <a onclick="return confirm('確定要作廢嗎 ?')" href="doc_del.php?doc_ser=<?php echo $row['orderCode']; ?>"
                    class='btn btn-outline-danger btn-sm mt-1'>作廢</a>
                <?php } ?>
           -->
            </td>
             
        </tr>
        <?php  } ?>
           
    </tbody>
</table>
<?php require('tax/taxhr_modal_add.php');?>
<?php 
require("./html/footer.html");
require("./html/endcnd.html");

//echo file_get_contents("html/footer.html"); 
//echo file_get_contents("html/ender.html");
?>
<script type="text/javascript">
/*
    $(document).ready(function() {
       $(".nav-item").on("click", function(){
        $(".nav-item").find(".active").removeClass("active");
       $(this).addClass("active");
      })
   });
*/
  </script>
 <script type="text/javascript">
     $('.datepicker').datepicker({
         format: 'yyyy/mm/dd',
         startDate: '-3d'
    
	 });
 </script>
<script type="text/javascript">

    $(function() {
        $("#myDataTalbe").DataTable({
            dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            language: {
                sProcessing: "處理中...",
                sLengthMenu: "顯示 _MENU_ 項結果",
                sZeroRecords: "沒有匹配結果",
                sInfo: "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
                sInfoEmpty: "顯示第 0 至 0 項結果，共 0 項",
                sInfoFiltered: "(由 _MAX_ 項結果過濾)",
                sInfoPostFix: "",
                sSearch: "搜索:",
                sUrl: "",
                sEmptyTable: "表中數據為空",
                sLoadingRecords: "載入中...",
                sInfoThousands: ",",
                oPaginate: {
                    sFirst: "首頁",
                    sPrevious: "上頁",
                    sNext: "下頁",
                    sLast: "末頁"
                },
                oAria: {
                    sSortAscending: ": 以升序排列此列",
                    sSortDescending: ": 以降序排列此列"
                }
            },
            
            // searching: false, //關閉filter功能 ,查詢
            columnDefs: [{
               targets:[0,1,5],
                orderable: false
            }]
        });
        //  columnDefs參數則定義最後的資料行無法排序;
    });
</script>
</body>
</html>