<?php
$data = json_decode(file_get_contents('php://input'), true);
$con =mysqli_connect("localhost","root","","goal_pp");
if (!$con){
     //die("Failed to connect to MySQL: " .mysqli_connect_error());
     echo "無法對資料庫連線 ";
     exit();
  }
  mysqli_query($con,'SET NAMES UTF8');
//mysqli 預設編號為latin-1，建立資料庫已指定utf8編碼，所以要指定連線時所用編碼

$tp_id=$data['a'];
$tp_name=$data['b'];
$sql = "INSERT INTO doc_tp(tp_id,tp_name) VALUES ('$tp_id','$tp_name')";
mysqli_query($con, $sql);

$con->close();
echo "hello world!";
?>