<?php
session_start();
if(!isset($_SESSION["username"])){
   header("Location: login.php");
   exit(); 
}
$title="嘉南羊乳-文件類別";
require_once("html/header.html");
require_once("html/menu.html");
//echo __DIR__."<br/>";

?>
<style>
table.dataTable thead th,tbody th, table.dataTable tbody td {
    padding: 4px 4px; /* e.g. change 8x to 4px here */
}
#toprow {
    margin-top: -15px;
}
#doc_keyword{
    width: 446px;
}
</style>
<?php
   date_default_timezone_set("Asia/Taipei");
    //$conn = new PDO('mysql:host=localhost; dbname=myweb', 'root', '155300') or die(mysql_error());
    if (isset($_POST['submit']) != "") {
     
        //echo date_default_timezone_get();
        require('./include/config.php');
        $extension = array("doc", "docx", "ppt", "pptx", "jpeg", "jpg", "png", "gif", "pdf");
        $name = $_FILES['photo']['name'];
        $size = $_FILES['photo']['size'];
        $type = $_FILES['photo']['type'];
        $temp = $_FILES['photo']['tmp_name'];
        $muser = $_POST['author'];
        $mdate = date('Y-m-d H:i:s');
        $doc_id = $_POST['doc_id'];
        $file_name = $_POST['file_name'];
        $doc_keyword = $_POST['doc_keyword'];
        $ext = substr($name, strrpos($name, '.') + 1);
        if (!in_array(strtolower($ext), $extension)) {
            $_SESSION['error'] = '檔案格式不符合:' . $ext;
        } else {
            $xfile = $doc_id . date('Ymd') . date('H') . date('i') . date('s') . '.' . $ext;
            //move_uploaded_file($temp, "upload/" . $name);
            move_uploaded_file($temp, "upload/" . $xfile);
            $data=array();
            $data = [
                "file_id"=> $xfile,
                "file_name"=>trim($_POST['file_name']),
                "doc_id"=>trim($_POST['doc_id']),
                "doc_keyword"=>trim($_POST['doc_keyword']),
                "muser"=>$muser,
                "mdate"=>$mdate
            ];
            $cql = "INSERT INTO doc_file (file_id,file_name,doc_id,doc_keyword,muser,mdate) ";
            $cql = $cql." VALUES(:file_id,:file_name,:doc_id,:doc_keyword,:muser,:mdate)";
            $stmt= $conn->prepare($cql);
            $stmt->execute($data);
        }
    }
    ?>
    <div class="row">
        <?php
        if (isset($_SESSION['error'])) {
            echo
                "
					<div class='alert alert-danger text-center'>
						<button class='close'>&times;</button>
						" . $_SESSION['error'] . "
					</div>
					";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo
                "
					<div class='alert alert-success text-center'>
						<button class='close'>&times;</button>
						" . $_SESSION['success'] . "
					</div>
					";
            unset($_SESSION['success']);
        }
        ?>
    </div>
