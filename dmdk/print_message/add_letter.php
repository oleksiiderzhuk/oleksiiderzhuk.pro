<?php 
session_start();
include('../probirka_db_functions.php');
$link = probirka_db_connect();

?>
<?php date_default_timezone_set('Europe/Kiev');?>
<?php
$id = $_GET['id'];
$sql = "SELECT * FROM person WHERE id=$id";
$person_id_result = mysqli_query($link, $sql);
$person = mysqli_fetch_array($person_id_result);
$person_id = $person['person_id'];
$sql = "SELECT DATE_FORMAT(income_date,'%Y-%m-%d') as 'i_date', income_number FROM adress WHERE person_id = $person_id AND is_main = 1";

$income_adress_result = mysqli_query($link, $sql);
$income_adress = mysqli_fetch_array($income_adress_result);
$income_date = $income_adress['i_date'];
$income_number = $income_adress['income_number'];

$sql = "
SELECT * FROM adress 
WHERE person_id = '$person_id ' 
  AND is_main = 1 
  AND (current = 1 OR current = 3)
  AND VERSION = (SELECT MAX(VERSION) FROM ADRESS WHERE person_id = '$person_id'  AND is_main = 1)
  ";
 // echo $sql;
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
		<div style="margin-left: 66px; margin-top: 180px">
		<table style="width: 95%">
				<tbody>
					<tr>
						<td style="width: 50%">
							<p>&nbsp;</p>
						</td>
						<td style="font-family: serif; font-size: 14pt">
							<strong> 
							<?=$opf.'<br>'?>
							<?=$name?></strong>
							<?=$mainadr?>
						</td>
					</tr>
				</tbody>
			</table>
			<p style="font-family: serif; font-size: 14pt; ">Про реєстрацію суб&rsquo;єкта<br>
			господарювання</p>
			
			<p style="text-indent: 47px; text-align: justify; font-family: serif; font-size: 14pt">Міністерство фінансів України повідомляє, що <?=$opf_alias?> <?=$name?> внесено до реєстру суб&rsquo;єктів господарювання, які здійснюють операції з дорогоцінними металами і дорогоцінним камінням, реєстраційний номер <?=$person['regno']?>.</p>
			<p style="text-align: justify">
			<table width=100%><tr><td valign=top width=80px style="font-family: serif; font-size: 14pt">Додаток: </td><td valign=top style="font-family: serif; font-size: 14pt; text-align: justify">реєстраційне повідомлення від <?php echo date("d.m.Y");?> про внесення суб&rsquo;єкта господарювання до реєстру на 1 арк. в 1 прим.</td></tr></table>
			
			</p>
			<br>
	
	<?php
	include('sign_letter.php'); 
	?>

			<br> <br><br><br> <br><br><br> <br><br><br> <br><br><br>
			<p style="font-family: serif; font-size: 10pt;"><?=$_SESSION['user']?></p>
			</div>
		</textarea>
	</form>

</body>
</html>