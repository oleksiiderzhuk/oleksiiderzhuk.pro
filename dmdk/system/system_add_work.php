<?php
include('../probirka_db_functions.php');
$link = probirka_db_connect();
$workname = $_POST['workname'];
$sql = "INSERT INTO `works` (`workid`, `workname`, `adress`) VALUES (NULL, '$workname', NULL)";
mysqli_query($link, $sql);
header("Location: ../works_list.php");
?>