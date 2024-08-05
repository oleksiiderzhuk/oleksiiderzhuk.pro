<?php
session_start();
include('probirka_db_functions.php');
$link = probirka_db_connect();
$find = $_POST['new_find'];
$limit = $_POST['new_limit'];
$archive_search = $_POST['archive_search'];
$find=mysqli_real_escape_string($link, $find);
$sql = "SELECT * FROM person WHERE tax_code = '$find' and (state=1 || state=3) 
UNION 
SELECT * FROM person WHERE regno = '$find' and (state=1 || state=3) 
UNION
SELECT * FROM person WHERE (lower(name) like lower('%$find%') AND (state = 1 || state=3))
UNION
SELECT * FROM person WHERE person_id IN (select person_id from adress where lower(city) like lower('%$find') and is_main='1') and state='1'";
//
if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) {
	$sql.="
UNION
SELECT * FROM person WHERE person_id IN (select person_id from Imen where lower(cipher) like lower('%$find') and is_valid = '1') and (state='1' || state=3) ";
}

$sql.= "ORDER BY date_start DESC limit 50  ";
//echo $sql."<br>";

//$sql='';
$count = 0;
if ($find!=='') {
$result = mysqli_query($link, $sql);
}
else {
echo "<a href=''><h5>Показати весь реєстр</h5></a>";
}



foreach ($result as $value) 
{
	$state=$value['state'];
	$count++;
	?>
	<div class="row" style="margin-top: -0px" >
		<div class="col-md-12" style="background-color: rgba(36,105,203,0.26);">
			<div class="jumbotron"
			<?php 
			if ($state=='3') {
			echo " style='background-color: yellow'";
			}
			?>
			>
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
				<a title="Податковий номер">
				<?php
				if ($opf==0) {
								if (( $_SESSION['can_edit']=='1' )||( $_SESSION['can_edit']=='2' )) {
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
				if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) {
					$person_id = $value['person_id'];
					$sql = "SELECT * FROM imen WHERE person_id = $person_id and is_valid = '1' order by start_date desc limit 1";
					$cipher = mysqli_fetch_array(mysqli_query($link, $sql))['cipher'];
					if($cipher)
					{
						echo "<span title='Шифр іменника' style='font-weight: bold; color: red'>$cipher</span><br>";
					}
				}
				?>
				
						<?php 
						$person_id=$value['person_id'];
						$sql = "SELECT * FROM adress WHERE person_id = '$person_id' and is_main='1' and (current='1' || current='3')";
						
						$result = mysqli_query($link, $sql);
						echo "<a title='Адреса'>";
						foreach ($result as $value) {
							$adr='';
							if ($value['postindex']) {
								$adr.=$value['postindex'].", ";
							}
							if ($value['region']) {
								if ($value['region']!=='1') {
									$adr.=show_region_name($value['region']).", ";
								}
							}
							if ($value['area']) {
								$adr.= $value['area'].", ";
							}
							if ($value['city']) {
								$adr.= $value['city'].", ";
							}
							if ($value['adress']) {
								$adr.= $value['adress']."<br>";
							}
							if ($opf==0) {
								if (( $_SESSION['can_edit']=='1' )||( $_SESSION['can_edit']=='2' )) {
									if ($value['current']=='3') {
										echo "<div style='background-color: yellow'>".$adr."</div>";
									}
									else {
										echo "<div>".$adr."</div>";
									}
								}
								else {
								echo "*****<br>";
								}
							}
							else {
								if ($value['current']=='3') {
									if (( $_SESSION['can_edit']=='1' )||( $_SESSION['can_edit']=='2' )) {
										echo "<div style='background-color: yellow'>".$adr."</div>";
									}
									else {
										echo "<div>".$adr."</div>";
									}
								}
								else {
									echo "<div>".$adr."</div>";
								}
							}
							echo "</a>";
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
		<div id="test">
		</div>
		<?php
		if($count%50==0 AND $count!=0)
		{
			echo "<button class='btn btn-warning' id='load_more'>Завантажити ще</button>";
		}

		?>
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				var limit = 0;
				var find = '<?=$find?>';
				$("#load_more").click(function() {
					limit = limit + 50;
					$.post("load_more_result.php", {
						new_limit: limit, new_find: find
					}, function(data, status) {
						$("#test").html(data);
					});
				});
				
			});
		</script>
		