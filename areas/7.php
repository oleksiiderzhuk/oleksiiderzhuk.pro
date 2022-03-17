<?php 
session_start();
include ("sys/config.php");
$link = areas_db_connect();

/*if ($link){
	echo 'link ok!!!';
} 
else {
	echo 'no link';
}*/
	
$_SESSION['floor']='7';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Офіс - <?=$_SESSION['floor']?>-й поверх</title>
<!--		<script type="text/javascript" src=".js"></script>-->
		<link rel="SHORTCUT ICON" href="/img/floor.ico" type="image/x-icon">
		<link href="css/layout_styles.css" rel="stylesheet" type="text/css"> 	
		<link rel="stylesheet" href="css/floor_<?=$_SESSION['floor']?>.css" /> 
		<script type="text/javascript">
			(document).ready(function() {
				
			});	
		</script>

	</head>
	<body>
		
		<table width=80% border=0><tr>
		
	<?php 
//$in= $_SESSION['log_in'];
/*if ($in!=='1') {
echo "<td width=45%>";
	include	('sys/enter_pass.html');
echo "</td><td>";
	include ('sys/pagination.php');
echo '</td></tr></table>';

}
else{*/
/*echo "<td width=45%>";
	//include ('sys/exit_button.html');
//echo "</td><td>";*/
	include ('sys/pagination.php');
echo '</td></tr></table>';

?>


                <td width=70% align=center>
		
		
		</td></tr></table>
	

<div style='margin-left: 0px'>

<?php
$min = $_SESSION['floor']*100;
$max = $_SESSION['floor']*100 + 100;
for ($k=$min; $k<$max; $k++) {
$roomquan=0;
$vals=0;

include ('sys/query.php');
//$sq=7;
include ('sys/poster.php');


$cv=0;
$roomquan=0;
$nsp=array();
$keys=array();
$vals=array();
$unicode=array();
$code=array();
}
//}
//include 'sys/areas_visit_stat.php';
?>
<div style="z-index: 1">
<img src='img/<?=$_SESSION['floor']?>.jpg'>
</div>
</div>
</body>
</html>
<?php 
//include 'footer.php';
?>