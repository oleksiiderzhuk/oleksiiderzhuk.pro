<?php
session_start();
include('../probirka_db_functions.php');

$edit_name = $_SESSION['user_id'];
$link = probirka_db_connect();
$person_id = $_GET['person_id'];
$year = $_POST['year'];
$y = substr($year, -1);
$cipher = $_POST['cipher'];
$cip = substr($cipher, -1);
if (!ctype_digit($cip)) {
$cipher.=$y;
}
$doc_date = $_POST['docdate'];
$reg_date = $_POST['regdate'];

$imen_type_name = $_POST['imen_type'];
$card_num = $_POST['cardnum'];
$current_date = date('Y-m-d H:i:s');
$end_date = '0000-00-00 00:00:00';
$flag = True;
if($imen_type_name)
{
	foreach ($imen_type_name as $value) 
	{
		if($flag)
		{
			$value= addslashes($value);
			$imentype_array = show_imen_id($value);
			$flag = False;
		}
		else
		{
			$value= addslashes($value);
			$imentype_array = $imentype_array.",".show_imen_id($value);
		}
	}
}
else
{
	$imentype_array = "0";
}

$check = False;
if (check_imen('imen','cipher', $cipher, $person_id)==1)
{
	$check = True;
}
if($check==True)
{

	header("Location: ../cipher_error.php");

	
}
else {
$sql = "INSERT INTO imen (id, person_id, cipher, doc_date, reg_date, imen_type_id, card_num, edit_name, start_date, end_date, is_valid) VALUES (NULL, '$person_id', '$cipher', '$doc_date', '$reg_date', '$imentype_array', '$card_num', '$edit_name', '$current_date', '$end_date', '1');";
//echo $sql."<br>";
mysqli_query($link, $sql);

$sql = "SELECT id FROM person WHERE person_id = $person_id AND state = 1";

$id_result = mysqli_query($link, $sql);
$id = mysqli_fetch_array($id_result)['id'];
header("Location: ../imen.php?person_id=$person_id");
}
?>

