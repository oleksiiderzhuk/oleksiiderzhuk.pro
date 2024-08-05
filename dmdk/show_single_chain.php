<?php include('header.php'); ?>
	<div class="container-fluid" style="margin-bottom: 30px;">
		<div class="row">
			<div class="col-md-12" style="">
				<div style="margin: 10px; border: 3px solid grey; background-color: #dae0e536; border-radius: 10px;">
					<div style="margin: 10px;">
						<?php
						$link = probirka_db_connect();
						$id = $_GET['id'];
						$back = $_GET['back'];
						//-------------Выбираем нужный субьект-----------
						$sql = "SELECT * FROM person WHERE id=$id";
						$result = mysqli_query($link, $sql);
						$name = mysqli_fetch_array($result);			
						?>
						<h4><?=$name['name']?></h4>
						<?php
						echo "Адреси:<br>";
						$sql = "SELECT COUNT(*) FROM adress WHERE id = $id AND isvalid = 1 GROUP BY date";
						$result = mysqli_query($link, $sql);
						$el_count = [];
						foreach ($result as $value) 
						{
							array_push($el_count, $value["COUNT(*)"]);
						}
						$sql = "SELECT * FROM adress WHERE id = $id AND isvalid = 1";
						$result = mysqli_query($link, $sql);
						$for_count = 0;
						$arr_count = 0;
						foreach ($result as $value) {

							$for_count++;
							echo show_work_name($value['worktype'])."<br>";
							if ($el_count[$arr_count]==$for_count)
							{
								echo "<b>".$value['adress']."</b><br>";
								echo "<b>Дата останнього оновлення: </b>".$value['date'];
								echo "<br><hr><br>";
								$arr_count++;
								$for_count = 0;
							}

						}?>
						<p>
							<a class="btn btn-warning" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
								Архівні адреси
							</a>
							<a class="btn btn-warning"  href="info_menu.php?id=<?=$back?>">
								Назад
							</a>
						</p>
						<div class="collapse" id="collapseExample">
							<div class="card card-body">
								<?php
								$sql = "SELECT COUNT(*) FROM adress WHERE id = $id AND isvalid = 0 GROUP BY date";
								$result = mysqli_query($link, $sql);
								$el_count = [];
								foreach ($result as $value) 
								{
									array_push($el_count, $value["COUNT(*)"]);
								}
								echo "<br>";
								$sql = "SELECT * FROM adress WHERE id = $id AND isvalid = 0";
								$result = mysqli_query($link, $sql);
								$for_count = 0;
								$arr_count = 0;
								foreach ($result as $value) {
									$for_count++;
									echo show_work_name($value['worktype'])."<br>";
									if ($el_count[$arr_count]==$for_count)
									{

										echo $value['date'];
										echo "<br>----------------------<br>";
										$arr_count++;
										$for_count = 0;
									}

								};
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
<?php include("footer.php"); ?>