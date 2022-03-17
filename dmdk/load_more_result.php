<?php
include('probirka_db_functions.php');
$link = probirka_db_connect();
$find = $_POST['new_find'];
$limit = $_POST['new_limit'];
$find=mysqli_real_escape_string($link, $find);
$sql = "SELECT * FROM person WHERE tax_code = '$find' and state=1 
UNION 
SELECT * FROM person WHERE regno = '$find' and state=1 
UNION
SELECT * FROM person WHERE (lower(name) like lower('%$find%') AND state = 1)
UNION
SELECT * FROM person WHERE person_id IN (select person_id from adress where lower(city) like lower('%$find') and is_main='1') and state='1'
UNION
SELECT * FROM person WHERE person_id IN (select person_id from imen where lower(cipher) like lower('%$find') and is_valid = '1' ) and state='1' 
ORDER BY date_start DESC limit 50,$limit  ";
$count = 0;
$result = mysqli_query($link, $sql);
foreach ($result as $value) 
{
	$count++;
	?>
	<div class="row">
		<div class="col-md-12"  style="background-color: rgba(36,105,203,0.26);">
			<div class="jumbotron">
				<h5><?=$value['regno']?></h5>
				<h4><a href='info_menu.php?id=<?=$value['id'];?>' target='_blank'><?=$value['name']?></a></h4>
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
				if ($_SESSION['can_edit'] == '1') {
					$person_id = $value['person_id'];
					$sql = "SELECT * FROM imen WHERE person_id = $person_id and is_valid = '1' order by start_date desc limit 1";
					$cipher = mysqli_fetch_array(mysqli_query($link, $sql))['cipher'];
					if($cipher)
						echo "<span style='color: red' title='Шифр іменника'>$cipher</span><br>";					{
					}
				}
				?>
				<a title="Податковий номер">
				<?php
				if ($opf==0) {
								if ( $_SESSION['can_edit']=='1' ) {
									echo $value['tax_code']."<br>";
								}
								else {
								}
							}
							else {
									echo $value['tax_code']."<br>";
							}
				?>
				
				</a>
					<!--	<span>REGNO: <?=$value['regno']?></span><br>
						<span><b>Date: <?=$value['date']?></b></span><br>-->
						<?php
				if ($opf==0) {
								if ( $_SESSION['can_edit']=='1' ) {
									echo $value['tax_code']."<br>";
								}
								else {
								}
							}
							else {
									echo $value['tax_code']."<br>";
							}
				

						$sql = "SELECT * FROM contact WHERE person_id = '$person_id' and current='1'
						AND (phone_number <> '' OR email <> '')";
						$result = mysqli_query($link, $sql);
						$rows = mysqli_num_rows($result); 
						if ($rows>0) {
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
							echo "</a><br>";
						}
						//---------------вывод видов деятельности субъекта-----------------
						echo "<div align=left>";

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
							if (show_work_name($wt[$i])) {
								echo "<li>".show_work_name($wt[$i])."</li>";							
							}

						}
						echo "</div>";
						//---------------окончание вывода видов деятельности субъекта-----------------
						?>
					</div>
				</div>
			</div>
			<?php	
		}
		?>

		