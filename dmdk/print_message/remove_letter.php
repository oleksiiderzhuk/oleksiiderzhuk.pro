<?php 
session_start();
include('../probirka_db_functions.php');
$link = probirka_db_connect();

?>
<?php date_default_timezone_set('Europe/Kiev');?>
<?php
$id = $_GET['id'];
$ino = $_GET['ino'];
$indate = $_GET['indate'];
$sql = "SELECT * FROM person WHERE id=$id";
$person_id_result = mysqli_query($link, $sql);
$person = mysqli_fetch_array($person_id_result);
$opf_id=$person['opf_id'];

if ($opf_id=='0') {
$opf[0]='Фізична особа-підприємець';
$opf[1]='ФОП';
}
else {
$opf[0]=show_opf_name($person['opf_id']);
$opf[1]=show_opf_alias($person['opf_id']);
}
$person_id = $person['person_id'];
$sql = "SELECT DATE_FORMAT(income_date,'%Y-%m-%d') as 'i_date', income_number FROM adress WHERE person_id = $person_id AND is_main = 1";

$income_adress_result = mysqli_query($link, $sql);
$income_adress = mysqli_fetch_array($income_adress_result);
$income_date = $income_adress['i_date'];
$income_number = $income_adress['income_number'];
$today=date('d.m.Y');
$sql = "
SELECT * FROM adress 
WHERE person_id = '$person_id ' 
  AND is_main = 1 
  AND (current = 1 OR current = 3)
  AND VERSION = (SELECT MAX(VERSION) FROM ADRESS WHERE person_id = '$person_id' AND is_main = 1)
";
$main_adr_result = mysqli_query($link, $sql);
$main_adr = mysqli_fetch_array($main_adr_result);

$name= $person['name'];
$mainadr='';
$mainadr .= '<br>'.$main_adr['adress'];
if ($main_adr['city']) {
	$mainadr.="<br>".$main_adr['city'];
}
if ($main_adr['area']) {
	$mainadr.="<br>".$main_adr['area'];
}

$region=$main_adr['region'];

if (($region)&&($region!=='1')) {

	$mainadr.="<br>".show_region_name($region);
}
if ($main_adr['postindex']) {
	$mainadr.="<br>".$main_adr['postindex'];
}
$regno = $person['regno'];
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php include("tinymce_header.php"); ?>
</head>
<body>
	<form method="post" style="margin-left: 200px">
		<textarea id="myeditablediv">
			<div style="margin-left: 66px; margin-top: 220px">
			<table style="width: 95%">
				<tbody>
					<tr>
						<td style="width: 50%">
							<p>&nbsp;</p>
						</td>
						<td style="font-family: serif; font-size: 14pt" >
							<b><?=$opf[0].'<br>'.$name?></b>
							<?=$mainadr?>
						</td>
					</tr>
				</tbody>
			</table>
			<p style="font-family: serif; font-size: 14pt">Про реєстрацію суб&rsquo;єкта<br>
			господарювання</p>
			<p style="text-indent: 47px; text-align: justify; font-family: serif; font-size: 14pt">Міністерство фінансів України повідомляє, що <?=$opf[1].' '.$name?> виключено з реєстру суб'єктів господарювання, які здійснюють операції з дорогоцінними металами і дорогоцінним камінням (реєстраційний номер <?=$regno?>).</p>
			
			<table width=100%><tr><td valign=top width=80px style="font-family: serif; font-size: 14pt">Додаток: </td><td valign=top style="font-family: serif; font-size: 14pt; text-align: justify">реєстраційне повідомлення від <?=$today?> про виключення суб'єкта господарювання із реєстру на 1 арк. в 1 прим. </td></tr></table>
			

		<?php
		include('sign_letter.php');
		?>
		

			<br> <br><br><br> <br><br><br> <br><br><br> 
			
			<p style="font-family: serif; font-size: 10pt"><?php echo ''.$_SESSION['user'].'';?></p>
		</div>
		</textarea>
	</form>

</body>
</html>