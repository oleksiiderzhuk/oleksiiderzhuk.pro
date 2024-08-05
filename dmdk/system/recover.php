<?php
include('../probirka_db_functions.php');
$link = probirka_db_connect();
$id = $_GET['id'];
$now_id = $_GET['now_id'];
$sql = "SELECT * FROM adress WHERE id = $id";
$del_adress = mysqli_fetch_array(mysqli_query($link, $sql));
$del_version = $del_adress['version']-1;
$del_adress_id = $del_adress['adress_id'];
$sql = "UPDATE adress SET current='1', end_date = '6666-07-13 13:13:13' WHERE version = $del_version AND adress_id = $del_adress_id";
mysqli_query($link, $sql);
$sql = "DELETE FROM `adress` WHERE `adress`.`id` = $id";
mysqli_query($link, $sql);
header("Location: ../info_menu.php?id=".$now_id);
?>