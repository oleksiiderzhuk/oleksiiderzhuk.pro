<?php
include('../probirka_db_functions.php');
$link = probirka_db_connect();
$workname = $_POST['workname'];
$workid = $_POST['workid'];
$sql = "UPDATE works SET workname = '$workname' WHERE workid = $workid";
mysqli_query($link, $sql);
header("Location: ../works_list.php");
?>