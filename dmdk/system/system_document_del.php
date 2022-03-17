<?php
include('../probirka_db_functions.php');
$link = probirka_db_connect();
$id = $_GET['id'];
$person_id = $_GET['person_id'];
$file_name = $_GET['file_name'];
unlink('$file_name');
$sql = "DELETE FROM documents WHERE id = $id";

mysqli_query($link, $sql);
header("Location: ../document_list.php?person_id=$person_id");
?>