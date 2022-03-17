
<?php
include('probirka_db_functions.php');
$link = probirka_db_connect();
$find = '';
$find=mysqli_real_escape_string($link,$find);
$limit = $_POST['new_limit'];
$sql = "SELECT * FROM person WHERE state='1' and person_id IN 
(SELECT person_id FROM adress WHERE (
	worktype LIKE '%,2,%' 
	OR worktype LIKE '%,2' 
	OR worktype LIKE '2,%' 
	OR worktype = '2' 
)
	and current = '1'
)  ORDER BY date_start DESC limit 20,$limit ";

		//echo $sql."<br>";

$result = mysqli_query($link, $sql);
$count = 0;
foreach ($result as $value) 
{
	$count++;
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="jumbotron">
				<h5><?=$value['regno']?></h5>
				<h4><a href='info_menu.php?id=<?=$value['id'];?>'><?=$value['name']?></a></h4>
				<h5>
					<?php $opf=$value['opf_id']; 
					if ($opf==0) {
						echo "Фізична особа-підприємець";
					} 
					else {
						echo show_opf_name ($opf);
					}
					?>
				</h5>
				<?php
				$person_id = $value['person_id'];
				$sql = "SELECT * FROM imen WHERE person_id = $person_id and is_valid = '1' order by start_date desc limit 1";
				$cipher = mysqli_fetch_array(mysqli_query($link, $sql))['cipher'];
				if($cipher)
				{
					echo "<span title='Шифр іменника' style='font-weight: bold; color: red'>$cipher</span><br>";
				}
				?>
				<span title="Податковий номер"><?=$value['tax_code']?></span><br>
					<!--	<span>REGNO: <?=$value['regno']?></span><br>
						<span><b>Date: <?=$value['date']?></b></span><br>-->
						<?php 
						$person_id=$value['person_id'];
						$sql = "SELECT * FROM adress WHERE person_id = '$person_id' and is_main='1' and current='1'";

						$result = mysqli_query($link, $sql);
						if ($opf==0) {
							echo "<a title='Адреса проживання'>";
						}
						else {
							echo "<a title='Адреса'>";
						}
						foreach ($result as $value) {
							if ($value['postindex']) {
								echo $value['postindex'].", ";
							}
							if ($value['region']) {
								echo $value['region'].", ";
							}
							if ($value['area']) {
								echo $value['area'].", ";
							}
							if ($value['city']) {
								echo $value['city'].", ";
							}
							if ($value['adress']) {
								echo $value['adress']."<br>";
							}
							echo "</a>";
						}

						$sql = "SELECT * FROM contact WHERE person_id = '$person_id' and current='1'";
						$result = mysqli_query($link, $sql);
						echo "<a title='Контакти'>";		
						foreach ($result as $value) {
							if ($value['phone_number']) {
								echo '0'.$value['phone_number'];
							}
							if (($value['phone_number'])&&($value['email'])) {
								echo ", ";
							}
							if ($value['email']) {
								echo $value['email'];
							}
						}
						echo "</a><br><div align=left>";

						$sql="select * from adress where person_id = '$person_id' and current='1' ";

						$result=mysqli_query($link, $sql);
						$wt=array();			
						while ($row = mysqli_fetch_assoc($result)) {

							$worktype=$row['worktype'];
							if ($worktype!=='0') {
				//echo $worktype."<br>";
								$explode=explode(',', $worktype);
								$wt=array_merge($wt, $explode);
							}
						}
						$wt=array_unique($wt);

						for ($i=0; $i<count($wt); $i++) {
							echo "<li>".show_work_name($wt[$i])."</li>";

						}
						echo "</div>";
						?>
					</div>
				</div>
			</div>
			<?php	
		}


		?>
