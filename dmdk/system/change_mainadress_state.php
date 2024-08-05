<?php
session_start();
$edit_name = $_SESSION['user'];
include('../probirka_db_functions.php');
$now_id = $_POST['id'];
$person_id = $_POST['person_id'];

$link = probirka_db_connect();
$current_date = date('Y-m-d H:i:s');
$now_id = $_POST['id'];

$sql = "SELECT * FROM person where id = '$now_id'";
$result = mysqli_query($link, $sql);
foreach ($result as $value)	{
	$sql = "SELECT * FROM adress where person_id = '$person_id' and is_main = '1'";
	$result = mysqli_query($link, $sql);
	foreach ($result as $value)	{
		$adress_id = $value['id'];
		
		if ($value['current']=='1') {
			$sql = "UPDATE adress SET current = '3' WHERE id = '$adress_id'";
			mysqli_query($link, $sql);
		}
		else {
			$sql = "UPDATE adress SET current = '1' WHERE id = '$adress_id'";
			mysqli_query($link, $sql);
		}
	}

}

header("Location: ../info_menu.php?id=$now_id");
?>

