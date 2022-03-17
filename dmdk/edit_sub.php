<?php
session_start();
include_once "probirka_db_functions.php";
$link = probirka_db_connect();
$edit_name = $_SESSION['user_id'];
$id = $_POST['id'];
//Считываем информацию с POST
if($_POST['type']=='jur')
{
	$isjur = 1;
}
else
{
	$isjur = 0;
}
if($_POST['passport_info'])
{
	$passport_info = mysqli_real_escape_string($link,$_POST['passport_info']);
}
else
{
	$passport_info = "No passport info";
}
$current_date = date('Y-m-d H:i:s');
$income_date = mysqli_real_escape_string($link,$_POST['income_date']);
$income_number = mysqli_real_escape_string($link,$_POST['income_number']);
$regno = $_POST['regno'];
if ($_POST['opf_id'])
{
	$opf_id = show_opf_id(mysqli_real_escape_string($link,$_POST['opf_id']));
}
else
{
	$opf_id = 0;
}

$name = mysqli_real_escape_string($link,$_POST['name']);
$tax_code = mysqli_real_escape_string($link,$_POST['tax_code']);
$phone_number = mysqli_real_escape_string($link,$_POST['phone_number']);
$email = mysqli_real_escape_string($link,$_POST['email']);
$regdate = mysqli_real_escape_string($link,$_POST['regdate']);
$reg=mysqli_real_escape_string($link,$_POST['reg1'].$_POST['reg2'].$_POST['reg3'].$_POST['reg4'].$_POST['reg5']);
$datestopped = mysqli_real_escape_string($link,$_POST['datestopped']);
$regstopped=mysqli_real_escape_string($link,$_POST['regstopped1'].$_POST['regstopped2'].$_POST['regstopped3'].$_POST['regstopped4'].$_POST['regstopped5']);


$datestopping = mysqli_real_escape_string($link,$_POST['datestopping']);



$sql = "SELECT * FROM person WHERE id = $id";
$previous_person_result = mysqli_query($link, $sql);
$previous_person = mysqli_fetch_array($previous_person_result);

$next_version = $previous_person['version'] + 1;
$next_person_id = $previous_person['person_id'];

$sql = "INSERT INTO person (id, version, person_id, regno, isjur, name, tax_code, passport_info, opf_id, reg, regdate, edit_name, date_start, date_end, state) VALUES (NULL, $next_version, $next_person_id, '$regno', '$isjur', '$name', '$tax_code', '$passport_info', $opf_id, '$reg', '$regdate', '$edit_name' ,'$current_date', '6666-07-13 13:13:13', '1');";
mysqli_query($link, $sql);

$sql = "UPDATE person SET date_end = '$current_date', state = '0' WHERE id = $id;";
mysqli_query($link, $sql);

$sql = "SELECT MAX(contact_id) as id FROM contact";
$result = mysqli_query($link, $sql);
$max_contact_id = mysqli_fetch_array($result)['id']+1;

$sql = "SELECT MAX(id) as max_id FROM contact WHERE person_id = $next_person_id";

$result = mysqli_query($link, $sql);
$m_id = mysqli_fetch_array($result)['max_id'];


$sql = "UPDATE contact SET current = 0 WHERE id = $m_id";

mysqli_query($link, $sql);

$sql = "INSERT INTO contact (id, version, contact_id, person_id, phone_number, email, date_start, end_date, current) VALUES (NULL, '1', '$max_contact_id', '$next_person_id', '$phone_number', '$email', '$current_date', '6666-07-13 13:13:13', '1');";

mysqli_query($link, $sql);

$sql = "SELECT MAX(id) as t_id FROM person";
$max_id_result = mysqli_query($link, $sql);
$max_id = mysqli_fetch_array($max_id_result)['t_id'];

$sql = "INSERT INTO activity_finish (id, version, person_id, date_stopped, regno_stopped, date_stopping, regno_stopping, date) VALUES (NULL, 1, $next_person_id, '$datestopped', '$regstopped', '$datestopping', NULL, '$current_date');";
mysqli_query($link, $sql);

header("Location: ../info_menu.php?id=$max_id");