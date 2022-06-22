<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
$title = "嘉南羊乳-發票明細";
require_once("html/header.html");
require_once("html/menu.html");
$comp_idno = "";
$ym = date('Y-m') . '-01';
$ym0 = date('Y-m-t', strtotime('now')); //當月最後一天
$tax_amt = $tax_no = $bad_no = $bad_amt = 0;
$stax_id = $etax_id = $tax_status = '';
$xchk = 0;
$sdate = date('Y-m-01', strtotime(date("Y-m-d")));
$edate = date('Y-m-d');
$stax_id = $etax_id = $tax_status = $comp_idno = '';
?>
<style>
    table.dataTable thead th,
    tbody tr,
    table.dataTable tbody td {
        padding: 1px 1px;
        /* e.g. change 8x to 4px here */
        font-size: 16px;
    }

    th,
    td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    #Q1 tr td {
        width: 400px;
        border: 1px solid green;
        font-size: 1.1em;
    }
    #Q1 tr {
        height: 35px;
        padding: auto;
        ;
    }
    #Q1 td {
        padding: auto;
    }
    .md {
        text-align: center;
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
            <th>發票號碼</th>
            <th>發票日期</th>
            <th>發票金額</th>
            <th>訂單號碼</th>
            <th>客戶代號</th>
            <th>買方統編</th>
            <th>類別</th>
            <th>狀態</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require('include/config.php');
        if (isset($_POST['submit'])) {
            $sdate = $_POST['sdate'];
            $edate = $_POST['edate'];
            $comp_idno = $_POST['comp_idno'];
            $tax_status = $_POST['tax_status'];
            $stax_id = $_POST['stax_id'];
            $etax_id = $_POST['etax_id'];
            $edate = $_POST['edate'];
            $comp_idno = $_POST['comp_idno'];
            $tax_status = $_POST['tax_status'];
            $stax_id = $_POST['stax_id'];
            $etax_id = $_POST['etax_id'];
            $cql = "SELECT  uncode,datetime,tax_id,customerName,orderCode,phone,totalFee,tax_status, 'b' as tp FROM `tax_b2b` where datetime between  '$sdate'  and '$edate'";
            $sql = "SELECT  uncode,datetime,tax_id,customerName,orderCode,phone,totalFee,tax_status, 'c' as tp FROM `tax_b2c` where datetime between '$sdate'  and '$edate'";
            if ($comp_idno !== '') {
                $cql .= " and uncode='$comp_idno'";
                $sql .= " and uncode='$comp_idno'";
            }
            if ($tax_status !== '') {
                $cql .= " and tax_status='$tax_status'";
                $sql .= " and tax_status='$tax_status'";
            }
            if ($stax_id !== '') {
                $cql .= " and tax_id >='$stax_id'";
                $sql .= " and tax_id >='$stax_id'";
            }
            if ($etax_id !== '') {
                $cql .= " and tax_id <='$etax_id'";
                $sql .= " and tax_id <='$etax_id'";
            }
            $sql .= " UNION " . $cql . " order by tax_id ";
        } else {
            $cql = "SELECT uncode,datetime,tax_id,customerName,orderCode,phone,totalFee,tax_status, 'b' as tp FROM `tax_b2b` where datetime between  '$sdate'  and '$edate'";
            $sql = "SELECT uncode,datetime,tax_id,customerName,orderCode,phone,totalFee,tax_status, 'c' as tp FROM `tax_b2c` where datetime between '$sdate'  and '$edate'";
            $sql .= " UNION " . $cql . " order by tax_id ";
        }
        //echo $sql;
        $tax_amt = $tax_no = $bad_amt = $bad=0;
        $sth = $conn->prepare($sql);
        $sth->execute();
        while ($row = $sth->fetch()) {
            $ed = date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 day'));
            $ey = substr($ed, 0, 4) - 1911;
            $em = substr($ed, -6);
            $ed = $ey . $em;                      // 繳費期限
            if ($row['tax_status'] == '1') {
                $bad_no += 1;
                $bad_amt += $row["totalFee"];
            } else {
                $tax_no += 1;
                $tax_amt += $row["totalFee"];
            }
            $xdate=$row['datetime'];
            $xdate =substr($xdate,0,4)-1911;
            $xdate .= substr($row['datetime'], -6);
            $tp = '2聯';
            if ($row['tp'] == 'b') {
                $tp = '3聯';
            }
            $status = '開立';
            if ($row['tax_status'] == '1') {
                $status = '作廢';
            }
        ?>
            <tr>
                <td><?php echo $row["uncode"]; ?> </td>
                <td><?php echo $row["tax_id"]; ?> </td>
                <td><?php echo $xdate; ?> </td>
                <td style="text-align: right"><?php echo  number_format($row["totalFee"]); ?></td>
                <td><?php echo $row["customerName"]; ?> </td>
                <td><?php echo $row["orderCode"]; ?> </td>
                <td><?php echo $row["phone"]; ?></td>
                <td><?php echo $tp; ?> </td>
                <td><?php echo $status; ?> </td>
            </tr>
        <?php  } ?>
    </tbody>
</table>
<div class="container">
<div class="row"  style="height:30px;padding-top: auto; background-color:darkseagreen; align-content: center; margin-bottom:10px; font-weight: bold; border-radius: 10px">
   <h4>
   &emsp;&emsp;已開立張數:&ensp;<?php  echo  number_format($tax_no,0);?> &emsp;
    已開立金額:&ensp;<?php  echo  number_format($tax_amt,0);?> &emsp;
    已作廢張數:&ensp;<?php  echo  number_format($bad_no,0);?> &emsp;
    已作廢金額:&ensp;<?php  echo  number_format($bad_amt,0);?> &emsp;
    </h4>
</div>
        </div>
<?php require('tax/taxdet_modal.php'); ?>
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
                    bom: true
                }, 'print'
            ],
            "scrollX": true,
            //設置固定高度為200px 數據量溢出時出現滾動條
            "scrollY": "300px",
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
                targets: [],
                orderable: false,
                targets: [],
                searchable: false
            }]
        });
        //  columnDefs參數則定義最後的資料行無法排序;
    });
</script>
</body>

</html>