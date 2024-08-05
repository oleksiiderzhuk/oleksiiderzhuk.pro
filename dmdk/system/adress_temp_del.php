<?php

include('../probirka_db_functions.php');
$now=date('Y-m-d H:i:s');
$link = probirka_db_connect();
$id = $_GET['id'];
$now_id = $_GET['now_id'];
$sql = "UPDATE `adress` SET `isvalid` = '0' WHERE `adress`.`id` = $id;";
mysqli_query($link,$sql);
header("Location: ../info_menu.php?id=".$now_id);


?>