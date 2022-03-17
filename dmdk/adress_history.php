<?php include_once("header.php"); ?>
<?php
	$link = probirka_db_connect();
	$id = $_GET['id'];
	$now_id = $_GET['now_id'];
	$sql = "SELECT * FROM adress WHERE id = $id";
	$adress_history_result = mysqli_query($link,$sql);
	$adress_history = mysqli_fetch_array($adress_history_result);
	$adress_id = $adress_history['adress_id'];
	$sql = "SELECT * FROM adress WHERE adress_id = $adress_id order by start_date desc";
	$full_adress_history = mysqli_query($link, $sql);

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div style="margin: 2%; border: 3px solid grey; background-color: white; min-height: 500px; border-radius: 5px; padding: 10px;">
				<h3>Історія зміни адреси</h3>
				<hr>
				<?php
					foreach ($full_adress_history as $value) 
					{
					$user=$value['edit_name'];
					$user=preg_replace ("/[0-9]/","",$user);
					$income_number=$value['income_number'];
					$income_date=$value['income_date'];
					if ($income_date!=='0000-00-00 00:00:00'){
					$income_date = date("d.m.Y", strtotime($value['income_date']));
					}
					else {
						$income_date='--';
					}
					
					
					$start_date=$value['start_date'];
						?>
						<table width=100% border=0><tr>
						<td><h6><?php echo show_region_name($value['region'])." ".$value['city'].", ".$value['area'].", ".$value['adress'];?></h6></td><td width=12% align=right><?="<a title=\"Внесено до реєстру користувачем $user\">".date("d.m.Y", strtotime($value['start_date'])).'</a>'?></td>
						<td width=12% align=center><?='<a title=\'Вх. №\'>'.$income_number.'</a>'?></td> 
						<td width=12% align=center><?='<a title=\'Дата вх. док.\'>'.$income_date.'</a>'?></td>  
						</tr></table>
						<h6>Поштовий індекс: <?=$value['postindex'];?></h6>
						<h6>Види робіт: </h6>
						<ul>
							<?php
							$worktype_array = explode(",", $value['worktype']);
							foreach ($worktype_array as $value_t) 
							{
								echo "<li>".show_work_name($value_t)."</li>";
							}
							?>
						</ul>
						<h6>Дата зміни: <?=$value['start_date'];?></h6>
						<hr>
						<?php
					}
					?>
				<a href="info_menu.php?id=<?=$now_id?>">Повернутися</a>
			</div>
		</div>
	</div>
</div>
<div style="margin-top: 10%;"></div>
<?php include_once("footer.php"); ?>