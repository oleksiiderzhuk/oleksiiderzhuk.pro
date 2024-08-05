<?php 
session_start();
include('../probirka_db_functions.php');
$link = probirka_db_connect();

?>
<?php date_default_timezone_set('Europe/Kiev');?>
<?php
$person_id = $_POST['person_id'];
$card_num = $_POST['card_num'];
$year = $_POST['year'];
$sql = "SELECT * FROM person WHERE person_id=$person_id order by date_start desc limit 1";
$person_result = mysqli_query($link, $sql);
$person = mysqli_fetch_array($person_result);
$sql = "
SELECT * FROM adress 
WHERE person_id = '$person_id ' 
  AND is_main = 1 
  AND (current = 1 OR current = 3)
  AND VERSION = (SELECT MAX(VERSION) FROM ADRESS WHERE person_id = '$person_id'  AND is_main = 1)
  ";
$main_adr_result = mysqli_query($link, $sql);
$main_adr = mysqli_fetch_array($main_adr_result);
$opfid=$person['opf_id'];
if ($opfid==0) {
$opf=show_opf_name($person['opf_id']);
$opf_alias = show_opf_alias($person['opf_id']);
}
else {
$opf=show_opf_name($person['opf_id']);
$opf_alias = show_opf_alias($person['opf_id']);
}
$name= $person['name'];
$mainadr='';
$mainadr .= '<br>'.$main_adr['adress'];
if ($main_adr['city']) {
	$mainadr.=",<br>".$main_adr['city'];
}
if ($main_adr['area']) {
	$mainadr.=",<br>".$main_adr['area'];
}

$region=$main_adr['region'];

if (($region)&&($region!=='1')) {

	$mainadr.=",<br>".show_region_name($region);
}
if ($main_adr['postindex']) {
	$mainadr.=",<br>".$main_adr['postindex']."";
}

$regno = $person['regno'];
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="windows-1251">
	<?php include("tinymce_header.php"); ?>

</head>
<body>
	<form method="post" style="margin-left: 200px">
		<textarea id="myeditablediv">
		<div style="margin-left: 66px; margin-top: 180px"><br>
		<?php
			include('header_letter.php');
		?>
	
			<br>
			<p style="font-family: serif; font-size: 14pt; ">
			Про реєстрацію відбитків іменника<br>
			на <?=$year?> рік
			</p>
			
			<p style="text-indent: 47px; text-align: justify; font-family: serif; font-size: 14pt">Міністерство фінансів України розглянуло заяву  <?=$opf_alias?> <?=$name?> про реєстрацію відбитка іменника і направляє один примірник реєстраційної картки із зареєстрованим до використання відбитком іменника.</p>
			<p style="text-align: justify">
			<table width=100%><tr><td valign=top width=12% style="font-family: serif; font-size: 14pt">Додаток: </td><td valign=top style="font-family: serif; font-size: 14pt; text-align: justify">реєстраційна картка № <?=$card_num?> в 1 прим.</td></tr></table>
			
			</p>
			<br>

		<?php
		include('sign_letter.php');
		?>
		

			<br> <br><br><br> <br><br><br> <br><br><br> <br><br><br><br>
			<p style="font-family: serif; font-size: 10pt;"><?=$_SESSION['user']?></p>
			</div>
		</textarea>
	</form>

</body>
</html>