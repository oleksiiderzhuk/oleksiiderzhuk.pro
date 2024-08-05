				<?php
				include('probirka_db_functions.php');
				$link = probirka_db_connect();

				$sql = "SELECT count(*) FROM person WHERE person_id in 
				(select person_id from imen where is_valid = '1') AND state = '1'";
			//echo $sql."<br>";

				$imen_lords_count = mysqli_query($link, $sql);
				$row = mysqli_fetch_array($imen_lords_count);
				$imen_lords_num= $row['count(*)'];

			//--------------------
				echo "<h4>Кількість виробників із зареєстрованими в реєстрі іменниками - <b>$imen_lords_num</b></h4>";

				$find = '';
				$find=mysqli_real_escape_string($link,$find);

				$sql = "SELECT * FROM person WHERE person_id IN (
				SELECT person_id FROM imen) and state = '1' limit ".$_POST['postResultCount'];

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
								$us=$_SESSION['user_id'];
								if (($us=='7')||($us=='8')||($us=='9')||($us=='1')||($us=='10')) {
									$sql = "SELECT * FROM imen WHERE person_id = $person_id order by reg_date desc limit 1";
									$cipher = mysqli_fetch_array(mysqli_query($link, $sql))['cipher'];
									if($cipher){
										echo "<span style='font-weight: bold; color: red' title='Шифр ыменника'>$cipher</span><br>";
									}
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