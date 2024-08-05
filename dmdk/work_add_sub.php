<?php
session_start();
include_once "probirka_db_functions.php";
$link = probirka_db_connect();
$edit_name = $_SESSION['user'];
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
$income_date = $_POST['income_date'];
$income_number = mysqli_real_escape_string($link,$_POST['income_number']);
$regno = $_POST['regno'];
if ($_POST['opf_id'])
{
	$opf_id = show_opf_id($_POST['opf_id']);
}
else
{
	$opf_id = 0;
}

$name = mysqli_real_escape_string($link,$_POST['name']);
$tax_code = mysqli_real_escape_string($link,$_POST['tax_code']);
$postindex = mysqli_real_escape_string($link,$_POST['postindex']);
$region = show_region_id($_POST['region']);
$city = mysqli_real_escape_string($link,$_POST['city']);
$area = mysqli_real_escape_string($link,$_POST['area']);
$adress = mysqli_real_escape_string($link,$_POST['adress']);
$phone_number = mysqli_real_escape_string($link,$_POST['phone_number']);
$email = mysqli_real_escape_string($link,$_POST['email']);
$reg=mysqli_real_escape_string($link,$_POST['reg1'].$_POST['reg2'].$_POST['reg3'].$_POST['reg4'].$_POST['reg5']);
$reg = mysqli_real_escape_string($link,$reg);
$regdate = mysqli_real_escape_string($link,$_POST['regdate']);
$worktype = mysqli_real_escape_string($link,$_POST['worktype']);
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
$check = False;
if (check_value('person','tax_code', $tax_code)==1)
{
	$check = True;
}
if($check==True)
{

	header("Location: tax_code_error.php");

	
}
else
{
	$sql = "SELECT MAX(person_id) as id FROM person";
	$result = mysqli_query($link, $sql);
	$max_person_id = mysqli_fetch_array($result)['id']+1;

//Записываем информацию в person
	$sql = "INSERT INTO person (id, version, person_id, regno, isjur, name, tax_code, passport_info, opf_id, reg, regdate, edit_name, date_start, date_end, state) VALUES (NULL, 1, $max_person_id, '$regno', '$isjur', '$name', '$tax_code', '$passport_info', $opf_id, '$reg', '$regdate', '$edit_name', '$current_date', '6666-07-13 13:13:13', '1');";
	mysqli_query($link, $sql);

	$sql = "SELECT MAX(adress_id) as id FROM adress";
	$result = mysqli_query($link, $sql);
	$max_adress_id = mysqli_fetch_array($result)['id']+1;

	$edit_name = $_SESSION['user'];
	$sql = "INSERT INTO adress (id, version, adress_id, person_id, is_main, postindex, region, city, area, adress, worktype, isvalid, income_date, income_number, edit_name, start_date, end_date, current) VALUES (NULL, 1, $max_adress_id, $max_person_id, 1, '$postindex', '$region', '$city', '$area', '$adress', '$worktype_array', 1, '$income_date', '$income_number', '$edit_name', '$current_date', '6666-07-13 13:13:13', 1);";
	mysqli_query($link, $sql);

	$sql = "SELECT MAX(contact_id) as id FROM contact";
	$result = mysqli_query($link, $sql);
	$max_contact_id = mysqli_fetch_array($result)['id']+1;

	$sql = "INSERT INTO contact (id, version, contact_id, person_id, phone_number, email, date_start, end_date, current) VALUES (NULL, '1', '$max_contact_id', '$max_person_id', '$phone_number', '$email', '$current_date', '6666-07-13 13:13:13', '1');";
	mysqli_query($link, $sql);

	$sql = "SELECT MAX(id) as t_id FROM person";
	$result = mysqli_query($link, $sql);
	$last_id = mysqli_fetch_array($result)['t_id'];

	header("Location: info_menu.php?id=$last_id");
}



