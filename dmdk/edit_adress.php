<?php 
include_once("header.php");
$link = probirka_db_connect();
$id = $_GET['id'];
$now_id = $_GET['now_id'];
$sql = "SELECT * FROM person WHERE id = $now_id";
$result = mysqli_query($link, $sql);
$person = mysqli_fetch_array($result);
$sql = "SELECT * FROM adress WHERE id = $id";
$result = mysqli_query($link, $sql);
$adress = mysqli_fetch_array($result);
$date = date("Y-m-d");
$worktype_array = explode(",", $adress['worktype']);
?>
<div class="container">
	<div class="row" style="margin-top: 60px;">
		<div class="col-md-12">
			<form action="system/next_adress_edit.php" method="post" style="min-height: 1000px;">
				<div class="form-row">
					<input type="text" name="id" value="<?=$_GET['id']?>" style="display: none;">
					<input type="text" name="now_id" value="<?=$_GET['now_id']?>" style="display: none;">
					<div class="form-group col-md-2">
						<label for="postindex">Пошт. індекс</label>
						<input type="text" name="postindex" placeholder="Пошт. індекс" class="form-control" value="<?=$adress['postindex']?>" style="letter-spacing: 0.2em;">
					</div>
					<div class="form-group col-md-3">
						<label for="region">Область</label>
						<select name="region" class="form-control">
							<?php
							$sql = "SELECT * FROM region";
							$result = mysqli_query($link, $sql);
							while($temp = mysqli_fetch_assoc($result))
							{
								if ($temp['region_id'] == $adress['region'])
								{
									echo "<option selected>".$temp['region_name']."</option>";
								}
								else
								{
									echo "<option>".$temp['region_name']."</option>";
								}
								
							}
							?>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="city">Місто</label>
						<input type="text" name="city" placeholder="Місто" class="form-control" value="<?=$adress['city']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="area">Район</label>
						<input type="text" name="area" placeholder="Район" class="form-control" value="<?=$adress['area']?>">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="adress">Адреса</label>
						<input type="text" name="adress" placeholder="Адреса" class="form-control" value="<?=$adress['adress']?>">
					</div>
				</div>
				<?php
				if(!$person['isjur']==0 or $adress['is_main']==0)
				{
					?>
					<div class="form-row">
						<div class="form-group col-md-12">
							<select name="worktype[]" multiple="multiple" style="height: 550px;" class="form-control">
								<?php
								$sql = "SELECT * FROM works";
								$result = mysqli_query($link, $sql);
								while($temp = mysqli_fetch_assoc($result))
								{
									foreach ($worktype_array as $value) {
										if($value == $temp['workid'])
										{
											$selected = "selected";
											break;
										}
									}
									$workname=$temp['workname'];
									echo "<option $selected title=\"$workname\">".$workname."</option>";
									$selected = "";
								}
								?>
							</select>
						</div>
					</div>
					<?php
				}
				?>
				<label for="income_number">Реєстраційні дані наданих матеріалів на реєстрацію адреси</label>
				<div class="form-row">
					<div class="form-group col-md-2">
						<?php	$income_date=date('Y-m-d', strtotime($adress['income_date']));
								if ($income_date=='1970-01-01') {
								$income_date='0000-00-00';
								}						?>
						<input class="form-control" type="date" name ="income_date" value="<?=$income_date?>"> 
					</div>
					<div class="form-group col-md-4">			
						<input class="form-control" type="text" name="income_number" placeholder="реєстраційний номер" style="letter-spacing: 0.2em" value="<?=$adress['income_number']?>">
					</div>
				</div>
				<input type="submit" name="submit" value="Зберегти" class="btn btn-warning">
			</form>
		</div>
	</div>
</div>
<?php include_once("footer.php");?>