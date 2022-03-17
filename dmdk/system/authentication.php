<?php
session_start();
include('../probirka_db_functions.php');
$link = probirka_db_connect();
$self=$_SESSION['self'];
$_SESSION['can_edit'] = '1';
$sql = "SELECT * FROM users WHERE login = '".$_POST['user']."'";
$result = mysqli_query($link, $sql);
while($user = mysqli_fetch_assoc($result))
{

	if($_POST['submit'])
	{

		if($user['login'] == $_POST['user'] AND $user['password'] == md5($_POST['pass']))
		{
			$_SESSION['user'] = $user['name'];
			$_SESSION['user_id'] = $user['id'];
			$us=$_SESSION['user_id'];
			if (($us=='7')||($us=='8')||($us=='9')||($us=='1') || ($us=='10') || ($us=='13')) { 
				$_SESSION['can_edit'] = '1';
			}
			elseif (($us=='4')||($us=='5')||($us=='16')) { 
				$_SESSION['can_edit'] = '2';
			}
			else{
				$_SESSION['auth_time'] = time();
			}
			$sql = "INSERT INTO user_history (id, user_id, log_in, log_out) VALUES (NULL, '".$user['id']."', CURRENT_TIMESTAMP, NULL)";
			mysqli_query($link, $sql);
			header("Location: ../$self");
			exit;
		}
		$user['login']='';
		$user['password']='';
	}
}
header("Location: ../index.php?incorrect=1");
?>
