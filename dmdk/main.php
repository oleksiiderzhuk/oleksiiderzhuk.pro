<?php 
// session_start();
include("header.php");
$self=$_SERVER['PHP_SELF'];
$self=substr($self, 1);
$_SESSION['self']=$self;
$_SESSION['can_edit'] = '1';
?>
    <!doctype html>
    <html lang="en">
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-164385942-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-164385942-1');
        </script>

        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
                <div style="margin-left: 50px"><a href="../"><h4><< Home</h4></a></div>

	<div class="row" style="margin-top: 0px;">
		<div class="col-md-12" align="center">

<div class="container" style="margin-top: 0px">
		<div class="col-md-12" align="center">
            <div class="container" >
<!--					<h4 align="center">Реєстр суб'єктів господарювання</h4>-->
                                <br>
                <!--		<div class="container" ><br>-->
<!--			<h4 align="center"><a href='phpword.php'>Docx</a></h4><br>-->
<!--		</div>-->
<!--		<div class="container" ><br>-->
<!--			<h4 align="center"><a href='sendmail.php'>Send mail</a></h4><br>-->
<!--		</div>-->
			
	<?php
		if($_GET['incorrect'])
		{
			echo "<h5 class='index-text' align='center' style='color:red'>Невірний логін або пароль!</h5>";
		}
	?>
</div>
			<?php 	
			if ($_SESSION['can_edit'] == '1') {
			
				echo '

				<p>
				<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="height: 50px">
				Внесення суб\'єкту господарювання
				</button>
				</p>';
							
			}

			?>
			<div class="collapse" id="collapseExample" align='center'>
				<div class="card card-body" align='center'>
					<a href="add_jur.php" class="btn btn-warning" >Юридична особа</a>
					<div style="margin-top: 10px;"></div>
					<a href="add_fiz.php" class="btn btn-warning" >Особа-підприємець</a>
					<div style="margin-top: 10px;"></div>
					<center><a href="works_list.php" class="btn btn-warning" style="width:60%;">Види діяльності</a></center><br>
					<center><a href="opf_list.php" class="btn btn-warning" style="width:60%;">Редагувати ОПФ</a></center>
				</div>
			</div>

	<!--
	<div class="form-group col-md-4" >
		<label for="opf_id">Орг-прав форма</label>
		<select name="opf_id" class="form-control">
			<?php
			/*
			$link = probirka_db_connect();
			$sql = "SELECT * FROM opf";
			$result = mysqli_query($link, $sql);
			echo "<option >-</option>";
			while($temp = mysqli_fetch_assoc($result)){
				if ($temp['opf_name']!=='Фізична особа - підприємець') {
					echo "<option >".$temp['opf_name']."</option>";
				}
			}
			*/
			?>
			
		</select>
	</div>
	-->
		<?php 	
			if ($_SESSION['can_edit'] == '1') {
			
				echo '
				<p>
				<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#docs_form" aria-expanded="false" aria-controls="collapseExample" style="height: 50px">
				Звіти
				</button>
				</p>';
			}
		?>	

			<div class="collapse" id="docs_form">
				<div class="card card-body">
	
