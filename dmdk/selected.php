<?php 
session_start();
error_reporting(0); 
include("header.php");
$user=$_SESSION['user'];
echo '<a style="margin-left: 2%">'.$user." - <a href=\"/\">Вийти</a></a> <br><br>";
$from=$_POST['from'];
$to= $_POST['to'];
$works= $_POST['worktype'];
$opf= $_POST['opf_id'];
$opfid= show_opf_id($opf);
echo '<h3>'.$opf." (".$opfid.")</h3>";
echo ''. date("d.m.Y", strtotime($from))." – ";
echo ''. date("d.m.Y", strtotime($to)) ."<br>";
$i=0;

/*while ($row=$works[$i]) {

$opfid=show_work_id($wid);	
	echo '<li>'.$row."</li>";
	$i++;
	$opf_id_array[]=$opfid;
}*/

?>

<?php

		$find = '';
		$find=mysqli_real_escape_string($link,$find);
		$sql = "SELECT * FROM person WHERE date_start BETWEEN '$from' AND '$to' 
		
		and state='1' ORDER BY date_start DESC limit 100 ";
		
		echo $sql."<br>";
		
		$result = mysqli_query($link, $sql);
		foreach ($result as $value) 
		{
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="jumbotron">

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


<?php include("footer.php"); ?>