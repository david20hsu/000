<?php
session_start();
if(!isset($_SESSION["username"])){
   header("Location: login.php");
   exit(); 
}
$title="嘉南羊乳-發票統計";
require_once("html/header.html");
require_once("html/menu.html");
$comp_idno="";
$ym=date('Y-m').'-01';
$ym0=date('Y-m-t', strtotime('now')); //當月最後一天
?>
<style>
table.dataTable thead th,tbody th, table.dataTable tbody td {
    padding: 2px 2px; /* e.g. change 8x to 4px here */
}
th, td {
  white-space: nowrap;
  overflow:hidden;
  text-overflow: ellipsis;
}
</style>
<div class="row" id="toprow">
<div class="col-2">
</div>
  <div class="col-6">
     <h3 align='center'><?php echo $title; ?></h3>
  </div>
  <div class="col-4">
      <a href="#QryModal" data-toggle="modal" class="btn btn-info btn-sm mt-1">查詢</a>
       &nbsp;
       <a href="index.php" class="btn btn-secondary btn-sm mt-1">返回</a>       
  </div>
</div>

<table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
<thead>
        <tr>
           <th>統一編號</th>
            <th>稅別</th>
            <th>類別</th>
            <th>張數</th>
            <th>發票金額</th>
            <th>作廢</th>
            <th>作廢金額</th>
        </tr>
</thead>
 <tbody>
    <?php
      require('include/config.php'); 
        if (isset($_POST['submit'])){
            $comp_idno=$_POST['comp_idno'];
            $ym =$_POST['yy'].'-'.$_POST['month'].'-01';
             $ym0 =$_POST['yy'].'-'.$_POST['month0'].'-01';
            $ym0= date('Y-m-t', strtotime($ym0));
        }
        if ($comp_idno==""){
            $sql = "CALL tax_comp_idnox('$ym','$ym0')";
        }else{
            $sql = "CALL tax_comp_idno('$ym','$ym0','$comp_idno')";
        }
        
        $sth = $conn->query($sql);
        //$sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        while ($row= $sth->fetch()){
        ?>
        <tr>
        <td><?php echo $row["uncode"];?> </td>
       <td><?php echo $row["tx"]; ?> </td>
            <td><?php echo $row["tp"]; ?> </td>

            <td><?php echo $row["xno"]; ?> </td>
            <td><?php echo $row["totalFee"]; ?></td>
         
            <td><?php echo $row["yno"]; ?></td>
            <td><?php echo $row["totalFee0"]; ?></td>
          </tr>     
        <?php  } ?>
           
    </tbody>
</table>
<?php require('tax/taxtot_modal.php');?>
<?php 
require("./html/footer.html");
require("./html/endcnd.html");
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
        responsive: true,
    
        buttons: [
             'copy', {
                extend: 'csv',
                text: 'CSV',
                bom : true},'print'
            ],
            "scrollX": true,
            //設置固定高度為200px 數據量溢出時出現滾動條
            // "scrollY": "500px",
            "scrollCollapse": "true",
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
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
               targets:[],
               orderable: false,
               targets:[], 
               searchable:false
            }]
        });
        //  columnDefs參數則定義最後的資料行無法排序;
    });
</script>
</body>
</html>