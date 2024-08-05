<?php
//include '../../db/db.php';
function probirka_db_connect()
{
	
  $link = mysqli_connect("localhost", "root", "password", "local_database");
// $link = new mysqli("localhost", "jbkjmgql_root", "Password4root", "jbkjmgql_database");
    $link->set_charset("utf8");
	if (mysqli_connect_errno())
	{
		echo "Ошибка в подключении";
		exit();
	}
	return $link;
}

function show_work_name($id)
{
	$w_id = $id;
	$link = probirka_db_connect();
	$sql = "SELECT workname FROM works WHERE workid=$w_id";
	$result_work = mysqli_query($link, $sql);
	$work_name = mysqli_fetch_array($result_work)['workname'];
	return $work_name;
}

function show_work_id($name)
{
	$link = probirka_db_connect();
	$sql = "SELECT workid FROM works WHERE workname='$name'";
	$result_work = mysqli_query($link, $sql);
	$work_name = mysqli_fetch_array($result_work)['workid'];
	return $work_name;
}

function show_editName_id($editName)
{
	$link = probirka_db_connect();
	$sql = "SELECT id FROM users WHERE name like '$editName'";
	$result_editName = mysqli_query($link, $sql);
	$editName_id = mysqli_fetch_array($result_editName)['id'];
	return $editName_id;
}

function show_editName($editName_id)
{
	
	$link = probirka_db_connect();
	$sql = "SELECT name FROM users WHERE id=\"$editName_id\"";
	$result_editName = mysqli_query($link, $sql);
	$editName = mysqli_fetch_array($result_editName)['name'];
	return $editName;
}

function show_opf_id($opf_name)
{
	$link = probirka_db_connect();
	$sql = "SELECT opf_id FROM opf WHERE opf_name='$opf_name'";
	$result_opfid = mysqli_query($link, $sql);
	$opf_id = mysqli_fetch_array($result_opfid)['opf_id'];
	return $opf_id;
}

function show_opf_name($id)
{
	$link = probirka_db_connect();
	$sql = "SELECT opf_name FROM opf WHERE opf_id='$id'";
	$result_opf_name = mysqli_query($link, $sql);
	$opf_name = mysqli_fetch_array($result_opf_name)['opf_name'];
	return $opf_name;
}

function show_opf_alias($id)
{
	$link = probirka_db_connect();
	$sql = "SELECT alias FROM opf WHERE opf_id='$id'";
	$result_opf_name = mysqli_query($link, $sql);
	$opf_name = mysqli_fetch_array($result_opf_name)['alias'];
	return $opf_name;
}

function show_region_name($id)
{
	$link = probirka_db_connect();
	$sql = "SELECT region_name FROM region WHERE region_id = $id";
	$result_region_name = mysqli_query($link, $sql);
	$region_name = mysqli_fetch_array($result_region_name)['region_name'];
	return $region_name;
}

function show_region_id($name)
{
	$link = probirka_db_connect();
	$sql = "SELECT region_id FROM region WHERE region_name = '$name'";
	$result_region_id = mysqli_query($link, $sql);
	$region_id = mysqli_fetch_array($result_region_id)['region_id'];
	return $region_id;
}

function show_imen_id($imen_type_name)
{
	$link = probirka_db_connect();
	$sql = "SELECT id FROM imen_type WHERE imen_type_name='$imen_type_name'";
	$result_type_id = mysqli_query($link, $sql);
	$imen_type_id = mysqli_fetch_array($result_type_id)['id'];
	return $imen_type_id;
	echo 'qqq'.$imen_type_id;
	echo 'qqq'.$imen_type_name;

	}

function show_imen_type($imen_type_id)
{
	$link = probirka_db_connect();
	$sql = "SELECT imen_type_name FROM imen_type WHERE id = '$imen_type_id'";
	$result_imen_type = mysqli_query($link, $sql);
	$imen_type = mysqli_fetch_array($result_imen_type)['imen_type_name'];
	return $imen_type;
}

