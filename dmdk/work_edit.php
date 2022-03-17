<?php include("header.php");?>
<?php
$link = probirka_db_connect();
$id = $_GET['id'];
$now_id = $_GET['now_id'];
$sql = "SELECT date FROM adress WHERE `adressid` = $id ";
$result = mysqli_query($link, $sql);
$date = mysqli_fetch_array($result)['date'];
$sql="SELECT name FROM person WHERE `id` = $now_id ";
$result = mysqli_query($link, $sql);
$name = mysqli_fetch_array($result)['name'];
$sql="SELECT * FROM adress WHERE `id` = $now_id and `isjur`= 1 ";
$result = mysqli_query($link, $sql);
$mainadr = mysqli_fetch_array($result);
$adr=$mainadr['postindex'].' '.$mainadr['city'].' '.$mainadr['adress'];

?>
<div class="container-fluid">
	<div class="row" style="margin-top: 30px;">
		<div class="col-md-12" align="center">
			<h4>Оберіть нові види робіт для адреси</h4>
		</div>
	</div>
	<div class="row" style="margin-top: 15px;">
		<div class="col-md-12">
			<form action="system/work_edit_script.php" method="post">
				<input type="" name="id" style="display: none;" value="<?=$id?>">
				<input type="" name="now_id" style="display: none;" value="<?=$now_id?>">
				<select name="individual_works[]" multiple="multiple" style="height: 360px;" class="form-control">
					<?php
					$sql = "SELECT * FROM works";
					$result = mysqli_query($link, $sql);
					while($temp = mysqli_fetch_assoc($result))
					{
						echo "<option>".$temp['workname']."</option>";
					}
					?>
				</select>
				<br>	
				<input type="submit" name="submit" value="Зберегти" class="btn btn-warning" style="margin-top: 10px;">
			
			<br>
			</form>
				Реєстраційні дані наданих матеріалів <br>
				<form method=post action='print_message/3.php'><input type=date name=dateinput> <input type=text name=regnumber placeholder='реєстраційний номер'>
					<input type=text name=name	value='<?php echo $name;  ?>' style="display:none">
					<input type=text name=adr	value='<?php echo $adr;  ?>' style="display:none">
					&nbsp;&nbsp;&nbsp;
					<input type=submit value='Надрукувати реєстраційне повідомлення'>
					</form>
					
		</div>
	</div>
</div>
<?php include("footer.php"); ?>