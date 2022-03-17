<?php
session_start();
$edit_name = $_SESSION['user'];
include('../probirka_db_functions.php');

$link = probirka_db_connect();
$person_id= $_POST['person_id'];
$id_to_delete= $_POST['id_to_delete'];
$now=date('Y-m-d H:i:s');
$sql = "update imen set end_date = '$now', is_valid = 0 where id = '$id_to_delete'";
$result = mysqli_query($link, $sql);
if ($result) {
header("Location: ../imen.php?person_id=$person_id");
}
else {
echo $sql."<br>";
}


?>

