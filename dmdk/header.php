<?php
session_start();
error_reporting(1);
$_SESSION['can_edit'] = '1';
if(isset($_SESSION['auth_time']) and time()-$_SESSION['auth_time']>600)
{

	unset($_SESSION['auth_time']);
	header("Location: system/exit.php");
}

if ($_SERVER['PHP_SELF'] != '/index.php')
{
/*	if(!$_SESSION['user'])
	{
		header("Location: index.php");
	}
*/	
}
include('probirka_db_functions.php');
$link = probirka_db_connect();
$sql = "SELECT 
COUNT(id) 
FROM
oleksii_derzhuk.person WHERE date_start >= '2018-03-01 00:00:00' and state = '1' || state='3'";

		//echo $sql."<br>";

$person_count = mysqli_query($link, $sql);
$row = mysqli_fetch_array($person_count);
$count= $row['COUNT(id)'];

$sql = "SELECT COUNT(*) FROM person WHERE state='1' and person_id IN 
(SELECT person_id FROM adress WHERE (
	worktype LIKE '%,2,%' 
	OR worktype LIKE '%,2' 
	OR worktype LIKE '2,%' 
	OR worktype = '2'
)
 	and current = '1'
) ";

		//echo $sql."<br>";

$producers_count = mysqli_query($link, $sql);
$row = mysqli_fetch_array($producers_count);
$prod_count= $row['COUNT(*)'];

//-----------------------
$sql = "SELECT COUNT(*) FROM imen where is_valid = '1'";


		//echo $sql."<br>";

$imen_count = mysqli_query($link, $sql);
$row = mysqli_fetch_array($imen_count);
$im_count= $row['COUNT(*)'];

//-----------------
$sql = "SELECT COUNT(*) FROM person where state = '2'";


		//echo $sql."<br>";

$stopped_count = mysqli_query($link, $sql);
$row = mysqli_fetch_array($stopped_count);
$stopped_count_persons= $row['COUNT(*)'];

//-----------------
$sql = "SELECT * FROM imen WHERE is_valid = '1' ORDER BY start_date DESC";


		//echo $sql."<br>";

$imen_last = mysqli_query($link, $sql);
$row = mysqli_fetch_array($imen_last);
$im_last= $row['cipher'];
$person_id = $row['person_id'];
$sql = "SELECT id FROM person WHERE person_id = '$person_id'";

//echo $sql."<br>";
$imen_last_person_id = mysqli_query($link, $sql);
$row = mysqli_fetch_array($imen_last_person_id);
$imen_last_person = $row['id'];


//--------------------
$sql = "SELECT count(*) FROM person WHERE person_id in 
				(select person_id from activity_finish) AND state = '1'";
			

				$activity_finish_query = mysqli_query($link, $sql);
				$row = mysqli_fetch_array($activity_finish_query);
				$activity_finish_num= $row['count(*)'];

//---------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<title>Реєстр суб'єктів</title>
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
    <link rel="SHORTCUT ICON" href="/img/coins.ico" type="image/x-icon">

</head>
<body style=''>
	<div class="container-fluid">
		<div class="row" style="margin-top: 15px;">
			<div class="col-md-12">
				<div style="background-color: rgba(36,105,203,0.26); border-radius: 10px;">
				<div style="padding:1%;">
					<a href="main.php"><img src="img/header/logo.png" height="103px" align="left"></a>
					<div class="text">
						<h4>РЕЄСТР</h4>
						<h4>СУБ'ЄКТІВ</h4>
						<h4>ГОСПОДАРЮВАННЯ</h4>
					</div>
					
					
					<!-- <div  style="float: right; margin-top: -110px; margin-right: 25px">
						<h4>Управління</h4>
						<h4>нагляду</h4>
						<h4>та звітності</h4>
					</div> -->

				</div>
			</div>
		</div>
	</div>
</div>

	<center><a href="../img/dmdk_author.jpg">
				<b>Авторське свідоцтво на твір</b>
	</a></center>

<?php
	
$us= $_SESSION['user_id'];
if ($us == '10') {
	echo "<img src='img/header/butterfly.png' style='position: absolute; margin-top: -120px; margin-left: 300px'>";
}
$user = $_SESSION['user'];
$user = ' ';
echo "<a style=\"margin-left: 2%\"></a>";
if ($user) {
	echo "<a>".$user."</a> <a href=\"system/exit.php\"></a> &nbsp;&nbsp;";

if ($_SESSION['can_edit'] == '1') {
echo "<center style='margin-top: -25px'>Всі суб'єкти: 
<a  title=\"Загальна кількість суб'єктів в реєстрі\">
<b>$count</b>
</a> &nbsp;";
if (($_SESSION['can_edit'] == '2')||($_SESSION['can_edit'] == '1')) {
//echo "&nbsp;<a href='reestr_stat.php'><u>Статистика заповнення реєстру</u></a>";
}
echo "
Виробники: 
<a href='manufacturers.php' title=\"Кількість виробників в реєстрі\">
<b><u>$prod_count</u></b>
</a>&nbsp; 
<a href=\"imens.php\" style=\"text-decoration: underline\"> Іменники:</a>
<a href='last_imen.php' title=\"Кількість іменників в реєстрі\"></a>
<a href=\"imen_lords.php\"><b><u>$im_count</u></b></a>
<a href='last_imen.php' title=\"Кількість іменників в реєстрі\"></a>&nbsp; 
Припинили діяльність: 
<a href=\"archieve.php\"><b><u>$stopped_count_persons</u></b></a>";
if (($_SESSION['can_edit'] == '1')||($_SESSION['can_edit'] == '2')) {
echo "&nbsp;Останній іменник: <a href=\"info_menu.php?id=$imen_last_person\"><u><b style='color: red'>$im_last</b></u></a>";
echo "&nbsp;Припинення: <a href=\"activity_finish.php\"><u><b style='color: blue'>".$activity_finish_num."</b></u></a></center>";
}
/*echo "	<select name=\"opf_id\">";
			$link = probirka_db_connect();
			$sql = "SELECT * FROM opf";
			$result = mysqli_query($link, $sql);
			while($temp = mysqli_fetch_assoc($result)){
				if ($temp['opf_name']!=='Фізична особа - підприємець') {
					echo "<option >".$temp['opf_name']."</option>";
				}
			}
echo '	</select>';*/

}
}
?>