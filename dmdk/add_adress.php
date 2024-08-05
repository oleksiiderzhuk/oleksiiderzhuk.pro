<?php
include('header.php'); 
$link = probirka_db_connect();
?>
<div class="container">
	<div class="row" style="margin-top: 60px;">
		<div class="col-md-12">
			<form action="system/next_adress_add.php" method="post" style="min-height: 750px;">
				<div class="form-row">
					<input type="text" name="person_id" placeholder="Індекс" value="<?=$_GET['person_id']?>" style="display: none;">
<!--
					<div class="form-group col-md-2">
						<label for="postindex">Пошт. індекс</label>
						<input type="text" name="postindex" placeholder="Пошт. індекс" class="form-control" style="letter-spacing: 0.2em">
					</div>
-->					
					<div class="form-group col-md-3">
						<label for="region">Область</label>
						<select name="region" class="form-control">
							<?php
							$sql = "SELECT * FROM region";
							$result = mysqli_query($link, $sql);
							while($temp = mysqli_fetch_assoc($result))
							{
								echo "<option>".$temp['region_name']."</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="city">Місто</label>
						<input type="text" name="city" placeholder="Місто" class="form-control">
					</div>
					<div class="form-group col-md-4">
						<label for="area">Район</label>
						<input type="text" name="area" placeholder="Район" class="form-control">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-8">
						<label for="adress">Вулиця</label>
						<input type="text" name="adress" placeholder="Вулиця" class="form-control">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<select name="worktype[]" multiple="multiple" style="height: 370px;" class="form-control" required>
							<?php
							$sql = "SELECT * FROM works";
							$result = mysqli_query($link, $sql);
							while($temp = mysqli_fetch_assoc($result))
							{
								$workname = $temp['workname'];
								echo "<option title=\"$workname\">".$workname."</option>";
							}
							?>
						</select>
					</div>
				</div>
				<label for="income_number">Реєстраційні дані наданих матеріалів</label>
				<div class="form-row">
					<div class="form-group col-md-2">
						<input class="form-control" type="date" name ="income_date"> 
					</div>	
					<div class="form-group col-md-4">			
						<input class="form-control" type="text" name="income_number" placeholder="реєстраційний номер" style="letter-spacing: 0.2em">
					</div>
				</div>
				<input type="submit" name="submit" value="Додати адресу" class="btn btn-warning">
			</form>
		</div>
	</div>
</div>
<?php include("footer.php"); ?>
