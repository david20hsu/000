<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
$title = "嘉南羊乳-團戶應收B2B(3聯)";
require_once("html/header.html");
require_once("html/menu.html");
$comp_id = "";
$ym = date('Y-m') . '-%';

$comp_idno = "";
$sdate = "";
$edate = "";
$stax_id = $etax_id = $tax_status = "";
?>
<style>
    table.dataTable thead th,
    tbody th,
    table.dataTable tbody td {
        padding: 2px 2px;
        /* e.g. change 8x to 4px here */
    }

    th,
    td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #Q1 tr td {
        width: auto;
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
    <div class="col-8">
        <h3 align='center'><?php echo substr($title, 13, strlen($title) - 13); ?></h3>
    </div>
    <div class="col-4">
        <a href="#addModal" data-toggle="modal" class="btn btn-info btn-sm mt-1">CSV上傳</a>
        &nbsp;
        <button class="btn btn-warning btn-sm mt-1" data-toggle="modal" data-target="#ReModal">發票取號</button>
        &nbsp;
        <a href="#editModal" data-toggle="modal" class="btn btn-success btn-sm mt-1">下載</a>
        &nbsp;
        <a href="#QryModal" data-toggle="modal" class="btn btn-primary btn-sm mt-1">查詢</a>
        &nbsp;
        <a href="index.php" class="btn btn-secondary btn-sm mt-1">返回</a>
    </div>
</div>
<table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
    <thead>
        <tr>
            <th>賣方統編</th>
            <th>客戶代號</th>
            <th>發票日期</th>
            <th>發票號碼</th>
            <th>買方統編</th>
            <th>稅別</th>
            <th>發票金額</th>
            <th>稅額</th>
            <th>銷售額</th>
            <th>商品</th>
            <th>單價</th>
            <th>數量</th>
            <th>備註</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require('include/config.php');
        if (isset($_POST['Qrysubmit'])) {
            $comp_idno = $_POST['comp_idno'];
            $sdate = $_POST['sdate'];
            $edate = $_POST['edate'];
            $stax_id = $_POST['stax_id'];
            $etax_id = $_POST['etax_id'];
            $tax_status = $_POST['tax_status'];
            $sql = "select *  from tax_b2b where tp='3' and datetime >='$sdate' and datetime <='$edate'";
            if ($comp_idno !== '') {
                $sql .= " and uncode='$comp_idno'";
            }
            if ($tax_status !== '') {
                $sql .= " and tax_status='$tax_status'";
            }
            if ($stax_id !== '') {
                $sql .= " and tax_id>='$stax_id'";
            }
            if ($etax_id !== '') {
                $sql .= " and tax_id<='$etax_id'";
            }
            $sql .= " order by uncode,tax_id ";
            unset($_POST);
        } else {
            //  $xy=date('Y');
            //  $xm=date('m');
            //  $sdate="";$edate="";
            //  if($xm%2==0){  // 單數月
            //    $xm=$xm-1;
            //   } 
            //  $sdate =$xy.'-'.$xm.'-01';
            //  $edate =date('Y-m-d', strtotime("$sdate +2 month -1 day"));
            $sdate = date('Y-m-01', strtotime('-60 days'));
            $edate = date('Y-m-d', strtotime("$sdate +4 month -1 day"));
            $sql = "select *  from tax_b2b where  tp='3' and datetime between '$sdate' and '$edate' order by uncode,tax_id ";
        }
        $sth = $conn->prepare($sql);
        $sth->execute();
        while ($row = $sth->fetch()) {
        ?>
            <tr>
                <td><?php echo $row["uncode"]; ?> </td>
                <td><?php echo $row["customerName"]; ?> </td>
                <td><?php echo $row["datetime"]; ?></td>
                <td><?php echo $row["tax_id"]; ?></td>
                <td><?php echo $row["phone"]; ?></td>
                <td><?php echo $row["taxState"]; ?></td>
                <td><?php echo $row["totalFee"]; ?></td>
                <td><?php echo $row["amount"]; ?></td>
                <td><?php echo $row["sales"]; ?></td>
                <td><?php echo $row["name"]; ?></td>
                <td><?php echo $row["money"]; ?> </td>
                <td><?php echo $row["number"]; ?></td>
                <td><?php echo $row["content"]; ?></td>
                <td>
                    <?php if ($row['tax_status'] == '0') { ?>
                        <?php if ($row["tax_id"] <> '') { ?>
                            <a onclick="return confirm('【已取號】確定要作廢嗎 ?')" href="tax/del_grb2b.php?tax_id=<?php echo $row['tax_id'] . "&uncode=" .  $row['uncode']; ?>" class='btn-sm btn-danger mt-1'>作廢</a>
                        <?php } else { ?>
                            <a onclick="return confirm('【未取號】確定要刪除嗎 ?')" href="tax/del_b2b.php?orderCode=<?php echo $row['orderCode'] . "&tp=" .  $row['tp'] . "&uncode=" .  $row['uncode'] . "&xfile=../grb2b_tax_list.php"; ?>" class='btn-sm btn-danger mt-1'>刪除</a>
                        <?php } ?>
                    <?php } else {
                        echo '已作廢';
                    } ?>
                </td>
            </tr>
        <?php  } ?>
    </tbody>