<center>
	<a href="https://drive.google.com/file/d/1BXVjnP5W6CFgJyQAmoJX-hcRmheIKKBW/view?usp=sharing" class="btn btn-warning" style="width:60%;" >Всі суб'єкти</a></center>
					<!-- <div style="margin-top: 10px;"></div>
					<center><a class="btn btn-warning"  style="width:60%;">Виробники</a></center>
					<div style="margin-top: 10px;"></div>
					<center><a class="btn btn-warning" style="width:60%;">Іменники</a></center><br>
				</div> -->
			</div>
		</div>
	</div>

		
	<div class="row" style="margin-top: 30px; margin-bottom: 60px;" align="center"> 
		<div class="col-md-12" align="center" style="background-color: rgba(36,105,203,0.26);"><br>
		

		
			<div class="form-group sm-3 mb-2">
				<input type="text" name="find"  style="text-align: center; width:65%; height: 50px" placeholder="Пошук за номером в реєстрі; іменем суб'єкта; ЄДРПОУ (ІПН) або МІСТОМ" id="find" autofocus>				
	<!--		<input type="checkbox" style="width: 20px; height: 20px; vertical-align: middle" name="archive_search" value="arc"> шукати і серед архівних			-->
	&nbsp;
			<input type="submit" name="enter" class="btn btn-primary mb-2" style="width:15%; height: 50px"  id="search_this" value="Знайти"></div>
			
		</div>
	</div>
	
	
	
	<script src="js/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var limit = 50;
			$("#search_this").click(function() {
				var find = $("#find").val();
				$.post("load_main_result.php", {
					new_find: find, new_limit: limit
				}, function(data, status) {
					$("#load_result_div").html(data);
				});
			});
			$('html').keydown(function(e)
			{ //отлавливаем нажатие клавиш
				if (e.keyCode == 13) 
 	 		{ //если нажали Enter, то true
 	 			var find = $("#find").val();
 	 			$.post("load_main_result.php", {
 	 				new_find: find, new_limit: limit
 	 			}, function(data, status) {
 	 				$("#load_result_div").html(data);
 	 			});
 	 		}
 	 	});
		});
	</script>
	<div id = "load_result_div">
	<!------>
	<?php

			$find = '';
			$find=mysqli_real_escape_string($link,$find);
			$sql = "SELECT * FROM person where state='1' || state=3 ORDER BY date_start DESC limit 20 ";

		//echo $sql."<br>";

			$result = mysqli_query($link, $sql);
			$count = 0;
			foreach ($result as $value) {
				
				$count++;
				$state=$value['state'];
				
				?>
				<div class="row">
					<div class="col-md-12">
						<div class="jumbotron" 	
				<?php 
					if (( $_SESSION['can_edit']=='1' )||( $_SESSION['can_edit']=='2' )) {
						 
						if ($state=='3') {
									echo " style='background-color: yellow'";
							}
						else {
							echo " style='background-color: white'";
						}
					}						
				?>					
						>
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
							if (($_SESSION['can_edit'] == '1')||( $_SESSION['can_edit']=='2' )) {
								$sql = "SELECT * FROM imen WHERE person_id = $person_id and is_valid = '1' order by reg_date desc limit 1";
								$cipher = mysqli_fetch_array(mysqli_query($link, $sql))['cipher'];
								if($cipher){
									echo "<span style='font-weight: bold; color: red' title='Шифр іменника'>$cipher</span><br>";
								}
							}	
							?>
							<span title="Податковий номер">
							<?php
							if ($opf==0) {
								if (( $_SESSION['can_edit']=='3' )||( $_SESSION['can_edit']=='4' )) {
									echo $value['tax_code']."<br>";
								}
								else echo '*****<br>';
							}
							else {
									echo $value['tax_code']."<br>";
							}
							?></span>
					<!--	<span>REGNO: <?=$value['regno']?></span><br>
						<span><b>Date: <?=$value['date']?></b></span><br>-->
						<?php 
						$person_id=$value['person_id'];
						$sql = "SELECT * FROM adress WHERE person_id = $person_id AND is_main = 1 AND (current = 1 || current = 3) order by start_date desc limit 1";
						//echo $sql."<br>";

						$result = mysqli_query($link, $sql);
						if ($opf==0) {
							echo "<a title='Адреса проживання'>";
						}
						else {
							echo "<a title='Адреса'>";
						}
						$adr='';
						foreach ($result as $value) {
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
								if (( $_SESSION['can_edit']=='3' )||( $_SESSION['can_edit']=='4' )) {
									if ($value['current']==3) {
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
						$rows = mysqli_num_rows($result); 
						if ($rows>0) {
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
						}

						$sql="select * from adress where person_id = '$person_id' and current='1'";

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
		<div id="test">
		</div>
		<?php
		if($count%20==0)
		{
			echo "<button class='btn btn-warning' id='load_more'>Завантажити ще</button>";
		}

		?>
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				var limit = 0;
				$("#load_more").click(function() {
					limit = limit + 20;
					$.post("load_more_reestr.php", {
						new_limit: limit
					}, function(data, status) {
						$("#test").html(data);
					});
				});
				
			});
		</script>
	<!------>
	</div>
	
</div>			

			
	</div>
</div>
</div>

<div style="margin-top: 120px;"></div>

<?php include("footer.php"); ?>