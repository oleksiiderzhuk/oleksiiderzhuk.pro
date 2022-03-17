<?php

include('../probirka_db_functions.php');
$now=date('Y-m-d H:i:s');
$link = probirka_db_connect();
$id = $_POST['id'];
$old_adress = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM adress WHERE id = $id"));
$version = $old_adress['version']+1;
$adress_id = $old_adress['adress_id'];
$person_id = $old_adress['person_id'];
$is_main = $old_adress['is_main'];
$postindex = $old_adress['postindex'];
$region = mysqli_real_escape_string($link,$old_adress['region']);
$city = mysqli_real_escape_string($link,$old_adress['city']);
$area = mysqli_real_escape_string($link,$old_adress['area']);
$adress = mysqli_real_escape_string($link,$old_adress['adress']);
$worktype= $old_adress['worktype'];
$isvalid = 1;
$income_date = $_POST['income_date'];
$income_number = $_POST['income_number'];
$edit_name = $_POST['edit_name'];
$start_date = $now;
$end_date = $now;
$current = 2;
$now_id = $_POST['now_id'];
$sql = "INSERT INTO `adress` (`id`, `version`, `adress_id`, `person_id`, `is_main`, `postindex`, `region`, `city`, `area`, `adress`, `worktype`, `isvalid`, `income_date`, `income_number`, `edit_name`, `start_date`, `end_date`, `current`) VALUES (NULL, '$version', '$adress_id', '$person_id', '$is_main', '$postindex', '$region', '$city', '$area', '$adress', '$worktype', '$isvalid', '$income_date', '$income_number', '$edit_name', '$start_date', '$end_date', '$current');";
mysqli_query($link,$sql);
$sql = "UPDATE `adress` SET `isvalid` = '1', end_date = '$now', current = '0' WHERE `adress`.`id` = $id;";
mysqli_query($link,$sql);
header("Location: ../info_menu.php?id=".$now_id);


?>