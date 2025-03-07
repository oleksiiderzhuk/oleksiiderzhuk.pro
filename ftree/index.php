<?php
//$prov=1;
session_start();
error_reporting(0);
//session_unset($_SESSION['tab']);
?>
<html>
<head>
	<link rel="shortcut icon" href="ftree.ico" type="image/x-icon">
<style type="text/css">
   body {
   font-family: arial;
   
   }
   
   
font-size: 10pt;
   }
   TH {
    background: '#006400'; /* Цвет фона заголовка */
    color: black; /* Цвет текста */
	font-size: 10pt;
   }
   TD, TH {
    padding: 1 3px; /* Поля вокруг текста */
    text-align: center; /* Выравнивание по центру */
	vertical-align: middle;
	height: 20px;
	overflow: hidden;
   }
   TD.le {
    padding: 12px;
	text-align: left; /* Выравнивание по левому краю */
	  }
	  

	
	.hidden {
	visibility: hidden;
	}
	
	
	.user {
	width: 150px; /* Ширина поля с учетом padding */
    height: 48px; /* Высота */
    background-color: white ;<!--#dad7c5 url(images/input.png) no-repeat -->/* Фон */
    padding: 0 10px; /* Поля */
    border: none; /* Убираем рамку */
    font-size: 10pt; /* Размер текста */
	text-align: center;
	vertical-align: bottom;
	}

	
	.check {
	width: 30px;
	height: 30px;
	}

	select {
text-align: center;
vertical-align: middle;
}
	
.knopka {
  color: black; /* цвет текста */
  text-decoration: bold; /* убирать подчёркивание у ссылок */
  user-select: none; /* убирать выделение текста */
  background: #F8F8F8; /* фон кнопки */
  padding: 1em 1em; /* отступ от текста */
  outline: none; /* убирать контур в Mozilla */
} 
.knopka:hover { background: #FFFFCC; } /* при наведении курсора мышки */
.knopka:active { background: gray; } /* при нажатии */

.knopk {
  color: black; /* цвет текста */
  text-decoration: bold; /* убирать подчёркивание у ссылок */
  user-select: none; /* убирать выделение текста */
  background: #F8F8F8; /* фон кнопки */
  padding: 0 0; /* отступ от текста */
  outline: none; /* убирать контур в Mozilla */
} 
.knopk:hover { background: #FFFFCC; } /* при наведении курсора мышки */
.knopk:active { background: gray; } /* при нажатии */


	
  </style>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  </head>
  <body>

<table bgcolor="antiquewhite" cellpadding="30" 
border="1" cellspacing="0" bordercolor="white" 
width=100% height=100% border=0>
<tr>
<td align=center>

<label for="subject">  </label><br>

<br><br><br>
<form method="post" action="authorize.php"><input name="fakey" class=user type="text" size="30" placeholder="Имя или фамилия">&nbsp;&nbsp;&nbsp;<input class=knopka type="submit" name="usesub" value="Найти родовое дерево"></form>


</td>
</tr>
<tr><td>
<a href="/testaut.php" target="_blank">Изучить демонстрационное родовое дерево</a>
</td></tr>
</table>
<?php  
session_start();
error_reporting(0); 
unset($_SESSION['pastree']);
unset($_SESSION['serv']);

$_SESSION['serv']='mysql1.000webhost.com';
$_SESSION['db']='a3552103_ftree';
$_SESSION['enco']='utf8';
$_SESSION['log']='a3552103_root';
$_SESSION['pas']='Qq1221';
$_SESSION['tab']='people';

?>

 