</table>
<!--Start QryModal-->
<div class="modal fade" id="QryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal 頭部 -->
            <div class="modal-header">
                <h4 class="modal-title">
                    <?php echo $title . '查詢'; ?></h3>
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" name="taxtot_form" id="txttot_form" action="#" method="POST">
                <div class="modal-body">
                    <table id='Q1'>
                        <tr class='md'>
                            <td>項目</td>
                            <td>起始</td>
                            <td>截止</td>
                        </tr>
                        <tr>
                            <td class='md'>發票日期</td>
                            <td><input type="date" name="sdate" id="sdate" value="<?php echo $sdate; ?>" required>
                            </td>
                            <td><input type="date" name="edate" id="edate" value="<?php echo $edate; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td class='md'>發票號碼</td>
                            <td>
                                <!--
                <?php if ($xchk = 1 && $etax_id != '') { ?>
                    <input type="text" name="stax_id" id="stax_id" maxlength="10" value="<?php echo $stax_id; ?>">
                <?php } else { ?>
                    <input type="text" name="stax_id" id="stax_id" maxlength="10">
                <?php } ?>
                -->
                                <input type="text" name="stax_id" id="stax_id" maxlength="10">
                            </td>

                            <td>
                                <!--
                <?php if ($xchk = 1 && $etax_id != '') { ?>
                    <input type="text" name="etax_id" id="etax_id" maxlength="10" value="<?php echo $etax_id; ?>">
                <?php } else { ?>
                    <input type="text" name="etax_id" id="etax_id" maxlength="10">
                <?php } ?>
                -->
                                <input type="text" name="etax_id" id="etax_id" maxlength="10">
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
                                    <option value="0" <?php if ($tax_status == '0') {
                                                            echo 'selected';
                                                        } ?>>開立</option>
                                    <option value="1" <?php if ($tax_status == '1') {
                                                            echo 'selected';
                                                        } ?>>作廢</option>
                                </select>&ensp;
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">放棄</button>
                    <button type="submit" name="Qrysubmit" class="btn btn-primary">提交</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end QryModal-->
<?php require('tax/grb2b_modal_add.php'); ?>
<?php require('tax/grb2b_modal_edit.php'); ?>
<?php
require("./html/footer.html");
require("./html/endcnd.html");
?>
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
                targets: [12],
                orderable: false,
                targets: [9, 11, 12],
                searchable: false
            }]
        });
        //  columnDefs參數則定義最後的資料行無法排序;
    });
</script>
</body>

</html>