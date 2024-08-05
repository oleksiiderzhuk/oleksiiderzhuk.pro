<?php 
session_start();
include('header.php'); ?>
<?php
date_default_timezone_set('UTC');
$link = probirka_db_connect();
	//-----------------Поля ввода-------------------------------
if ($_POST['pre'])
{
	$pre = $_POST['pre'];
	$sql = "UPDATE person SET islast = 0 WHERE id = $pre";
	mysqli_query($link, $sql);
}
$income_number = $_POST['income_number'];
$income_date = $_POST['income_date'];
$number = $_POST['number'];
$surname = $_POST['surname'];
$name = $_POST['name'];
$patronymic = $_POST['patronymic'];
$ipn = $_POST['ipn'];
$pasport = $_POST['pasport'];
$pasport_info = $_POST['pasport_info'];
$index = $_POST['index'];
$area = $_POST['area'];
$district = $_POST['district'];
$town = $_POST['town'];
$street = $_POST['street'];
$house = $_POST['house'];
$flat = $_POST['flat'];
$phone = $_POST['phone'];
$mail = $_POST['mail'];
$registration_document = $_POST['registration_document'];
	$_SESSION['name']=$surname." ".$name." ".$patronymic;
	$_SESSION['adr']=$index.' '.$area.' '.$district.' '.$town.' '.$street.' '.$house.' '.$flat;
	$_SESSION['income_date']=$income_date;
	$_SESSION['income_number']=$income_number;
	$_SESSION['number']=$number;
if($_POST['registration_date'])
{
	$registration_date = $_POST['registration_date'];
}
else
{
	$registration_date = date("d.m.Y");
}
$opf_id = $_POST['opf_id'];

$type = $_POST['type'];
if ($_POST['individual_works'])
{
	$individual_works=$_POST['individual_works'];
}
	//-----------------Конец поля ввода-------------------------------
$opf_name = $_POST['opf_name'];

$opf_id=opf_whatid($opf_name);
//-----------------Запись в таблицу person------------------------------
if ($_POST['pre'])
{
	if(!$type)
	{

		$sql = "INSERT INTO person (id, regno, isjur, pre, islast, name, edrpou, unzr, issue, opf_id, reg, regdate, date, income_date, income_number) VALUES (NULL, '$number', '0', '$pre', '1', '$surname $name $patronymic', '$ipn', '$pasport', '$pasport_info', '$opf_id', '$registration_document', '$registration_date', CURRENT_TIMESTAMP, '$income_date', '$income_number')";
	}
	else
	{
		$sql = "INSERT INTO person (id, regno, isjur, pre, islast, name, edrpou, unzr, issue, opf_id, reg, regdate, date, income_date, income_number) VALUES (NULL, '$number', '1', '$pre', '1', '$surname $name $patronymic', '$ipn', '$pasport', '$pasport_info', '$opf_id', '$registration_document', '$registration_date', CURRENT_TIMESTAMP, '$income_date', '$income_number')";
	}
}
else
{
	if(!$type)
	{
		$sql = "INSERT INTO person (id, regno, isjur, pre, islast, name, edrpou, unzr, issue, opf_id, reg, regdate, date, income_date, income_number) VALUES (NULL, '$number', '0', NULL, '1', '$surname $name $patronymic', '$ipn', '$pasport', '$pasport_info', '$opf_id', '$registration_document', '$registration_date', CURRENT_TIMESTAMP, '$income_date', '$income_number')";
	}
	else
	{
		$sql = "INSERT INTO person (id, regno, isjur, pre, islast, name, edrpou, unzr, issue, opf_id, reg, regdate, date, income_date, income_number) VALUES (NULL, '$number', '1', NULL, '1', '$name', '$ipn', NULL, NULL, '$opf_id', '$registration_document', '$registration_date', CURRENT_TIMESTAMP, '$income_date', '$income_number')";
	}

}
echo $sql."<br>";
//echo $opf_id."<br>";
mysqli_query($link, $sql);
	//-----------------Конец записи в таблицу person------------------------	

	//-----------------Поиск id------------------------------
$last_id = mysqli_insert_id($link);
	//-----------------Конец поиска id-----------------------

	//-----------------Копирование цепочки адресов-----------
