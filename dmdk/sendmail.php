<?php
$to = 'dialeks@gmail.com';
$subject = 'subject';
$msg = 'Test text';
$headers = "From: dialeks@gmail.com"."\r\n"."CC: dialeks@minfin.gov.ua";
mail($to, $subject, $msg, $headers);
header('Location: main.php');