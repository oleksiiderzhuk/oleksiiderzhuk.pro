<?php
error_reporting(0);
include('header.php') ;

$link = probirka_db_connect();
$id = $_GET['id'];
$self=$_SERVER['PHP_SELF'].'?id='.$id;
$self=substr($self, 1);
$_SESSION['self']=$self;
$sql = "SELECT * FROM person WHERE id = $id";
$person_result = mysqli_query($link, $sql);
$person = mysqli_fetch_array($person_result);
$person_edit_name_id = $person['edit_name'];
$person_edit_name = show_editName($person_edit_name_id);


$person_id = $person['person_id'];
$state = $person['state'];
$edrpou = $person['tax_code'];
$sql = "SELECT * FROM imen WHERE person_id = $person_id and is_valid = '1' order by start_date desc limit 1";
$imen_result = mysqli_query($link, $sql);
if ($imen_result) {
	$imen = mysqli_fetch_array($imen_result);
}
$cipher = $imen['cipher'];
if ($imen['reg_date']) {
	$imen_reg_date = date("d.m.Y", strtotime($imen['reg_date']));
}	

$regdate = $person['regdate'];
if (($regdate)&&($regdate!=='0000-00-00')) {
	$regdate=date("d.m.Y", strtotime($regdate)); 
}
else {
	$regdate='';
}

$reg_info = $person['reg'];
$reg_info_1 = substr($reg_info, 0, 1);
$reg_info_2 =  substr($reg_info, 1, 3);
$reg_info_3 =  substr($reg_info, 4, 3);
$reg_info_4 =  substr($reg_info, 7, 4);
$reg_info_5 =  substr($reg_info, 11, 6);


$sql = "SELECT * FROM adress WHERE person_id = $person_id AND is_main = 1 AND (current = 1 || current = 3) order by start_date desc";
//echo '<br>'.$sql."<br>";
$main_adress_result = mysqli_query($link, $sql);
$main_adress = mysqli_fetch_array($main_adress_result);
$main_adress_id = $main_adress['id'];

$main_adress_state = $main_adress['current'];

$sql = "SELECT * FROM adress WHERE person_id = $person_id AND current = 1 AND is_main = 0 AND isvalid = 1 order by start_date desc";
$adress = mysqli_query($link, $sql);

$sql = "SELECT * FROM adress WHERE person_id = $person_id AND current = 2 AND is_main = 0 AND isvalid = 1";
$arch_adress = mysqli_query($link, $sql);

$sql = "SELECT * FROM adress WHERE person_id = $person_id AND current = 1 AND is_main = 0 AND isvalid = 0";
$temp_del = mysqli_query($link, $sql);


$sql = "SELECT * FROM contact WHERE id = (SELECT MAX(id) FROM contact WHERE person_id = $person_id)";
$contact_result = mysqli_query($link, $sql);
$contact = mysqli_fetch_array($contact_result);

$sql = "SELECT * FROM adress WHERE id = (SELECT MAX(id) FROM adress WHERE person_id = $person_id)";
$last_adress = mysqli_fetch_array(mysqli_query($link, $sql));

if($person['state']==2) {
	$last_date=date("d.m.Y", strtotime($last_adress['income_date']));
	if ($last_date!=='01.01.1970') {
		$del_message = "Видалено користувачем ".$person_edit_name."<br>на основі вх. ".$last_adress['income_number']." від ".date("d.m.Y", strtotime($last_adress['income_date']));
	}
}

$sql = "SELECT MAX(ID) FROM activity_finish WHERE person_id = $person_id";
$max_result = mysqli_query($link, $sql);
$max_array = mysqli_fetch_array($max_result);
$maxid=$max_array['MAX(ID)'];

$sql = "SELECT * FROM activity_finish WHERE id=$maxid";
$activity_finish_result = mysqli_query($link, $sql);
$activity_finish_array = mysqli_fetch_array($activity_finish_result);
$date_stopped=$activity_finish_array['date_stopped'];
if (($date_stopped)&&($date_stopped!=='0000-00-00')) {
	$date_stopped=date("d.m.Y", strtotime($date_stopped));
}
else {
	$date_stopped='';
}	

