<?php
/*
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '0');
define('DB_NAME', 'goal_pp');
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link === false){
    die("ERROR: 無法連線資料庫: " . mysqli_connect_error());
}else{
    mysqli_query($link,'SET NAMES UTF8');
}
*/

$dbhost = '127.0.0.1';
$dbuser = 'root';
$dbpasswd = '';
$dbname = 'goal_pp';
$dsn = "mysql:host=".$dbhost.";dbname=".$dbname;
try
{
    //注意，使用PDO方式連結，需要指定一個資料庫，否則將拋出異常
    $conn = new PDO($dsn,$dbuser,$dbpasswd);
    $conn->exec("SET CHARACTER SET utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 //   echo "Connected Successfully";
}
catch(PDOException $e)
{
    echo "資料庫連線失敗: ".$e->getMessage();
}

?>