if($_POST['pre'])
{
	$sql = "SELECT * FROM adress WHERE id = $pre";
	$result_test = mysqli_query($link, $sql);
	foreach ($result_test as $value) 
	{
		$isjur = $value['isjur'];
		$postindex = $value['postindex'];
		$region = $value['region'];
		$area = $value['area'];
		$city = $value['city'];
		$adress = $value['adress'];
		$worktype = $value['worktype'];
		$isvalid = $value['isvalid'];
		$date = $value['date'];
		if($type)
		{
			$sql = "INSERT INTO adress (adressid, id, isjur, postindex, region, area, city, adress, worktype, isvalid, date, upd, edit_name, regdate, regno) VALUES (NULL, '$last_id', '0', '$postindex', '$region', '$area', '$city', '$adress', '$worktype', '$isvalid', '$date', NULL, NULL, $income_date, $income_number);";
		}
		else
		{
			$sql = "INSERT INTO adress (adressid, id, isjur, postindex, region, area, city, adress, worktype, isvalid, date, upd, edit_name, regdate, regno) VALUES (NULL, '$last_id', '0', '$postindex', '$region', '$area', '$city', '$adress', '$worktype', '$isvalid', '$date', NULL, NULL, $income_date, $income_number);";
		}
		echo 'adr: '.$sql."<br>";
		mysqli_query($link, $sql);
	}
}
	//-----------------Конец копирование адресов--------------

$income_number = $_POST['income_number'];
$income_date = $_POST['income_d qate'];
$income_date = date('Y-m-d', strtotime($income_date));
$number = $_POST['number'];
$surname = $_POST['surname'];
$name = $_POST['name'];
$patronymic = $_POST['patronymic'];
$ipn = $_POST['ipn'];
$pasport = $_POST['pasport'];
$pasport_info = $_POST['pasport_info'];
$index = $_POST['index'];
$area = $_POST['area'];
$district = $_POST['district'];
$town = $_POST['town'];
$street = $_POST['street'];
$house = $_POST['house'];
$flat = $_POST['flat'];
$phone = $_POST['phone'];
$mail = $_POST['mail'];
$registration_document = $_POST['registration_document'];
if($_POST['registration_date'])
{
	$registration_date = $_POST['registration_date'];
}
else
{
	$registration_date = date("d.m.Y");
}

$type = $_POST['type'];
if ($_POST['individual_works'])
{
	$individual_works=$_POST['individual_works'];
}

	//-----------------Запись в таблицу adress------------------------------
if($type)
{
	if ($_POST['individual_works'])
	{
		foreach ($individual_works as $var)
		{
			$sql_work = "SELECT workid FROM works WHERE workname = '$var'";
			$result_work = mysqli_query($link, $sql_work);
			$work_id = mysqli_fetch_array($result_work)['workid'];
			$sql = "INSERT INTO adress (adressid, id, isjur, postindex, region, area, city, adress, worktype, isvalid, date, upd, edit_name, regdate, regno) VALUES (NULL, '$last_id', '1', '$index', '$area', '$district', '$town', '$street $house $flat', '$work_id', '1', CURRENT_TIMESTAMP, NULL, NULL, $income_date, $income_number)";
			mysqli_query($link, $sql);
		}
	}
	else
	{
		$sql = "INSERT INTO adress (adressid, id, isjur, postindex, region, area, city, adress, worktype, isvalid, date, upd, edit_name, regdate, regno) VALUES (NULL, '$last_id', '1', '$index', '$area', '$district', '$town', '$street $house $flat', '0', '1', CURRENT_TIMESTAMP, NULL, NULL, $income_date, $income_number)";
		mysqli_query($link, $sql);
	}
}
else
{
	if ($_POST['individual_works'])
	{
		foreach ($individual_works as $var)
		{
			$sql_work = "SELECT workid FROM works WHERE workname = '$var'";
			$result_work = mysqli_query($link, $sql_work);
			$work_id = mysqli_fetch_array($result_work)['workid'];
			$sql = "INSERT INTO adress (adressid, id, isjur, postindex, region, area, city, adress, worktype, isvalid, date, upd, edit_name, regdate, regno) VALUES (NULL, '$last_id', '1', '$index', '$area', '$district', '$town', '$street $house $flat', '$work_id', '1', CURRENT_TIMESTAMP, NULL, NULL, '$income_date', '$income_number')";
			mysqli_query($link, $sql);
		}
	}
	else
	{
		$sql = "INSERT INTO adress (adressid, id, isjur, postindex, region, area, city, adress, worktype, isvalid, date, upd, edit_name, regdate, regno) VALUES (NULL, '$last_id', '1', '$index', '$area', '$district', '$town', '$street $house $flat', '0', '1', CURRENT_TIMESTAMP, NULL, NULL, '$income_date', '$income_number')";
		mysqli_query($link, $sql);
	}
}

	//-----------------Конец записи в таблицу adress-------------------------

	//-----------------Запись в таблицу contacts-----------------------------------
$sql = "INSERT INTO contacts (contactid, id, tel, email, date) VALUES (NULL, '$last_id', '$phone', '$mail', CURRENT_TIMESTAMP)";
mysqli_query($link, $sql);
	//-----------------Конец записи в таблицу contacts------------------------------


