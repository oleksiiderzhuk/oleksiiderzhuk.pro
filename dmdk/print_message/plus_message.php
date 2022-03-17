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

$sql = "SELECT * FROM adress WHERE person_id = $person_id AND is_main = 1 AND current = 1";
$main_adr_result = mysqli_query($link, $sql);
$main_adr = mysqli_fetch_array($main_adr_result);

$name= $person['name'];
$mainadr='';
$mainadr .= ''.$main_adr['adress'];
if ($main_adr['city']) {
	$mainadr.=", ".$main_adr['city'];
}
if ($main_adr['area']) {
	$mainadr.=", ".$main_adr['area'];
}

$region=$main_adr['region'];

if (($region)&&($region!=='1')) {

	$mainadr.=", ".show_region_name($region);
}
if ($main_adr['postindex']) {
	$mainadr.=", ".$main_adr['postindex'].".";
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
		<div style="margin-left: 66px; margin-top: 34px">

			<div style="font-weight: bold; font-size: 18pt"><center>РЕЄСТРАЦІЙНЕ ПОВІДОМЛЕННЯ</center></div>
			<br><br>
			<table style="width: 100%">
				<tbody>
					<tr>
						<td  style="width: 50%">
							<p>&nbsp;</p>
						</td>
						<td style="font-family: serif; font-size: 14pt">
							<p>Кому: <u><?=show_opf_name($person['opf_id'])?> <?=$name?></u></p>
							<p>Місцезнаходження: <u><?=$mainadr?></u></p>
						</td>
					</tr>
				</tbody>
			</table>
			<p style="text-indent: 45px;  text-align: justify; font-family: serif; font-size: 14pt">За результатами розгляду матеріалів, наданих <?=date("d.m.Y", strtotime($income_date))?> № <?=$income_number?>, повідомляється, що до місць провадження діяльності <u><?=show_opf_name($person['opf_id'])?> <?=$name?></u> внесено такі зміни до реєстру суб&rsquo;єктів господарювання, які здійснюють операції з дорогоцінними металами і дорогоцінним камінням.</p>
			<p style="text-align: justify; font-family: serif; font-size: 14pt">
			Розпочато діяльність з дорогоцінними металами і дорогоцінним камінням за такими місцями провадження діяльності:
			</p>
			
			<table class="activity" cellpadding="5px"  cellspacing=0 border=1 width=660>
			<tr>
				<th style="font-family: serif; font-size: 14pt; font-weight: normal; ">Місце провадження діяльності суб'єкта господарювання</th>
				<th style="font-family: serif; font-size: 14pt; font-weight: normal; ">Операції з дорогоцінними металами і дорогоцінним камінням, які здійснює суб'єкт господарювання за місцем провадження діяльності (відповідно до пункту 1.2 розділу I Порядку)</th>
			</tr>
			<tr>
				<th style="font-family: serif; font-size: 14pt; font-weight: normal; ">1</th>
				<th style="font-family: serif; font-size: 14pt; font-weight: normal; ">2</th>
			</tr>
			<?php
			$today=date('Y-m-d');
			$sql="SELECT * FROM adress WHERE person_id=$person_id and worktype not like '0' and start_date like '$today%' and current = '1' and isvalid = '1'";

			$all_adress_today = mysqli_query($link, $sql);
			foreach($all_adress_today as $value):
				$adr='';
				$adr .= ''.$value['adress'];
				 if ($value['city']) {
					$adr.=", ".$value['city'];
				}
				 if ($value['area']) {
					$adr.=", ".$value['area'];
				}
				$region=$value['region'];

				 if (($region)&&($region!=='1')) {

					$adr.=", ".show_region_name($region);
				}
				 if ($value['postindex']) {
					$adr.=", ".$value['postindex'].".";
				}				
				$worktype = explode(",", $value['worktype'])
				?>
				<tr>
					<th style="font-weight: normal; text-align: left; vertical-align: top; font-family: serif; font-size: 14pt"><?=$adr;?></th>
					<th style="font-weight: normal; text-align: left; vertical-align: top; font-family: serif; font-size: 14pt">
					 
						
						<?php mb_internal_encoding('UTF-8');
						foreach($worktype as $t_value):?>
							<?=mb_strtolower(show_work_name($t_value)).';<br>'?>
							<?php endforeach; ?>

						</th>
					</tr>
				<?php endforeach; ?>
			</table>
			<br>
		
		<?php
		include('sign_message.php');
		?>
			

			 <p style="text-indent: 25px;  text-align: justify; font-family: serif; font-size: 14pt">М. П.</p>
			<p style="font-family: serif; font-size: 14pt"><?=ukr_date()?></p>
			</div>
		</textarea>
	</form>

</body>
</html>