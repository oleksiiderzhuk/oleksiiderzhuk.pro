<?php
include('../probirka_db_functions.php');
$link = probirka_db_connect();
$opf_name = $_POST['opf_name'];
$alias = $_POST['alias'];
$sql = "INSERT INTO `opf` (`opf_id`, `opf_name`, `alias`) VALUES (NULL, '$opf_name', '$alias')";
mysqli_query($link, $sql);
header("Location: ../opf_list.php");
?>