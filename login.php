<!DOCTYPE html>
<html>
<meta charset="utf-8">
<title>Login</title>
   <link rel="stylesheet"  href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
   <link rel="stylesheet" href="./assets/css/login.css">
</head>
<body>
<?php 
   session_start();
   if(isset($_POST['login'])){
      require("./include/config.php");
      if($_POST['acode']==$_POST['xcode']){
			$username = addslashes($_POST['username']); // 處理sql injection
			$password = addslashes($_POST['password']); // 處理sql injection
			$sql = "SELECT * FROM `sys_uemp` WHERE `emp_id`=? AND `emp_pd`=? ";
			$query = $conn->prepare($sql);
			$query->execute(array($username,$password));
			$row = $query->rowCount();
			$fetch = $query->fetch();
			if($row > 0) {
				$_SESSION['username'] = $fetch['emp_id'];
				header("location:index.php");
			} else{
				echo "
				<script>alert('帳號 或 密碼 不正確')</script>
				";
			}
		}else{
			echo "
				<script>alert('請正確輸入 驗證碼  !')</script>
			";
		}
	
   }
?>
<div class="login">
<h1>登入作業</h1>
<form action="login.php" method="post" name="login">
<label for="username">
		<i class="fas fa-user"></i>
</label>
<input type="text" name="username" placeholder="輸入 使用者帳號" required />
<label for="password">
   <i class="fas fa-lock"></i>
</label>
<input type="password" name="password" placeholder="輸入 使用者密碼" required />
<label for="xcode">
    <i class="fas fa-user-check"></i>
</label>
<?php
    $rcode = rand(1000,9999); //產生亂數5位碼 
?>
<input type="text" name="xcode" maxlength="4" size="4"  placeholder=<?php echo '驗證碼:'.$rcode; ?>  required>
<input type="hidden" name="acode" value="<?php echo $rcode; ?>">
<br>
<input type="submit" name="login" value="送出" />
</form>
<!--
<p>還沒有註冊 ? <a href='registration.php'>在此註冊</a></p>
-->
</div>
</body>
</html>