function show_chain($link_t, $id, $main_id)
{
	$m_id = $main_id;
	$link = $link_t;
	$sql = "SELECT * FROM person WHERE id=$id";
	$result = mysqli_query($link, $sql);
	$temp = mysqli_fetch_array($result);
	$name = $temp['name'];
	$date = $temp['date'];
	$pre = $temp['pre'];
	echo "<div class='row' style='margin-top:20px;'><div class='col-md-12'><div style='border: 3px solid grey; background-color:#e7e7e7e0; border-radius:10px;'><div style='margin:5px'>";
	echo "<b>".$name."</b><br>";
	echo $date."";
	echo "<a href='show_single_chain.php?id=$id&back=$m_id' class='btn btn-primary' style='float: right; margin-top:-18px;'>Детальніше</a>";
	echo "</div></div></div></div>";
	if($pre)
	{
		show_chain($link, $pre, $m_id);
	}
}
function show_title()
{
	if($_SERVER['PHP_SELF'] == '/index.php')
	{
		echo "Авторизація";
	}
}
function check_value($table,$var,$value)
{
	$link = probirka_db_connect();
	$sql = "SELECT * FROM $table WHERE $var = '$value' and (state = '1' || state = '3')";
	$result = mysqli_query($link, $sql);
	$count = mysqli_num_rows($result);
	if($count > 0)
	{
		return True;
	}
	else
	{
		return False;
	}
}
function check_imen($table,$var,$value,$person_id)
{
	$link = probirka_db_connect();
	$sql = "SELECT * FROM $table WHERE $var = '$value' and is_valid = 1 and person_id <> '$person_id'";
	$result = mysqli_query($link, $sql);
	$count = mysqli_num_rows($result);
	if($count > 0)
	{
		return True;
	}
	else
	{
		return False;
	}
}

function count_changes($id)
{
	$link = probirka_db_connect();
	$sql = "SELECT * FROM adress WHERE id = $id";
	$adress_result = mysqli_fetch_array(mysqli_query($link, $sql));
	$count_version = $adress_result['version'] - 1;
	$last_letter = substr($count_version, -1);
	if($last_letter==0)
	{
		return '(без змін)';
	}
	if($last_letter==5 or $last_letter==6 or $last_letter==7 or $last_letter==8 or $last_letter==9)
	{
		$ending = ' змін';
	}
	if($last_letter==1)
	{
		$ending = ' зміна';
	}
	if($last_letter==2 or $last_letter==3 or $last_letter==4)
	{
		$ending = ' зміни';
	}
	$final_string = "(".$count_version.$ending.")";
	return $final_string;
}
function count_person_changes($id)
{
	$link = probirka_db_connect();
	$sql = "SELECT * FROM person WHERE id = $id";
	$person_result = mysqli_fetch_array(mysqli_query($link, $sql));
	$count_version = $person_result['version'] - 1;
	$last_letter = substr($count_version, -1);
	if($last_letter==0)
	{
		return '(без змін)';
	}
	if($last_letter==5 or $last_letter==6 or $last_letter==7 or $last_letter==8 or $last_letter==9)
	{
		$ending = ' змін';
	}
	if($last_letter==1)
	{
		$ending = ' зміна';
	}
	if($last_letter==2 or $last_letter==3 or $last_letter==4)
	{
		$ending = ' зміни';
	}
	$final_string = "(".$count_version.$ending.")";
	return $final_string;
}

function ukr_date()
{
	$day = date("d");
	$year = date("Y");
	$month = date("m");
	if(date("m")=="01")
	{
		$month = "січня";
	}
	if(date("m")=="02")
	{
		$month = "лютого";
	}
	if(date("m")=="03")
	{
		$month = "березня";
	}
	if(date("m")=="04")
	{
		$month = "квітня";
	}
	if(date("m")=="05")
	{
		$month = "травня";
	}
	if(date("m")=="06")
	{
		$month = "червня";
	}
	if(date("m")=="07")
	{
		$month = "липня";
	}
	if(date("m")=="08")
	{
		$month = "серпня";
	}
	if(date("m")=="09")
	{
		$month = "вересня";
	}
	if(date("m")=="10")
	{
		$month = "жовтня";
	}
	if(date("m")=="11")
	{
		$month = "листопада";
	}
	if(date("m")=="12")
	{
		$month = "грудня";
	}
	echo "«".$day."» ".$month." ".$year." року";
}


?>
