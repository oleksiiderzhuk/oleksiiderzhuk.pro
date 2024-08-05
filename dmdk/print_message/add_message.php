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
//echo $sql;
$main_adr_result = mysqli_query($link, $sql);
$main_adr = mysqli_fetch_array($main_adr_result);

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
$opf_id=$person['opf_id'];
if ($opf_id==0) {
$opf=show_opf_name($person['opf_id']);
$opf_alias = show_opf_alias($person['opf_id']);
}
else {
$opf=show_opf_name($person['opf_id']);
$opf_alias = show_opf_alias($person['opf_id']);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php include("tinymce_header.php"); ?>
	<style type="text/css">
	
	TD, TH {
	 font-family: serif; 
	 font-size: 14pt;
	}
	</style>
</head>
<body>
	<form method="post" style="margin-left: 200px">
		<textarea id="myeditablediv">
		<div style="margin-left: 66px; margin-top: 34px">
					<div style="font-weight: bold; font-size: 18pt"><center>РЕЄСТРАЦІЙНЕ ПОВІДОМЛЕННЯ</center></div><br>
			<table style="width: 95%" >
				<tbody>
					<tr>
						<td style="width: 330px">
							<p>&nbsp;</p>
						</td>
						<td style="font-family: serif; font-size: 14pt" >
							<p>Кому: <u>
							<?=$opf.'<br>'?>
							<?=$name?></u><br>
							Місцезнаходження: <u><?=$mainadr?></u></p>
						</td>
					</tr>
				</tbody>
			</table>
			<p style="text-indent: 45px; text-align: justify; font-family: serif; font-size: 14pt">За результатами розгляду матеріалів, наданих <?=date("d.m.Y", strtotime($income_date))?> № <?=$income_number?>, повідомляється, що <u><?=$opf_alias?> <?=$name?></u> внесено до реєстру суб&rsquo;єктів господарювання, які здійснюють операції з дорогоцінними металами і дорогоцінним камінням.</p>
			<p style="font-family: serif; font-size: 14pt">
			<span class="bold"><u>Реєстраційні дані:</u></span>
		<br>	
			 реєстраційний номер <b><u><?=$regno?></u></b>
		</p>
		<table class="activity" cellpadding="5px"  cellspacing=0 border=1 width=100%>
			<tr>
				<th style="font-family: serif; font-size: 14pt; font-weight: normal" width=40%>Місце провадження діяльності суб'єкта господарювання</th>
				<th style="font-family: serif; font-size: 14pt; font-weight: normal; ">Операції з дорогоцінними металами і дорогоцінним камінням, які здійснює суб'єкт господарювання за місцем провадження діяльності (відповідно до пункту 1.2 розділу I Порядку)</th>
			</tr>
			<tr>
				<th style="font-family: serif; font-size: 14pt; font-weight: normal; ">1</th>
				<th style="font-family: serif; font-size: 14pt; font-weight: normal; ">2</th>
			</tr>
			<?php
			$today=date('Y-m-d');
			$sql="SELECT * FROM adress WHERE person_id=$person_id AND current = 1 AND worktype!='0' AND worktype!='' AND worktype!='NULL' AND isvalid=1";

			$all_adress_today = mysqli_query($link, $sql);
			foreach($all_adress_today as $value):
				$adr='';
				$region=$value['region'];

				 if (($region)&&($region!=='1')) {

					$adr.=show_region_name($region).', ';
					
				}
				
				if ($value['area']) {
					$adr.=$value['area'].", ";
				}
				 if ($value['city']) {
					$adr.=$value['city'].", ";
				}
				$adr .= $value['adress'];
				
				$worktype = explode(",", $value['worktype'])
				?>
				<tr>
					<th style="font-weight: normal; text-align: center; vertical-align: top; font-family: serif; font-size: 14pt"><?=$adr?></th>
					<th style="font-weight: normal; text-align: justify; vertical-align: top; font-family: serif; font-size: 14pt">
					 
						
						<?php 
						mb_internal_encoding('UTF-8');
						$worktype_count=count($worktype);
						$i=0;
						foreach($worktype as $t_value){
							$i++;
							if ($i<$worktype_count) {
								echo mb_strtolower(show_work_name($t_value)).';<br>';
							}
							else {
						 		echo mb_strtolower(show_work_name($t_value)).'<br>';
							}	
						}
						?>

						</th>
					</tr>
				<?php endforeach; ?>
			</table>
			<br><br>
		
		<?php
		include('sign_message.php');
		?>
			
			<p style="text-indent: 45px; text-align: justify; font-family: serif; font-size: 14pt">М. П.</p>
			<p style="font-family: serif; font-size: 14pt"><?=ukr_date()?></p>
			
			<p></p>
			</div>
		</textarea>
	</form>

</body>
</html>