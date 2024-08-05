<?php function areas_db_connect()

{

$link = mysqli_connect("localhost", "root", "password", "local_database");
//  $link = mysqli_connect("localhost", "jbkjmgql_root", "Password4root", "jbkjmgql_database");


    $link->set_charset("utf8");

	if (mysqli_connect_errno())

	{

		echo "Ошибка в подключении";

		exit();

	}



	return $link;

}



$height_floors = 7;

?>