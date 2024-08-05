<?php
session_start();
$edit_name = $_SESSION['user_id'];
include('../probirka_db_functions.php');

$link = probirka_db_connect();
$current_date = date('Y-m-d H:i:s');
$id = $_POST['id'];
$now_id = $_POST['now_id'];
$postindex = $_POST['postindex'];
$region = show_region_id($_POST['region']);
$city = mysqli_real_escape_string($link,$_POST['city']);
$area = mysqli_real_escape_string($link,$_POST['area']);
$adress = mysqli_real_escape_string($link,$_POST['adress']);

$worktype = $_POST['worktype'];
$flag = True;
if($_POST['worktype'])
{
	foreach ($worktype as $value) 
	{
		if($flag)
		{
			$value= addslashes($value);
			$worktype_array = show_work_id($value);
			$flag = False;
		}
		else
		{
			$value= addslashes($value);
			$worktype_array = $worktype_array.",".show_work_id($value);
		}
	}
}
else
{
	$worktype_array = "0";
}
$income_date = $_POST['income_date'];
$income_number = $_POST['income_number'];

$sql = "SELECT * FROM adress WHERE id = $id";
$previous_adress_result = mysqli_query($link, $sql);
$previous_adress = mysqli_fetch_array($previous_adress_result);

$next_version = $previous_adress['version'] + 1;
$next_adress_id = $previous_adress['adress_id'];
$next_person_id = $previous_adress['person_id'];
$next_is_main = $previous_adress['is_main'];
$next_isvalid = $previous_adress['isvalid'];

$sql = "INSERT INTO adress (id, version, adress_id, person_id, is_main, postindex, region, city, area, adress, worktype, isvalid, income_date, income_number, edit_name, start_date, end_date, current) VALUES (NULL, $next_version, $next_adress_id, $next_person_id, $next_is_main, '$postindex', '$region', '$city', '$area', '$adress', '$worktype_array', $next_isvalid, '$income_date', '$income_number', '$edit_name', '$current_date', '6666-07-13 13:13:13', 1);";
//echo $sql."<br>";
mysqli_query($link, $sql);

$sql = "UPDATE adress SET end_date = '$current_date', current = 0 WHERE id = $id;";
//echo $sql."<br>";
mysqli_query($link, $sql);

header("Location: ../info_menu.php?id=$now_id");
?>

