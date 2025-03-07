<html><head>
<meta charset="utf-8">
<link rel="shortcut icon" href="ftree.ico" type="image/x-icon">
<script src="calendar_kdg.js" type="text/javascript"></script>
<script type="text/javascript">


function viewsingle(record)
{
var viewsingle=encodeURIComponent(record);
window.location.href='singleview.php?record='+record;
}

function viewadd(treno)
{
var viewadd=encodeURIComponent(treno);
window.location.href='plus.php?treno='+treno;
}
</script>

<style type="text/css">
body {
font-family: arial;
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
width: 200px; /* Ширина поля с учетом padding */
height: 30px; /* Высота */
background-color: white ;<!--#dad7c5 url(images/input.png) no-repeat -->/* Фон */
padding: 0 10px; /* Поля */
border: none; /* Убираем рамку */
font-size: 10pt; /* Размер текста */
text-align: center;
vertical-align: bottom;
}
	
.userpas {
width: 120px; /* Ширина поля с учетом padding */
height: 30px; /* Высота */
vertical-align: bottom;
text-align: center;
}
	
.username {
width: 200px; /* Ширина поля с учетом padding */
height: 30px; /* Высота */
background-color: white ;<!--#dad7c5 url(images/input.png) no-repeat -->/* Фон */
padding: 0 10px; /* Поля */
border: none; /* Убираем рамку */
font-size: 10pt; /* Размер текста */
text-align: center;
vertical-align: bottom;
}
	
.userdate {
width: 150px; /* Ширина поля с учетом padding */
height: 30px; /* Высота */
background-color: white ;<!--#dad7c5 url(images/input.png) no-repeat -->/* Фон */
padding: 0 10px; /* Поля */
border: none; /* Убираем рамку */
font-size: 10pt; /* Размер текста */
text-align: center;
vertical-align: middle;
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
padding: 0.5em 1.5em; /* отступ от текста */
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

.fileUpload {
position: relative;
overflow: hidden;
margin: 10px;
font-weight: bold;
}

.fileUpload input.upload {
position: absolute;
top: 0;
right: 0;
margin: 0;
padding: 0;
font-size: 10pt;
cursor: pointer;
opacity: 0;
filter: alpha(opacity=0);
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body bgcolor="lightgrey">

<?php 
session_start();
error_reporting(0);
 ?>
<table width=100% height=90%><tr><td>
<form method="post" action="enterpas.php">

<br>
<input class=user placeholder="Ввведите пароль" name="pass" type="password" size="30">&nbsp;&nbsp;&nbsp;
<input class=knopka type="submit" name="submit" value=">>>">

</form>
</td></tr></table>

<?php 
$_SESSION['pastree']= $_POST['pass'];
if ( $_SESSION['pastree'] ) {
Header("Location: ftree.php");
}

 ?>