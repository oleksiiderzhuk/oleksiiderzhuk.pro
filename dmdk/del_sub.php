<?php
include_once("probirka_db_functions.php");
$link = probirka_db_connect();
$id = $_GET['id'];
$sql = "SELECT * FROM person WHERE id=$id";
$person_id_result = mysqli_query($link, $sql);
$person = mysqli_fetch_array($person_id_result);
$person_id = $person['person_id'];
$income_date = $_GET['income_date'];
$income_number = $_GET['income_number'];
$current_date = date('Y-m-d H:i:s');
$edit_name=$_SESSION['user'];

if ($id)
{
	$sql = "UPDATE person SET state = 2, date_end = '$current_date' WHERE id = $id;";

	mysqli_query($link, $sql);

	$sql = "SELECT MAX(adress_id) as id FROM adress";
	$result = mysqli_query($link, $sql);
	$max_adress_id = mysqli_fetch_array($result)['id']+1; 

	$sql = "INSERT INTO adress (id, version, adress_id, person_id, is_main, postindex, region, city, area, adress, worktype, isvalid, income_date, income_number, edit_name, start_date, end_date, current) VALUES (NULL, 1, $max_adress_id, $person_id, 0, '0', '0', '0', '0', '06032018', '0', 0, '$income_date', '$income_number', '$edit_name', '$current_date', '6666-07-13 13:13:13', 0);";
	mysqli_query($link, $sql);
	header("Location: main.php");
}
else
{
	?>
	<html>
	<head>
		<meta charset="UTF-8">
		<title>Реєстраційне повідомлення</title>
	<style type="text/css" media="print"> 
		div.no_print {display: none; } 
	</style> 
	<style>
			* {
				margin: 0;
				padding:0;
			}
			p {
				margin: 20px 0;
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
				width: 600px;
				float: right;
				font-size: 22pt;
			}
			.clear {
				font-size: 22pt;
				clear: both;
			}
			.bold {
				font-weight: bold;
				font-size: 22pt;
			}
			.activity th,.activity td {
				width:50%;
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
				font-size: 22pt;
			}
			.footer {
				font-size: 22pt;
				margin-top: -250px;
			}
		</style>
		<body>
		</head>
		<?php 
		$sql = "SELECT * FROM adress WHERE person_id = $person_id AND is_main = 1 AND current = 1";
		$main_adr_result = mysqli_query($link, $sql);
		$main_adr = mysqli_fetch_array($main_adr_result);

		$dateinput= $income_date;
		$regnumber=	$income_number;
		$adr = $main_adr['region']." ".$main_adr['city']." ".$main_adr['area']." ".$main_adr['adress'];
		$name = $person['name'];
		$today=date('d.m.Y');
		?>


	</body>
	</html>
	<?php
}

?>