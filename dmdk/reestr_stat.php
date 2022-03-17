<?php 
session_start();
error_reporting(1); 
include("header.php");

?>
<div class="container" style="background-color:#ffffffb3; min-height: 70vh;">

	<div class="row" style="margin-top: 10px; padding:20px;">
			<h4 style="width:100%; margin-left: 30%">Статистика заповнення реєстру за 2019 рік</h4>

		<?php
		if (($_SESSION['can_edit'] == '2')||($_SESSION['can_edit'] == '1')) {

			$sql = "SELECT * FROM users";
			//echo $sql;
			$users = mysqli_query($link, $sql);
			echo " <table width=100% cellpadding=25px style='font-size: 1.4em; background-color: rgba(36,105,203,0.26);' border=1 bordercolor=white>";
			echo "<tr><td width=50%><h4><i>Користувач</i></td><td align=center><i>Кількість первинних внесень</i></h4></td></tr>";
			foreach ($users as $value)	{
				$login = $value['name'];
				$id = $value['id'];
				$name=preg_replace ("/[0-9]/","",$login);
				$name=preg_replace ("/-/","",$name);
				$sql = "SELECT * FROM person where person_id in (select person_id from adress where edit_name like '$id' and is_main = '1' and current = '1' and start_date > '2019-01-01 00:00:00') and (state = '1' || state = '3') ";
				//echo $sql."<br>";
				$result=mysqli_query($link,$sql);
				$person_count =  mysqli_num_rows($result);
				if ($person_count>0) {
					echo "<tr><td width=50%>".$name." </td><td style='font-weight: bold; text-align: center'>".$person_count."</td></tr>";
				}
			}
			echo "</table>";
			echo '<h4 style="width:100%; margin-left: 30%"><br>Статистика заповнення реєстру за весь час</h4>';
			echo " <table width=100% cellpadding=25px style='font-size: 1.4em; background-color: rgba(36,105,203,0.26);' border=1 bordercolor=white>";
			echo "<tr><td width=50%><h4><i>Користувач</i></td><td align=center><i>Кількість первинних внесень</i></h4></td></tr>";
			foreach ($users as $value)	{
				$login = $value['name'];
				$id = $value['id'];
				$name=preg_replace ("/[0-9]/","",$login);
				$name=preg_replace ("/-/","",$name);
				$sql = "SELECT * FROM person where person_id in (select person_id from adress where edit_name like '$id' and is_main = '1' and current = '1') and (state = '1' || state = '3')";
				//echo $sql."<br>";
				$result=mysqli_query($link,$sql);
				$person_count =  mysqli_num_rows($result);
				if ($person_count>0) {
					echo "<tr><td width=50%>".$name." </td><td style='font-weight: bold; text-align: center'>".$person_count."<br></td></tr>";
				}
			}
			echo "</table>";
		}
		?>
		
		
	
	</div>

	
<?php include("footer.php"); ?>