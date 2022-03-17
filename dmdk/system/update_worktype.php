<?php

include("../probirka_db_functions.php");
$link = probirka_db_connect();

$sql = "SELECT * FROM adress";
$result = mysqli_query($link, $sql);

$sql = "UPDATE works SET adress = NULL";
mysqli_query($link, $sql);

foreach ($result as $adress) 
{
	$works_array = explode(",", $adress['worktype']);
	foreach ($works_array as $single_work) 
	{
		if($single_work==NULL or $single_work=="" or $single_work==" ")
		{
			continue;
		}
		else
		{
			$sql = "SELECT * FROM works WHERE workid = $single_work";
			echo "1 ".$sql."<br>";
			$adress_list_work = mysqli_fetch_array(mysqli_query($link, $sql))['adress'];
			if($adress_list_work==NULL or $adress_list_work=="" or $adress_list_work==" ")
			{
				$adress_list_work = $adress['id'];
			}
			else
			{
				$adress_list_work = $adress_list_work.",".$adress['id'];
			}
			$sql = "UPDATE works SET adress = '$adress_list_work' WHERE workid = $single_work";
			echo "2 ".$sql."<br>";
			mysqli_query($link, $sql);
		}
		
	}
}

?>