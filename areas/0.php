<?php
error_reporting(E_ALL);

// $servername = "localhost";
// $username = "jbkjmgql_root";
// $password = "Top4password";
// $database = "jbkjmgql_database";

$mysqli = new mysqli("localhost", "jbkjmgql_root", "Top4password", "jbkjmgql_database");

/* check connection */ 
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

printf("Host information: %s\n", $mysqli->host_info);

/* close connection */
$mysqli->close();