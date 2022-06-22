<?php

  // 設定四個變數
 // $db_server = "localhost";
  //$db_name = "members";
  //$db_user = "root";
  //$db_passwd = "";
  // mysql_connect是建立mysql連線服務的語法，在這裡我們先判斷連結mysql服務是否成功?
  // 前面的「@」就能把錯誤顯示給抑制住，也就是不會顯示錯誤，然後再拋出異常，添加這個只是為了讓瀏覽者不看到錯誤
  //if(!mysqli_connect($db_server, $db_user, $db_passwd,$db_name)) {
  //  die("無法對資料庫連線");
  //}
  // 設定mysql資料庫的編碼
  //mysqli_query("SET NAMES utf8");
  // mysql_select_db是選擇要使用的資料庫語法，在這裡我們先判斷連結members資料庫是否成功？
  $con =mysqli_connect("localhost","root","","members");
  if (!$con){
     //die("Failed to connect to MySQL: " .mysqli_connect_error());
     echo "無法對資料庫連線 ";
     exit();
  }
  mysqli_query($con,'SET NAMES UTF8');

?>