?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="jumbotron" style="margin-top: 50px;">
				
				<?php if(!$type){?>
				<h5>Фізична особа</h5>
				<p>Реєстраційний номер: <?=$number?><br>
					<h2><?php echo $surname." ".$name." ".$patronymic ?></h2>
					<?='<h4>'.$ipn.'</h4>'?>
					<?=$pasport.' '.$pasport_info?><br>
					<?='<b><u>'.$index.' '.$area.' '.$district.' '.$town.' '.$street.' '.$house.' '.$flat.'</u></b>'?><br>
					<?php
					if ($phone!=='') {
					echo "+380";
					}
					?>
					<?=$phone.' '.$mail?>
					
					</p>
					<?php
					if ($_POST['individual_works']) {
					echo "<p><br>";
					}
					?>
					
						<ol>
							<?php
							if ($_POST['individual_works'])
							{
								$individual_works=$_POST['individual_works'];
								foreach ($individual_works as $var)
								{
									echo "<li>".$var.'</li>';
								}
							}
							?>
						</ol>
					</p>
					<?php } 
					else {?>
					<h5>Юридична особа</h5>
					<p>	Реєстраційний номер: <?=$number?><br>
						<?php 

						echo " <h2>".$surname." ".$name." ".$patronymic ?>
						
						</h2><h4><?=$opf_name?></h4>
						<h4><?=$ipn?></h4>
						<?='<b><u>'.$index.' '.$area.' '.$district.' '.$town.' '.$street.' '.$house.' '.$flat.'</u></b>'?><br>
						<?php
						if ($phone!=='') {
						echo "+380";
						}
						?>
						<?=$phone.' '.$mail?></p>
					<?php
					if ($_POST['individual_works']) {
					echo "<p>Види робіт, що ведуться за юридичною адресою:<br>";
					}
					?>
							<ol>
								<?php
								if ($_POST['individual_works'])
								{
									$individual_works=$_POST['individual_works'];
									foreach ($individual_works as $var)
									{
										echo "<li>".$var.'</li>';
									}
								}
								?>
							</ol>
						</p>
						<?php }?>
						<form target="_blank" action="print_message/print.php" method="post">
							<input type="text" name="income_number" value="<?=$income_number?>" class="d-n"> 
							<input type="text" name="income_date" value="<?=$income_date?>" class="d-n"> 
							<input type="text" name="number" value="<?=$number?>" class="d-n"> 
							<input type="text" name="surname" value="<?=$surname?>" class="d-n"> 
							<input type="text" name="name" value="<?=$name?>" class="d-n"> 
							<input type="text" name="patronymic" value="<?=$patronymic?>" class="d-n"> 
							<input type="text" name="ipn" value="<?=$ipn?>" class="d-n"> 
							<input type="text" name="pasport" value="<?=$pasport?>" class="d-n"> 
							<input type="text" name="pasport_info" value="<?=$pasport_info?>" class="d-n"> 
							<input type="text" name="index" value="<?=$index?>" class="d-n"> 
							<input type="text" name="area" value="<?=$area?>" class="d-n"> 
							<input type="text" name="district" value="<?=$district?>" class="d-n"> 
							<input type="text" name="town" value="<?=$town?>" class="d-n"> 
							<input type="text" name="street" value="<?=$street?>" class="d-n"> 
							<input type="text" name="house" value="<?=$house?>" class="d-n"> 
							<input type="text" name="flat" value="<?=$flat?>" class="d-n"> 
							<input type="text" name="phone" value="<?=$phone?>" class="d-n"> 
							<input type="text" name="mail" value="<?=$mail?>" class="d-n"> 
							<input type="text" name="registration_document" value="<?=$registration_document?>" class="d-n"> 
							<input type="text" name="registration_date" value="<?=$registration_date?>" class="d-n"> 
							<select multiple="multiple" name="individual_works[]" class="d-n">
								<?php
								foreach ($individual_works as $var)
								{
									echo "<option selected>".$var.'</option>';
								}
								?>
							</select>
							<input type="submit" name="" class="btn btn-warning" value="Реєстраційне повідомлення">
							
							<a href="main.php"><button type="button" class="btn btn-warning">На головну</button></a>
							<a href="info_menu.php?id=<?=$last_id?>"><button type="button" class="btn btn-warning">Додати адреси</button></a>
						</form>

					</div>
				</div>
			</div>
		</div>
		
		<?php 
		$name=$name['name'];
		
if ( $_SESSION['change']=='edit' ) {
echo "<input type=\"text\" name=\"name\" value=\"$name\" class=\"d-n\">";
					
echo "<script type='text/javascript'>
		window.open('print_message/3.php', '_blank'); </script>";

}
else {
//echo "<script type='text/javascript'> window.open('print_message/1.php', '_blank'); </script>";
}
		?>
		
		
		
		
		

		<?php include('footer.php'); 
		echo '<script type="text/javascript">'; 
	echo "window.location.href=\"info_menu.php?id=$last_id\";"; 
	echo '</script>';
		?>
		
