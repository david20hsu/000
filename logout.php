<?php
session_start();
// Destroying All Sessions
if(session_destroy())
{
// Redirecting To Home Page
unset($_SESSION["username"]);
header("Location: login.php");
}
?> 