$reg_stopped=$activity_finish_array['regno_stopped'];
$reg_stopped1 = substr($reg_stopped, 0, 1);
$reg_stopped2 =  substr($reg_stopped, 1, 3);
$reg_stopped3 =  substr($reg_stopped, 4, 3);
$reg_stopped4 =  substr($reg_stopped, 7, 4);
$reg_stopped5 =  substr($reg_stopped, 11, 6);
$date_stopping=$activity_finish_array['date_stopping'];
if (($date_stopping)&&($date_stopping!=='0000-00-00')) {
	$date_stopping=date("d.m.Y", strtotime($date_stopping));
}
else {
	$date_stopping='';
}	
?>
<div class="container-fluid" style="margin-bottom: 30px;">
	<div class="row">
		<div class="col-md-12" >
			<?php 
			if ($state=='2') {
				echo '
				<div style="margin: 10px; border: 3px solid grey; background-color: darkgrey; border-radius: 10px; min-height: 300px;">
				';
			}
			else {
				echo '
				<div style="margin: 10px; border: 3px solid grey; background-color: #ffffffbf; border-radius: 10px; min-height: 300px;">
				';
			}
			?>
			
			<div style="margin: 10px;">
				<h3>
					<table width=70%><tr> 
						<?php 
						if ($state==3) {
							if (( $_SESSION['can_edit']=='1' )||( $_SESSION['can_edit']=='2' )) {
								echo '<td align=left bgcolor=yellow>'.$person['name'].'</td><td align=right>';
							}
							else {
								echo '<td align=left>'.$person['name'].'</td><td align=right>';
							}
							
						} 
						else { 
							echo '<td align=left>'.$person['name'].'</td> <td align=right>';
						}

						if ($_SESSION['can_edit'] == '1') {
						
							if ($state=='2') {
								if (!isset($_POST['x'])) {
									echo "
									<form method='post' action='system\delete_person.php'>
									<input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
									<input type=\"hidden\" name=\"id\" value=\"$id\">

									<input type='submit' class='btn btn-warning' name='x' value='Остаточно видалити' width='100px'  onclick=\"return confirm('Вы точно хотите удалить субъект из реестра?')\">
									</form>
									";
								}
								if (!isset($_POST['restore'])) {
									echo "
									<form method='post' action='system/restore_person.php'>
									<input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
									<input type=\"hidden\" name=\"id\" value=\"$id\">

									<input type='submit' class='btn btn-warning' name='x' value='Відновити' width='100px' >
									</form>
									";
								}
							}
							else {
							if (!isset($_POST['warning'])) {
								echo "
								<form method='post' action='system\change_state.php'>
								<input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
								<input type=\"hidden\" name=\"id\" value=\"$id\">
								<div  style=\"float: right; margin-right: 175px\">
									<input type='submit' class='btn btn-warning' name='warning' value='!' width='100px' >
								</div>
								</form>
								";
							}
							}	
						}
						?>

					</td></tr></table>
					<?php 
					
					$date_end = $person['date_end'];
					$date_end=date("d.m.Y", strtotime($date_end));
					if ($state == '2') {
						echo "Суб'єкт припинив діяльність ";

						if ($date_end!=='01.01.1970') {
							echo $date_end."<br>";
						}
						echo $del_message;

					}

					?></h3>
					<div class="dropdown dropleft" style="float: right; margin-top: -40px;">
						<?php 
						if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']!=='1' )) { ?>
						<button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-left: ">
							Дії з суб'єктом
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<?php if ($_SESSION['can_edit'] == '1') { ?>
							<a class="dropdown-item" href="edit_<?php if($person['isjur']==1) echo "jur"; else echo "fiz";?>.php?id=<?=$id?>&adr_id=<?=$main_adress_id?>">Редагувати дані</a>
							<?php } ?>
							<a class="dropdown-item" href="sub_history.php?id=<?=$id?>">Продивитися історію</a>
							<?php if ($_SESSION['can_edit'] == '1'){ ?>
							<a class="dropdown-item" data-toggle="modal" data-target="#reg_new">Внесення нового суб'єкта</a>
							<a class="dropdown-item"  data-toggle="modal" data-target="#reg_edit">Внесені зміни</a>
							<a class="dropdown-item" style="color:white; background-color: #dc5f5f" data-toggle="modal" data-target="#exampleModalCenter">Виключення з реєстру</a>
							<?php } ?>
							<a class="dropdown-item" href="document_list.php?person_id=<?=$person_id?>">Документи</a>
						</div>

						<div class="modal fade" id="reg_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content" style="width:200%; margin-left: -150px">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLongTitle">Внесені зміни</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<form method="post" action="print_message/core.php" target="_blank">
											<div class="form-row">	
												<div class="form-group col-md-4">
													<input type="hidden" name="type" value="1">
													<input type="hidden" name="id" value="<?=$id?>">			
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">X</button>
												<input type="submit" class="btn btn-secondary" value="Лист +адр" name="letter">
												<input type="submit" class="btn btn-secondary" value="Лист +опер" name="letter_operations">
												<input type="submit" class="btn btn-secondary" value="Шаблон" name="message">
												<input type="submit" class="btn btn-secondary" value="+Адреси" name="adr+">
												<input type="submit" class="btn btn-secondary" value="+Операції" name="opera+">
												<input type="submit" class="btn btn-secondary" value="- Адреси" name="adr-">
												<input type="submit" class="btn btn-secondary" value="Конверт" name="envelope">
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						
						<div class="modal fade" id="reg_new" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLongTitle">Внесення нового суб'єкта господарювання</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<form method="post" action="print_message/core.php" target="_blank">
											
											<div class="form-row">	
												<div class="form-group col-md-4">
													<input type="hidden" name="id" value="<?=$id?>">
													<input type="hidden" name="type" value="0">
												</div>		
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">X</button>
												<input type="submit" class="btn btn-secondary" value="Лист" name="letter">
												<input type="submit" class="btn btn-secondary" value="Повідомлення" name="message">
												<input type="submit" class="btn btn-secondary" value="Конверт" name="envelope">
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

						<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLongTitle">Виключення з реєстру</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<h6>Реєстраційні дані наданих матеріалів на виключення з реєстру</h6>
										<form method="post" action="print_message/core.php" target="_blank">
											<label for="income_number">Реєстраційні дані наданих матеріалів</label>
											<div class="form-row">	
												<div class="form-group col-md-6">
													<input class="form-control" type="date" name ="income_date"> 
												</div>	
												<div class="form-group col-md-6">
													<input type="hidden" name="id" value="<?=$id?>">			
													<input type="hidden" name="type" value="2">			
													<input class="form-control" type="text" name="income_number" placeholder="реєстраційний номер" >
												</div>
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Х</button>
												<input type="submit" class="btn btn-secondary" value="Лист" name="letter">
												<input type="submit" class="btn btn-secondary" value="Повідомлення" name="message">
												<input type="submit" class="btn btn-secondary" value="Конверт" name="envelope">
												
												<input type="submit" class="btn btn-danger" value="Виключити" name="delete">
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<br><br>
						<?php } ?>
						
						
						<h4 title="Номер в реєстрі<?php
						if (( $_SESSION['can_edit']=='1' )||( $_SESSION['can_edit']=='2' )) {
							echo ", дата внесення: ".date('d.m.Y', strtotime($person['date_start']));
						}
						?>
						
						"><?=$person['regno']?>
						
						
						</h4>
						<?php
						if (( $_SESSION['can_edit']=='1' )||( $_SESSION['can_edit']=='2' )) {
						?>
						<table border=0><tr valign=top><td width=170px>
						<?php
						if (($regdate)||($reg_info1)){
						?>
						Реєстрація</td><td>
						<a title="Дата державної реєстрації юридичної особи">
						<?php 
							if ($regdate) {
								echo $regdate;
							}
						?>
						</a><br>
						
						<a title="Номер запису в Єдиному державному реєстрі про проведення державної реєстрації">
						<?php
						echo $reg_info_1.' '.$reg_info_2.' '.$reg_info_3.' '.$reg_info_4.' '.$reg_info_5;
						?>
						</a><br><br>
						<?php
						}
						?>
						</td></tr>
						<tr valign=top>
						<?php
						if (($date_stopped)||($reg_stopped1)){
						?>						
						<td>Припинення</td><td>
						<a title="Дата державної реєстрації припинення">
						<?php

						if ($date_stopped) {
							echo $date_stopped;
						}
						?>
						</a><br>
						
												
						<a title="Номер запису про державну реєстрацію припинення">
						<?php
						echo $reg_stopped1.' '.$reg_stopped2.' '.$reg_stopped3.' '.$reg_stopped4.' '.$reg_stopped5;
						?>
						</a><br><br>						
						</td></tr>
						<?php
						}
						?>
						<?php
						if ($date_stopping){
						?>
						<tr><td>В стані припинення</td><td>
						<a title="Дата перебування в стані припинення">
						<?php
							echo $date_stopping;
						}
						}
						?>
						</a><br>
						</td></tr>
						</table>
								</div>
								<h5><?=show_opf_name($person['opf_id']);?></h5>
								<?php 
								if($person['isjur']==0){
									//echo "<h5>Фізична особа-підприємець</h5>";
									if (($_SESSION['can_edit'] == '3')||( $_SESSION['can_edit']=='4' )) {
										echo "<h5 title='Ідентифікаційний податковий номер'>$edrpou</h5><br>";
									}
									else {
										echo "<h5 title='Ідентифікаційний податковий номер'>**********</h5><br>";
									}
								}
								else {
									echo "<h5 title='ЄДРПОУ'>$edrpou</h5><br>";
								}

								?>
								<h6>Контактні дані:</h6>
								<span>Номер телефону: 
									<?php if ($contact['phone_number']) {
										echo "0";
									}
									else {
										echo "-";
									}
									?> 
									<?=$contact['phone_number']?></span><br>
									<span>Електронна скринька: <?=$contact['email']?></span>
