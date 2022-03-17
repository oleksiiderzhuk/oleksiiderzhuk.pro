<?php 
$link = areas_db_connect();

$sql="SELECT TRIM(ind_p), room, name_p, posada, pib1, pib2, pib3, tel_10, tel_vn, tel_zn, tel_ho, 
korp, unicod, poryad, datez FROM areas_table WHERE TRIM(room) = '$k' AND TRIM(korp) = '2' AND TRIM(pib1) NOT LIKE '_'
AND TRIM(pib1) NOT LIKE '.%' 
AND TRIM(ind_p) NOT LIKE '9996' AND TRIM(ind_p) NOT LIKE '9991' AND TRIM(ind_p) NOT LIKE '99999' ORDER BY ind_p";
//echo $sql."<br>";
 $result = mysqli_query($link, $sql);
 $count = 0;
 $splist = array();
 $pib1 = array();
 $code = array();
 $ind_p = array();
 $fio_array = array();
 $ind_p_list = array();
	foreach ($result as $value) {
		$count++;
		$pib1[]=$value['pib1'];
		$pib2[]=$value['pib2'];
		$pib3[]=$value['pib3'];
		$ind_p_list[]=$value['TRIM(ind_p)'];
		$fio=
		//$value['pib1'].' '.
		$value['pib2'].' '.$value['pib3'];
		$fio_array[] = $fio;
		//	echo 'fio: '.$fio."<br>";
		$code[]=$value['TRIM(ind_p)'];
		$roomquan+=1;
	}
		/*
-------------
 $getpi = oci_parse($conn, $query);
 $pi=oci_execute($getpi);
 while (oci_fetch($getpi)){
	$nsp[]=oci_result($getpi, "NAME_P");
	$code[]=oci_result($getpi, "TRIM(A.IND_P)");
	$code_temp=oci_result($getpi, "TRIM(A.IND_P)");
	
		$pib1=oci_result($getpi, "PIB1");
		$pib2=oci_result($getpi, "PIB2");
		$pib3=oci_result($getpi, "PIB3");
		$fio=$pib1.' '.$pib2.' '.$pib3;
		$fio_array[] = $fio;
		//	echo 'fio: '.$fio."<br>";
	
//	$plroom[$k]=oci_result($getpi, "PLROOM");
	*/


/*$qusqu="SELECT a.nroom, a.plroom, a.street
  FROM rooms a where trim(nroom) = '$k' and trim(street) = '2'";
 $getsqu = oci_parse($conn, $qusqu);
 $squ=oci_execute($getsqu);
 $sq='?';
while (oci_fetch($getsqu)){
$sq=oci_result($getsqu, "PLROOM");
}*/

//?$countvals=array_count_values ($pib1);
$ind_p_unilist = array_unique($ind_p_list);
$splist = array_unique($pib1);
$unicode= array_unique($code);

$code_amount = array_count_values ($code);

//print_r($unicode);
//$splistnum=count($splist);
//$keys=array_keys($countvals);
//$vals=array_values($countvals);
//$cv=count($vals);
$qua[$k]=$roomquan;

?>