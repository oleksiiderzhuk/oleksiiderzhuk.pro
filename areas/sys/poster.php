<?php 

$workers_amount = $qua[$k];
	/*if ($sq>1) {
		$sq_per_worker = round($sq/$workers_amount, 2);
	}*/
echo "<div class=\"poster\" id=\"layer_$k\">
<div class=\"descr\" style=\"width: 250px\">
<b style=\"font-size: 24pt;\">$k</b><a style=\"font-size: 12pt;\">";

//if ($sq>1) {
//	echo "<br>$sq м<sup>2</sup>";
//}

//if ($workers_amount>1) {
//	if ($sq>1) {
//		echo " ($sq_per_worker м<sup>2</sup> / чол.)</a>";
//	}
//
//}
	echo "<br><b><a style=\"font-size: 20pt\">";
	
	if ($workers_amount>0){
		echo $workers_amount."</a>&nbsp;чол.</b>";
	}
	/*if ($splistnum>1) {
		echo " у " . $splistnum . '-х підрозділах';
	}*/	
	echo "<b style=\"font-size: 8pt\">";

	echo "<br>";
//	$room_code_num = 0;
				foreach ($splist as $key => $value) {
				//	$cod=$unicode[$key];
						//echo "&#8226; <a style=\"font-size: 10pt;\" href=\"\" target=\"_blank\">"./*$cod." ".*/$splist[$key]."</a><br>";

					/*if ($splistnum>1) {
						$room_code_amount = $code_amount[$cod];
						echo "<a style=\"font-size: 10pt;\">".$room_code_amount."&nbsp;чол.</a><br>";
					}*/
/*					for ($i=0; $i<$room_code_amount; $i++) {
						echo $fio_array[$i]."<br>";
					}
*/					
	/*				$room_code_num++;
					$room_codes_table[][] = [$room_code_num][$room_code_amount];
	*/			
				}
	echo "<br>";				
				foreach ($ind_p_unilist as $ind){
					echo $ind."<br>";
				}
				foreach ($fio_array as $fio){
					echo $fio."<br>";
				}
		
				$pib1 = array();
				$ind_p_unilist = array();
				echo "</b>";

	echo "</div></div>";
	
 ?>