<br>									<?php 
									if (($_SESSION['can_edit'] == '1')||($_SESSION['can_edit'] == '2')) { ?>
										<a href="sub_history.php?now_id=<?=$id;?>">Історія <?=count_person_changes($id)?></a>
										<?php } ?>	
										<?php 
										if ($_SESSION['can_edit'] == '1') { ?>
										 | <a href="edit_<?php if($person['isjur']==1) echo "jur"; else echo "fiz";?>.php?id=<?=$id?>&adr_id=<?=$main_adress_id?>">Змінити </a>
									<?php } 									echo '<br><br>';
?>
									
									<?php
									$mainadr='';
									$region=$main_adress['region'];

									if (($region)&&($region!=='1')) {
										$region=show_region_name($region);
										$mainadr.=$region.", ";
									}
									if ($main_adress['area']) {
										$mainadr.=$main_adress['area'].", ";
									}
									if ($main_adress['city']) {
										$mainadr.=$main_adress['city'].", ";
									}
									$mainadr .= $main_adress['adress'];					
									if($person['isjur']==1)
									{
										?>
										
										<div style="width:100;  border: 1px solid grey; border-radius: 5px; padding: 10px; margin-top: 20px;">
											<h5>Юридична адреса: </h5>
											<table width=100% border=0><tr>

												<?php 
												if ($main_adress_state == '3') {
													if (( $_SESSION['can_edit']=='1' )||( $_SESSION['can_edit']=='2' )) {
														echo "<td bgcolor='yellow'>";
													}
													else {
														echo "<td>"; 
													}
													
												}
												else {
													echo "<td>"; 
												}

												?>
												<h6><?=$mainadr?></h6></td> 
												<td width=5%>
												<?php 
												if ( $_SESSION['can_edit'] == '1' ) {
										
													if (!isset($_POST['adr_warning'])) {
														echo "
															<form method='post' action='system\change_mainadress_state.php'>
															<input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
															<input type=\"hidden\" name=\"id\" value=\"$id\">
															<input type='submit' class='btn btn-warning' name='adr_warning' value='!' width='100px'>
															</form>
															";
													}
												}
  												?>
												</td>
												<?php 
												$user=$main_adress['edit_name'];
												$user = show_editName($user);
												
												$start_date=$main_adress['start_date'];
												$start_date= date("d.m.Y", strtotime($start_date));
												?>
												<?php if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) { ?>
												<td width=12%><?="<a title=\"Внесено до реєстру користувачем $user\">".$start_date.'</a>'?></td> 
												<td width=12%><?='<a title=\'Вх. №\'>'.$main_adress['income_number'].'</a>'?></td> 
												<td width=12%><a title='Дата вх. док.'><?php
												if (date("d.m.Y", strtotime($main_adress['income_date']))!=='01.01.1970') {
													echo ''.date("d.m.Y", strtotime($main_adress['income_date'])).'';
												}
												else {
													echo "--";
												}
												?></a></td>  
												<?php } ?>	
											</tr></table>
											<h6>Поштовий індекс: <?=$main_adress['postindex'];?></h6>
											<?php

											if ($main_adress['worktype']!=='0') {
												echo '<h6>Види робіт: </h6>
												<ul>';
												$worktype_array = explode(",", $main_adress['worktype']);
												foreach ($worktype_array as $value) 
												{
													echo "<li>".show_work_name($value)."</li>";
												}
											}

											?>
										</ul>
										<?php if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) { ?>
										<a href="adress_history.php?id=<?=$main_adress['id'];?>&now_id=<?=$id;?>">Історія <?=count_changes($main_adress['id'])?></a> | 
										<?php } ?>	
										<?php 
										if ($_SESSION['can_edit'] == '1') { ?>
										<a href="edit_adress.php?id=<?=$main_adress['id'];?>&now_id=<?=$id;?>">Змінити </a>
										<?php } ?>

									</div>
									<?php
								}
								else
								{
									{
										?>
										
										<div style="width:100; border: 1px solid grey; border-radius: 5px; padding: 10px; margin-top: 20px;">

											<h5>Адреса проживання: </h5>
											<table width=100% border=0><tr><td>
												<?php if (($_SESSION['can_edit'] == '3')||( $_SESSION['can_edit']=='4' )) {
												
												if ($main_adress_state == 3) {
													echo "<h6 style='background-color: yellow'>";
												}
												else {
													echo "<h6>"; 
												}
												?>
												<?=$mainadr?></h6></td>
												
												<td width=5%>
												<?php 
												if ($_SESSION['can_edit'] == '1'){
												if (!isset($_POST['adr_warning'])) {
													echo "
														<form method='post' action='system\change_mainadress_state.php'>
														<input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
														<input type=\"hidden\" name=\"id\" value=\"$id\">
														<input type='submit' class='btn btn-warning' name='adr_warning' value='!' width='100px'>
														</form>
														";
												}
												}
  												?>
												</td>
												
												<?php } 
												else echo "<h6>*****</h6></td> 
													";
												?>	
												<?php 
												$user=$main_adress['edit_name'];
												$user = show_editName($user);
												$start_date=$main_adress['start_date'];
												$start_date= date("d.m.Y", strtotime($start_date));
												?>
												<?php if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) { ?>
												<td width=12%><?="<a title=\"Внесено до реєстру користувачем $user\">".$start_date.'</a>'?></td> 
												<td width=12%><?='<a title=\'Вх. №\'>'.$main_adress['income_number'].'</a>'?></td> 
												<td width=12%>
													<a title='Дата вх. док.'><?php
													if (date("d.m.Y", strtotime($main_adress['income_date']))!=='01.01.1970') {
														echo ''.date("d.m.Y", strtotime($main_adress['income_date'])).'';
													}
													else {
														echo "--";
													}
													?></a></td>  
													<?php } ?>	
												</tr></table>
												<?php if (($_SESSION['can_edit'] == '3')||( $_SESSION['can_edit']=='3' )) { ?>
												<h6>Поштовий індекс: 
													<?=$main_adress['postindex']?></h6>
													<?php } ?>	
												</ul>
												<?php 
												if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) { ?>
												<a href="adress_history.php?id=<?=$main_adress['id'];?>&now_id=<?=$id;?>">Історія <?=count_changes($main_adress['id'])?></a> | 
												<?php } ?>	
												<?php 
												$us=$_SESSION['user_id'];
												if ($_SESSION['can_edit'] == '1') { ?>
												<a href="edit_adress.php?id=<?=$main_adress['id'];?>&now_id=<?=$id;?>">Змінити </a>
												<?php } ?>
											</div>
											<?php
										}
									}?>

									<hr>
									
									<?php 
									
									if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) { 

										$sql = "SELECT count(*) FROM adress WHERE 
										person_id = '$person_id'
										and (
										worktype LIKE '%,2,%' 
										OR worktype LIKE '%,2' 
										OR worktype LIKE '2,%' 
										OR worktype = '2' )
										";

										$result = mysqli_query($link, $sql);
										if ($result) {
											$is_producer = mysqli_fetch_array($result);
										}

										if ($is_producer['count(*)']>0) {
											echo "
											<table width=100%><tr><td>
											<h5>Шифр поточного іменника: </h5>
											</td> 
											<td width=12%>
											<h4 title=\"Шифр поточного іменника\"><a href=\"imen.php?person_id=$person_id\" style='color: red'>$cipher</a></h4>
											<a title=\"Дата реєстрації іменника\">$imen_reg_date</a>
											</td> 
											</tr></table>
											<a href=\"imen.php?person_id=$person_id\">Іменники суб'єкта</a><br><br>
											";
										} 
									}
									?>
									
									
									
									
									<h5>Адреси здійснення діяльності: </h5>
									<?php 
									if ($_SESSION['can_edit'] == '1') { ?>
									<a href="add_adress.php?person_id=<?=$person_id?>">Додати адресу</a><br>
									<?php } ?>
									<br>
									<?php
									foreach ($adress as $value) 
									{
										?>
										<h6>
											<?php 
											$adr='';
											$region=$value['region'];

											if (($region)&&($region!=='1')) {
												$adr.=show_region_name($value['region']).", ";
											}
											if ($value['area']) {
												$adr.=$value['area'].", ";
											}
											if ($value['city']) {
												$adr.=$value['city'].", ";
											}
											$adr .= $value['adress'];
											$start_date=$value['start_date'];
											$start_date=date("d.m.Y", strtotime($start_date));

											$income_number=$value['income_number'];
											if (!$income_number) {
												$income_number='--';
											}

											$income_date=$value['income_date'];
											$income_date=date("d.m.Y", strtotime($income_date));
											if ($income_date=='01.01.1970') {
												$income_date='--';
											}

											$user=$value['edit_name'];
											$user = show_editName($user);
											

											?>
											<table width=100% border=0><tr><td><h6><?=$adr?></h6></td> 
												<?php if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) { ?>

												<td width=12%><?="<a title=\"Внесено до реєстру користувачем $user\">".$start_date.'</a>'?></td> 
												<td width=12%><?='<a title=\'Вх. №\'>'.$income_number.'</a>'?></td> 
												<td width=12%><?='<a title=\'Дата вх. док.\'>'.$income_date.'</a>'?>
												</a></td>  
												<?php } ?>
											</tr></table>

										</h6>
										<h6>Поштовий індекс: <?=$value['postindex'];?></h6>
										<h6>Види робіт: </h6>

										<ul>
											<?php
											$worktype_array = explode(",", $value['worktype']);
											foreach ($worktype_array as $value_t) 
											{
												echo "<li>".show_work_name($value_t)."</li>";
											}
											?>
										</ul>
										<table width=100% border=0><tr><td>
											<?php 

											if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) { ?>
											<a href="adress_history.php?id=<?=$value['id'];?>&now_id=<?=$id;?>">Історія <?=count_changes($value['id'])?></a> | 
											<?php } ?>	
											<?php 
											if ($_SESSION['can_edit'] == '1') { ?>

											<a href="edit_adress.php?id=<?=$value['id'];?>&now_id=<?=$id;?>">Змінити </a> | <a href="#" data-toggle="modal" data-target="#adress_add_to_arch<?=$value['id'];?>"> Припинити діяльність за адресою</a> </td><td align=right> <a href='system/adress_temp_del.php?id=<?=$value['id'];?>&now_id=<?=$id;?>'>Перемістити адресу до кошика</a>
												<?php } ?>
											</td></tr></table>	

											<!-- Modal -->
											<div class="modal fade" id="adress_add_to_arch<?=$value['id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
												<div class="modal-dialog modal-dialog-centered" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">Заповніть форми:</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>

														<div class="modal-body">
															<form method="post" action="system/add_to_arch.php">
																<div class="form-row">	
																	<div class="form-group col-md-6">
																		<input type="date" name="income_date" class="form-control">
																	</div>	
																	<div class="form-group col-md-6">
																		<input type="hidden" name="id" value="<?=$value['id']?>">
																		<input type="hidden" name="now_id" value="<?=$id?>">
																		<input type="hidden" name="edit_name" value="<?=$_SESSION['user_id']?>">
																		<input type="text" name="income_number" class="form-control" placeholder="Реєстраційний номер">

																	</div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
																	<input type="submit" class="btn btn-danger" value="Підтвердити">
																</div>
															</form>
														</div>

													</div>
												</div>
											</div>
											<hr>
											<?php
										}
										?>

										<?php 
										if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) { ?>
										<table width=100% border=0><tr><td>
											<a class="btn btn-warning" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
												Архівні адреси
											</a> </td> <td align=right>
												<a class="btn btn-warning" data-toggle="collapse" href="#delList" role="button" aria-expanded="false" aria-controls="collapseExample">
													Кошик
												</a></td></tr></table>
												<div class="collapse" id="delList">
													<div class="card card-body">
														<?php
														foreach ($temp_del as $temp_adress) 
														{
															$te_id = $temp_adress['id'];
															$adr='';
															if ($temp_adress['postindex']) {
																$adr.=$temp_adress['postindex'].', ';
															}
															if ($temp_adress['region']!=='1') {
																$adr.=show_region_name($temp_adress['region']).', ';
															}
															if ($temp_adress['area']) {
																$adr.=$temp_adress['area'].', ';
															}
															if ($temp_adress['city']) {
																$adr.=$temp_adress['city'].', ';
															}
															echo $temp_adress['adress'].".<br>";
															if ($_SESSION['can_edit'] == '1'){
															echo "<a href='system/adress_temp_back.php?id=$te_id&now_id=$id'>Повернути адресу</a>";
															}
														}
														?>
													</div>
												</div>
												<?php } ?>

												<div class="collapse" id="collapseExample">
													<div class="card card-body">
														<?php
														foreach ($arch_adress as $value) 
														{
															$end_date=$value['end_date'];
															$remove_user=$value['edit_name'];
															$remove_user = show_editName($remove_user);
															$adr='';
															if ($value['postindex']) {
																$adr.=$value['postindex'];
															}
															if ($value['region']!=='1') {
																$adr.=show_region_name($value['region']).', ';
															}
															if ($value['area']) {
																$adr.=$value['area'].', ';
															}
															if ($value['city']) {
																$adr.=$value['city'].', ';
															}
															$adr.=$value['adress'].'.';
															?>

															<table width=100% border=0><tr><td><h6><?=$adr?></h6></td> 
																<td width=12%><?="<a title=\"Перенесено в архів користувачем $remove_user\">".date("d.m.Y", strtotime($end_date)).'</a>'?></td> 
																<td width=12%><?='<a title=\'Вх. №\'>'.$value['income_number'].'</a>'?></td> 
																<td width=12%><?='<a title=\'Дата вх. док.\'>'.date("d.m.Y", strtotime($value['income_date'])).'</a>'?>
																</a></td>  </tr></table>


																<h6></h6>
																<h6>Поштовий індекс: <?=$value['postindex'];?></h6>
																<h6>Види робіт: </h6>
																<ul>
																	<?php
																	$worktype_array = explode(",", $value['worktype']);
																	foreach ($worktype_array as $value_t) 
																	{
																		echo "<li>".show_work_name($value_t)."</li>";
																	}
																	?>
																</ul>
																<?php if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) { ?>
																<a href="adress_history.php?id=<?=$value['id'];?>&now_id=<?=$id;?>">Історія <?=count_changes($value['id'])?></a>
																<?php } ?>	
																<!--		<a href="system/del_from_arch.php?id=<?=$value['id']?>&now_id=<?=$id;?>"> Повернути адресу до активних</a>-->
																<hr>
																<?php
															}
															?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div style="margin-top: 60px;"></div>
							
							<?php 

							include('footer.php');?>