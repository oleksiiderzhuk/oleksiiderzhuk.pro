<?php 
$link = areas_db_connect();

$sql_pidrozsil="SELECT TRIM(ind_p), room FROM teldov WHERE TRIM(room) = '$k' AND TRIM(korp) = '2' AND TRIM(pib1) NOT LIKE '_' 
AND TRIM(pib1) NOT LIKE '.%' 
AND TRIM(ind_p) NOT LIKE '9996' AND TRIM(ind_p) NOT LIKE '9991' AND TRIM(ind_p) NOT LIKE '99999' ORDER BY ind_p";
//echo $sql_pidrozsil;
$result = mysqli_query($link, $sql_pidrozsil);
$pidrozdil = array();
foreach ($result as $value) {
	$pidrozdil[]=$value['TRIM(ind_p)'];
	//echo $value['TRIM(ind_p)'].'<br>';
}
$unidep= array_unique($pidrozdil);
//print_r($unidep);
foreach ($unidep as $value) {
	$dep[] = $value;
	//echo $value.'<br>';
}
/*
$ind_p_unilist = array_unique($ind_p_list);
$splist = array_unique($pib1);
$unicode= array_unique($code);

$code_amount = array_count_values ($code);
*/

 ?>