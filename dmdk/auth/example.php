<?php
if (version_compare(phpversion(), '5.4.0', '<')) {
    if(session_id() == '') {
        session_start();
    }
}else{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

require_once('auth_uni.inc');
// обязательная авторизация
$retArray=Array();
if(auth_uni_login($sv['ESA']['SOTRS']['client_id'],$sv['ESA']['SOTRS']['secret'], $_SESSION['sv']['token'], $retArray)=='') {
echo "OK<br>";
	// авторизрвались	    
}else{
    unset($_SESSION['sv']['token']);
    die('Ошибка авторизации !');
}
$sid=$sv['token']; // сессия проминя
?>