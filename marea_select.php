<?php
require("./include/config.php");
$dept_id = $_GET['dept_id'];
if(isset($dept_id)){
	
    $stmt = $pdo->prepare("select marea_id,marea_name from marea where group_id='1' and type_id in('1','2','3','4','5','6') and dept_id =?");
    $stmt->execute([$dept_id]); 
    $row = $stmt->fetch();
   
    echo urldecode(json_encode($select));
}