<div class="row" id="toprow">
<div  class="card bg-secondary text-white" style="width: 100%; margin-top:0px;">
<h5 style="text-align:center;">本文件包含和民宅食有限公司機密資訊及個人資料，任何人不得任意傳佈或揭露，以共同善盡資訊安全與個資保護責任</h5>
        <form enctype="multipart/form-data" action="" method="post">
            <table style="width:60%; margin-left: 5px;">
                <tr>
                    <td style="width:10%; text-align:right;">文件名稱</td>
                    <td>
                        <input type="text" name="file_name" id="file_name" style="margin-left: 5px;" classs="form-control" required placeholder="文件名稱">
                    </td>
                    <td style="width:10%; text-align:right;">文件類別:</td>
                    <td>
                        <select name="doc_id" id=" doc_id"  style="margin-left: 5px;" classs="form-control" required style="width:250px;">
                            <?php
                             require('./include/config.php'); 
                             $cql="select doc_id,doc_name from doc_type order by doc_id";
                             $sthm = $conn->prepare($cql);
                             $sthm->execute();
                             while ($rows= $sthm->fetch()){?>
                                <option value="<?php echo $rows['doc_id']; ?>"><?php echo $rows['doc_name']; ?></option>
                           <?php }   ?>
                        </select>
                    </td>
                   <!--
                    本文件包含萬凌工業股份有限公司機密資訊及個人資料，任何人不得任意傳佈或揭露，以共同善盡資訊安全與個資保護責任
                             -->
                </tr>
                <tr>
                <td style="width:10%; text-align:right;">關鍵字</td>
                <td colspan="3">
                   <input type="text" name="doc_keyword" id="doc_keyword" style="margin-left: 5px;"  placeholder="文件查詢關鍵字" required>
                </td>
                </tr>
                <tr>
                    <td style="width:10%; text-align:right;">文件編審</td>
                    <td>
                        <input type="text" name="author" id="author" style="margin-left: 5px;"  placeholder="文件編審" required value=<?php echo $_SESSION['username']; ?>>
                    </td>
                    <td style="width:10%; text-align:right;">
                        上傳檔案
                    </td>
                    <td>
                        <input type="file" name="photo" id="photo" style="margin-left: 5px;"  class="form-control-file" style="width:400px;height:30px;border:2px blue none;" required>
                    </td>
                </tr>
                <tr>
                    <td style="width:10%; text-align:right;">
                        <input type="submit" name="submit" style="height:25px;border:2px blue none;background-color:pink;left:30px;" value="送出表單">
                    </td>
                    <td>
                    <td style="width:10%; text-align:right;">
                        <font color="Yellow">檔案格式</font>
                    </td>
                    <td  style="margin-left: 5px;">

                        <font color="white">pdf,doc,docx,ppt,pptx,jpeg,jpg,png,gif
                    </td>
                </tr>
            </table>
        </form>      
</div>
    <hr>

<div class="col-8">
  <h3 align='center'><?php echo $title; ?></h3>
  </div>
  <div class="col-4">
       <a href="index.php" class="btn btn-secondary btn-sm mt-1">返回</a>
</div>
</div>
<table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
<thead>
   <tr>
     <th>ID</th>
     <th>文件編號</th>
     <th>文件名稱</th>
     <th>文件類別</th>
     <th>搜尋關鍵字</th>
     <th>維護人</th>
     <th>上傳日期</th>
     <th>檢視</th>
     <th>下載</th>
      <th>刪除</th>
     </tr>
    </thead>
    <tbody>
    <?php
    require('./include/config.php'); 
    $sql="select a.*,b.doc_name from doc_file a left join doc_type b using(doc_id) ";
    $sth = $conn->prepare($sql);
    $sth->execute();
    while ($row= $sth->fetch()){
          $id = $row['id'];
          $file_id = $row['file_id'];
          $file_name = $row['file_name'];
          $doc_name = $row['doc_name'];
          $doc_keyword = $row['doc_keyword'];
          $date = $row['mdate'];
     ?>
       <tr>
          <td><?php echo $row['id'] ?></td>
          <td><?php echo $row['file_id'] ?></td>
          <td><?php echo $row['file_name'] ?></td>
          <td><?php echo $row['doc_name'] ?></td>
          <td><?php echo $row['doc_keyword'] ?></td>
          <td><?php echo $row['muser'] ?></td>
          <td><?php echo substr($row['mdate'],0,10) ?></td>
          <td><a href="upload/<?php echo $row['file_id']; ?>" target="_blank" class='btn-sm btn-primary mt-1'>檢視</a></td>
          <td><a href="upload/<?php echo $row['file_id']; ?>" download  class='btn-sm btn-info mt-1'>下載</td>
           <td>
             <a href="doc_del.php?del=<?php echo $row['id'] ?>" class='btn-sm btn-danger mt-1'>刪除</a>
           </td>
      </tr>
     <?php } ?>
    </tbody>
    </table>
    </div>
</div>
<?php 
require("html/footer.html");
require("html/endcnd.html");
?>


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
           
            lengthMenu: [
                [5,10, 25, 50, -1],
                [5,10, 25, 50, "All"]
            ],
            "scrollX": true,
            //設置固定高度為200px 數據量溢出時出現滾動條
            //"scrollY": "500px",
            //"scrollCollapse": "true",
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
               targets:[7,8,9],
               orderable: false,
             targets:[7,8,9], 
               searchable:false
            }]
        });
        //  columnDefs參數則定義最後的資料行無法排序;
    });
</script>
</body>
</html>