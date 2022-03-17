<?php
session_start();
include('../probirka_db_functions.php');
$self=$_SESSION['self'];
$link = probirka_db_connect();
unset( $_SESSION['user'] );
unset( $_SESSION['user_id'] );
$_SESSION['can_edit']= '0';
header("Location: ../$self");
?>