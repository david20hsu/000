<?php
session_start();
//$Norder="";  // 傳變數
if(!isset($_SESSION["username"])){
   header("Location: login.php");
   exit(); 
}
 $title="和民宅食-雲端發票";
require_once("html/header.html");
require_once("html/menu.html");
$title='';
?>
<?php 
//require("./html/ender.html");
//require("./html/footer.html");
echo file_get_contents("html/footer.html"); 
echo file_get_contents("html/ender.html");
?>