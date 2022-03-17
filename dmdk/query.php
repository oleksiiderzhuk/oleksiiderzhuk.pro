<?php 
session_start();
error_reporting(0); 
include("header.php");
$user=$_SESSION['user'];
echo '<a style="margin-left: 2%">'.$user." - <a href=\"/\">Вийти</a></a> ";
?>

<div class="row" style="margin-top: 10px;">
	<div class="col-md-12" align="left">
		<?php 	
		$us=$_SESSION['user_id'];
		?>
			
				<a href="main.php" ><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
					Назад
				</button></a>
			<br>

		<form method=post action="selected.php">
			
		 	<div class="row"  style="height: 1000px;">
			
				<div class="form-group col-md-2" >
					<label for="opf_id">Дата первинної реєстрації</label>
					з<br>
					<input type=date class="form-control" name="from"><br>
					по<br>
					<input type=date class="form-control" name="to"><br>
			
				</div>

				<div class="form-group col-md-6">
					<label for="worktype">Види робіт: </label>
					<select name="worktype[]" multiple="multiple" style="height: 420px;" class="form-control">
						<?php
						$link = probirka_db_connect();
						$sql = "SELECT * FROM works";
						$result = mysqli_query($link, $sql);
						while($temp = mysqli_fetch_assoc($result))
							{
								$workname=$temp['workname'];
								echo "<option title=\"$workname\">".$workname."</option>";
							}
							?>
					</select>
				</div>			
			
				<div class="form-group col-md-2" >
					<label for="opf_id">Орг-прав форма</label>
					<select name="opf_id" class="form-control" multiple style="height: 400px">
					<?php
					$link = probirka_db_connect();
					$sql = "SELECT * FROM opf";
					$result = mysqli_query($link, $sql);
	
					while($temp = mysqli_fetch_assoc($result)){
						echo "<option >".$temp['opf_name']."</option>";
					}
					?>
					</select>
				</div>
			
				<div class="form-group col-md-2" >
					<input type=submit class="btn btn-primary" value="Вибрати суб'єктів">
				</div>
			</div>
	</div>
</div>
			
<?php include("footer.php"); ?>