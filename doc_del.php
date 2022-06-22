
<?php
session_start();
if(!isset($_SESSION["username"])){
   header("Location: ../login.php");
   exit(); 
}
extract($_REQUEST);
require('./include/config.php'); 
$cql = "delete from doc_file where id='$del'";
$stmt = $conn->prepare($cql); 
$stmt->execute();
header("Location:doc.php");
/*
$result = $conn->query($sql);

$row = $result->fetch();
unlink("upload/$row[doc_id]");

mysql_query("delete from doc_file where id='$del'");

header("Location:index.php");
*/
?>
