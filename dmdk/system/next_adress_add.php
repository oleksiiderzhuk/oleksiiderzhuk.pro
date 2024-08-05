<?php
session_start();
$edit_name = $_SESSION['user_id'];
include('../probirka_db_functions.php');

$link = probirka_db_connect();
$current_date = date('Y-m-d H:i:s');
$person_id = $_POST['person_id'];
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

$sql = "SELECT MAX(adress_id) as id FROM adress";
$result = mysqli_query($link, $sql);
$max_adress_id = mysqli_fetch_array($result)['id']+1; 

$sql = "INSERT INTO adress (id, version, adress_id, person_id, is_main, postindex, region, city, area, adress, worktype, isvalid, income_date, income_number, edit_name, start_date, end_date, current) VALUES (NULL, 1, $max_adress_id, $person_id, 0, '$postindex', '$region', '$city', '$area', '$adress', '$worktype_array', 1, '$income_date', '$income_number', '$edit_name', '$current_date', '6666-07-13 13:13:13', 1);";
mysqli_query($link, $sql);

$sql = "SELECT id FROM person WHERE person_id = $person_id AND (state = 1 || state = 3)";
$id_result = mysqli_query($link, $sql);
$id = mysqli_fetch_array($id_result)['id'];
header("Location: ../info_menu.php?id=$id");
?>

