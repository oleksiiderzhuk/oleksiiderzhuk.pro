<?php
session_start();
$edit_name = $_SESSION['user'];
include('../probirka_db_functions.php');

$link = probirka_db_connect();
$current_date = date('Y-m-d H:i:s');
$now_id = $_POST['id'];

$sql = "SELECT * FROM person where id = '$now_id'";
$result = mysqli_query($link, $sql);
foreach ($result as $value)	{
if ($value['state']=='1') {
$sql = "UPDATE person SET state = '3' WHERE id = '$now_id'";
mysqli_query($link, $sql);
}
else {
$sql = "UPDATE person SET state = '1' WHERE id = '$now_id'";
mysqli_query($link, $sql);
}
}

header("Location: ../info_menu.php?id=$now_id");
?>

