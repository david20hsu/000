<?php
$data = json_decode(file_get_contents('php://input'), true);
var_dump(json_decode($data, true));
?>