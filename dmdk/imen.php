<?php
include('header.php'); 
$link = probirka_db_connect();
$person_id = $_GET['person_id'];
$sql = "SELECT * FROM person where person_id = $person_id";
$result = mysqli_query($link, $sql);
$pers = mysqli_fetch_array($result);
$pers_id = $pers['id'];
$today=date('Y-m-d');
?>
<div class="container" style="height: 1200px">
	<div class="row" style="margin-top: 60px;">
		<a href="info_menu.php?id=<?=$pers_id?>"><< Назад</a>
<br><br><br>
		<div class="col-md-12">
			<form action="system/next_imen_add.php?person_id=<?=$person_id?>" method="post">
			
				<div class="form-row">

					<div class="form-group col-md-2">			
						<label for="docdate">Дата реєстрації заяви</label>
						<input class="form-control" type="date" name="docdate" required>
					</div>
					<div class="form-group col-md-2">			
						<label for="cipher">Шифр</label>
						<input class="form-control" type="text" name="cipher" placeholder="Шифр іменника" style="letter-spacing: 0.2em" required>
					</div>
					
					<!--
					<div class="form-group col-md-1">			
						<label for="cipher">Рік</label>
						<select class="form-control" name="year" style="letter-spacing: 0em" >
							<option></option>
							<option>2018</option>
							<option>2019</option>
							<option>2020</option>
							<option>2017</option>
						</select>
						
					</div>
					-->
										
					<div class="form-group col-md-4" style="margin-top: -35px">			
						<label for="cipher">Спосіб клеймування</label>
						<select class="form-control" name="imen_type[]" multiple style="height: 135px">
						<?php
							$link = probirka_db_connect();
							$sql = "SELECT * FROM imen_type";
							$result = mysqli_query($link, $sql);

							while($temp = mysqli_fetch_assoc($result))
							{								
								$imen_type = $temp['imen_type_name'];
								echo "<option title=\"$imen_type\">".$imen_type."</option>
								
								";
							}
							?>
						</select>
						
													
					</div>
					<div class="form-group col-md-2">			
						<label for="cardnum">Картка №</label>
						<input class="form-control" type="text" name="cardnum" placeholder="Картка №" style="letter-spacing: 0.3em" >
					</div>
					<div class="form-group col-md-2">			
						<label for="regdate">Дата реєстрації</label>
						<input class="form-control" type="date" name="regdate" value="<?=$today?>" >
					</div>
					
				</div>
				<?php if ($_SESSION['can_edit'] == '1') { ?>
				<input type="submit" name="submit" value="Додати шифр/іменник" class="btn btn-warning">
				<?php } ?>
			</form>




<?php 
	$sql = "SELECT * FROM imen WHERE person_id = '$person_id' and is_valid = '1' order by start_date desc";
	$result = mysqli_query($link, $sql);
	
	echo "
	<br>
	<table width=99% border=0 cellpadding=20px style='margin-left: 0px;'>
	";
	foreach ($result as $temp)	{	
		$card_num = $temp['card_num'];	
		$cip = $temp['cipher'];
		if (substr($cip, -2, 2)=='0:') {
			$year = '2020';
		}
		elseif (ctype_digit(substr($cip, -1))) {
				$year = '201'.substr($cip, -1);
		}
		else {
			$year = '<b>???</b>';
		}
		echo "<tr>
		<td>";
		
		if ($_SESSION['can_edit'] == '1') { 
			echo "
		<form method=\"post\" action=\"print_message/imen_cipher_letter.php\" target=\"_blank\">
		<input type='hidden' name='card_num' value=\"$card_num\">
		<input type='hidden' name='cip' value=\"$cip\">
		<input type='hidden' name='person_id' value=\"$person_id\">
		<input type=submit class='btn btn-warning' value='Лист шифр'>
		</form>
			<br>
		<form method=\"post\" action=\"print_message/imen_letter.php\" target=\"_blank\">
		<input type='hidden' name='year' value=\"$year\">
		<input type='hidden' name='card_num' value=\"$card_num\">
		<input type='hidden' name='person_id' value=\"$person_id\">
		<input type=submit class='btn btn-warning' value='Лист іменник'>
		</form>
		</td>";
		}
		echo "
		<td width=20% title='Дата реєстрації заяви'>";
		$docdate=$temp['doc_date'];
		if ($docdate==null) {
		echo "дд.мм.рррр";
		}
		else echo date("d.m.Y", strtotime($docdate));
		echo "</td>
		<td width=15% title='Шифр / іменник'><h5>".$temp['cipher']."</h5></td>
		<td width=30%>";
		if ($temp['imen_type_id']!=='0') {
			$imen_type_array = explode(",", $temp['imen_type_id']);
			foreach ($imen_type_array as $value){
				echo "<li>".show_imen_type($value)."</li>";
			}
		}
		$id_to_delete=$temp['id'];
		
		echo "</td>
		<td width=15%>".$temp['card_num']."</td>
		<td width=15%  title='Дата реєстрації іменника'>";
		if ($temp['reg_date']!=='0000-00-00') {
		echo date("d.m.Y", strtotime($temp['reg_date']));
		}
		echo "</td>
		<td width=15%>";
		if ($_SESSION['can_edit'] == '1') { 
		echo "<form method=\"post\" action=\"system/imen_delete.php\">
			<input type=\"hidden\" name=\"id_to_delete\" value=\"$id_to_delete\">
			
			<input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
		<input type=\"submit\" name=\"submit\" value=\"Видалити\" class=\"btn btn-warning\">
		</form>";
		}
		
		echo "
		</td>
		";
	}
	echo "</table>";
	
?>

		</div>
	</div>
</div>
<?php include("footer.php"); ?>
