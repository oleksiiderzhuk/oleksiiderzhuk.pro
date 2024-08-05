<?php
session_start();
$edit_name = $_SESSION['user_id'];
?>
<?php header("Content-Type: text/html; charset=utf-8"); ?>
<?php
include('../probirka_db_functions.php');
$link = probirka_db_connect();
$name = $_POST['name'];
$person_id = $_POST['person_id'];
if($_FILES['document']['error']==0)
{
	$files_document = [];
	$temp_array = explode(".", $_FILES['document']['name']);
	if($_POST['name'])
	{
		if(count($temp_array)>=2)
		{
			$files_document['name'] = $name."3_3".time().".".$temp_array[count($temp_array)-1];
		}
		else
		{
			$files_document['name'] = $name."3_3".time();
		}
	}
	else
	{
		$files_document['name'] = $temp_array[count($temp_array)-2]."3_3".time().".".$temp_array[count($temp_array)-1];
	}
	
	$files_document['type'] = $_FILES['document']['type'];
	$files_document['tmp_name'] = $_FILES['document']['tmp_name'];
	$files_document['error'] = $_FILES['document']['error'];
	$files_document['size'] = $_FILES['document']['size'];
	$uploaddir = '../uploads/documents/';
	$uploadfile = $uploaddir . basename($files_document['name']);
	$uploadfile =  iconv('utf-8','windows-1251', $uploadfile);
	if (move_uploaded_file($files_document['tmp_name'], $uploadfile)) 
	{
	} 
	else 
	{
		echo "Возможная атака с помощью файловой загрузки!1\n";
	}
	$document_name = basename($files_document['name']);
}


$sql = "INSERT INTO documents (id, person_id, name, data, edit_name, del) VALUES (NULL, '$person_id', '$document_name', CURRENT_TIMESTAMP, '$edit_name', '0')";
mysqli_query($link, $sql);
header("Location: ../document_list.php?person_id=$person_id");
?>