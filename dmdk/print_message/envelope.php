<?php 

include('../probirka_db_functions.php');
$link = probirka_db_connect();

?>
<?php date_default_timezone_set('Europe/Kiev');?>

<html>
<head>
	<meta charset="UTF-8">
	<title>Конверт</title>
	<style type="text/css" media="print"> 
		div.no_print {display: none; } 
	</style> 
	<style>
		* {
			margin: 0;
			padding:5px;
		}
		p {
			margin: 0px 0;
		}
		.container {
			width:1024px;
			min-height: 80vh;
			font-family: Times New Roman;
			font-size: 22pt;
			color: #000;
			margin: 20px auto;
			padding-left: 40px;
		}
		.title {
			text-transform: uppercase;
			text-align: center;
			margin: 40px 0;
		}
		.to {
			width: 650px;
			float: right;
			font-size: 22pt;
			text-align: justify;
		}
		.clear {
			clear: both;
			font-size: 22pt;
			text-align: justify;
		}
		.bold {
			font-weight: bold;
			font-size: 22pt;
		}
		.activity th,.activity td {
			width:50%;
			padding: 15px;
			border:1px solid #000;
			font-size: 22pt;
		}
		.signature {
			margin-top: 40px;
			font-size: 22pt;
		}
		.signature td {
			width: 30%;
			font-size: 22pt;
		}
		.wrapper {
			min-height: 80vh;
		}
		.footer {

		}
	</style>
	<style type="text/css" media="print"> 
		div.no_print {display: none; } 
	</style> 
</head>
<body>
	<div class="container">
		<div class="wrapper">

			<div class="to">
				<p>
					<?php
					$id = $_GET['id'];
					$sql = "SELECT * FROM person WHERE id=$id";
					$person_id_result = mysqli_query($link, $sql);
					$person = mysqli_fetch_array($person_id_result);
					$person_id = $person['person_id'];
					$opf_id=$person['opf_id'];
					if ($opf_id==0) {
						$opf='ФОП';
						$opff='Фізична особа - підприємець';
					}
					else {
						$opf=show_opf_name($person['opf_id']);
					}
					$sql = "SELECT DATE_FORMAT(income_date,'%Y-%m-%d') as 'i_date', income_number FROM adress WHERE person_id = $person_id AND is_main = 1";
					
					$income_adress_result = mysqli_query($link, $sql);
					$income_adress = mysqli_fetch_array($income_adress_result);
					$income_date = $income_adress['i_date'];
					$income_number = $income_adress['income_number'];

					$sql = "
					SELECT * FROM adress 
					WHERE person_id = '$person_id ' 
  					AND is_main = 1 
  					AND (current = 1 OR current = 3)
  					AND VERSION = (SELECT MAX(VERSION) FROM ADRESS WHERE person_id = '$person_id'  AND is_main = 1)
  					";
					$main_adr_result = mysqli_query($link, $sql);
					$main_adr = mysqli_fetch_array($main_adr_result);

					$name= $person['name'];
					$mainadr='';
					$mainadr .= ''.$main_adr['adress'].'<br>';
					if ($main_adr['city']) {
						$mainadr.='<a style="margin-left: 0px">'.$main_adr['city']."</a><br>";
					}
					if ($main_adr['area']) {
						$mainadr.=' '.$main_adr['area']."<br>";
					}
					
					$region=$main_adr['region'];
					if (($region)&&($region!=='1')) {
						
						$mainadr.=' '.show_region_name($region)."<br>";
					}
					
					
					if ($main_adr['postindex']) {
						$mainadr.='<a style="margin-left: 0px">'.$main_adr['postindex']."</a><br>";
					}
					$regno = $person['regno'];
					?>
					<b style="font-size: 20pt"><?php echo '<a style="margin-left: -10px">';
					
							if ($opf_id=='0') {
								echo $opff;
							}
							 else {
								echo $opf;
							}
							
					echo '</a><br>'.$name; ?></b><br>
				</p>
				<?php echo '<a style="font-size: 20pt">'.$mainadr.'</a>'; ?>
				
				<div class="no_print">
					<button type="print" onClick="window.print()">Надрукувати</button>
				</div>
			</div>
		

</body>
</html>