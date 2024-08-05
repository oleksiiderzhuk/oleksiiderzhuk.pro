<?php
include('../probirka_db_functions.php');
$link = probirka_db_connect();
$opf_name = $_POST['opf_name'];
$alias = $_POST['alias'];
$opf_id = $_POST['opf_id'];
$sql = "UPDATE opf SET opf_name = '$opf_name', alias = '$alias' WHERE opf_id = $opf_id";
mysqli_query($link, $sql);
header("Location: ../opf_list.php");
?>