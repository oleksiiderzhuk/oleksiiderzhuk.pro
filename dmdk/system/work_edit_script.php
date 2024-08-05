<?php
include("../probirka_db_functions.php");
$link = probirka_db_connect();
$id = $_POST['id'];
$now_id = $_POST['now_id'];
$sql = "SELECT date FROM adress WHERE `adressid` = $id ";
$result = mysqli_query($link, $sql);
$date = mysqli_fetch_array($result)['date'];
$sql = "SELECT * FROM adress WHERE adressid= $id ORDER BY date";
$result_r2 = mysqli_query($link, $sql);
$r2 =  mysqli_fetch_array($result_r2);
$postindex = $r2['postindex'];
$region = $r2['region'];
$area = $r2['area'];
$city = $r2['city'];
$adress = $r2['adress'];
$isvalid = $r2['isvalid'];
$date = $r2['date'];
$upd = $r2['upd'];



if ($_POST['individual_works'])
{
	$individual_works=$_POST['individual_works'];
	$sql = "SELECT * FROM adress WHERE date = '".$date."' AND id = $now_id";
	$date_result = mysqli_query($link, $sql);
	foreach ($date_result as $date_value) 
	{
		$sql = "DELETE FROM adress WHERE adressid = ".$date_value['adressid'];
		mysqli_query($link, $sql);
	}
	foreach ($individual_works as $var)
	{
		$sql_work = "SELECT workid FROM works WHERE workname = '$var'";
		$result_work = mysqli_query($link, $sql_work);
		$work_id = mysqli_fetch_array($result_work)['workid'];
		$sql = "INSERT INTO adress (adressid, id, isjur, postindex, region, area, city, adress, worktype, isvalid, date, upd, edit_name) VALUES (NULL, '$now_id', '1', '$postindex', '$region', '$area', '$city', '$adress', '$work_id', '1', CURRENT_TIMESTAMP, NULL, NULL)";
		mysqli_query($link, $sql);
	}
}
header("Location:../info_menu.php?id=$now_id")
?>