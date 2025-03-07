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
padding: 2px;
text-align: left; /* Выравнивание по левому краю */
}

tbody tr:hover {
background: #00A507; /* Цвет фона при наведении */
color: #fff; /* Цвет текста при наведении */
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
require_once('ImageManipulator.php');

//unset($_SESSION['aut']);
//$prov=$_SESSION['aut'];
//echo 'prov='.$prov.'<br>';
//if ($prov==1) {
$order='name';
if (!$serv) {
$serv=$_SESSION['serv'];
//echo 'serv='.$serv.'<br>';



}

if (!$serv) {
Header("Location: index.php");
//echo 'serv='.$serv.'<br>';
}

$enco=$_SESSION['enco'];
//echo 'enco='.$enco.'<br>';

$log=$_SESSION['log'];
//echo 'log='.$log.'<br>';
$pas=$_SESSION['pas'];
//echo 'pas='.$pas.'<br>';
$tab=$_SESSION['tab'];
//echo 'tab='.$tab.'<br>';
$db=$_SESSION['db'];
//echo 'db='.$db.'<br>';


//echo $log;
	/* МОДУЛЬ ДОБАВЛЕНИЯ ЗАПИСЕЙ В ТАБЛИЦУ MYSQL
			РИСУЕМ ФОРМУ ДЛЯ ДАННОЙ КОНКРЕТНОЙ БД */


//echo '<meta http-equiv="Content-Type" content="text/html; charset=cp1251">';
//$isdouble=$_SESSION['isdouble'];
//echo "</td><td BGCOLOR='#B5B5B5'><a href='../../'><<< На главную</a><br><br><br>";
           // создаем форму для ввода данных




//echo 'user='.$log.'<br>';
//echo 'pas='.$pas.'<br>';	
//echo 'table='.$tab.'<br>';
//echo 'db='.$db.'<br>';


//echo "Вы вошли как <b>$log</b><br><br><b>версии </b>mysql: ".mysql_result(mysql_query('SELECT VERSION()'), 0)."; 
//<a <p style='color:RoyalBlue' href='http://mysqlwith/vers.php'>php: ". phpversion()."</a>
//<br><br>
//$zxc=1;
if ($_GET['treno']) {
$treno= $_GET['treno'];
$_SESSION['treno']=$treno;
}
else {
$treno=$_SESSION['treno'];
}
$pastree= $_SESSION['pastree'];
//echo "pas=".$pastree."<br>";
$conn=mysql_connect("$serv","$log","$pas") or die("Ошибка при подключении");// устанавливаем
             // соединение
			 mysql_query("SET NAMES $enco");


		//	  print($mysqlv);
if ($conn) {
//echo "connok<br>";
}

mysql_select_db($db);
$query="select keyname from names where id=\"$treno\"";
//echo "q=".$query."<br>";
$result=mysql_result((mysql_query($query)), 0);
echo '<a style="color: gray"><b>'.$result.'</b></a>';
$passqu="select pass from names where id=\"$treno\"";
$pass=mysql_result((mysql_query($passqu)), 0);
//echo "treno=".$treno."<br>";	echo "pas=/".$pass."/<br>";
//echo "pastre=/".$pastree."/<br>";
if (($pastree!==$pass)&&($pass)) {
echo "no<br>";
//die(': <a href="enterpas.php">Для просмотра семейного дерева авторизируйтесь</a>');
Header("Location: enterpas.php");
//echo "111111";
}
 // выбираем базу данных

$list_f=mysql_list_fields($db,$tab);
           // получаем список полей в таблице
$n=mysql_num_fields($list_f); // число строк в результате
           // предыдущего запроса (т.е. сколько всего
           // полей в таблице Book)
		  





// для каждого поля получаем его имя, тип, длину и флаги






	
	//------------------
/*	if (!isset($_POST['pass'])) {
echo '<table border=0 style="width: 100%; margin-left: -65px" ><tr><td style="text-align: center"><form method=post><input type=submit class=knopkawide value="Войти в режим редактирования" class="button"><br><input type=password name=pass placeholder="Оставьте пустым" class=user></form></td></tr></table>';
$_SESSION['in']=null;
}

else {
*/



//}
	echo "<br><br><br><br>";

/* МОДУЛЬ ОТОБРАЖЕНИЯ ТАБЛИЦЫ MYSQL
	сначала делаем то же, что и раньше: устанавливаем
соединение, выбираем базу и получаем список и число полей в таблице Book */

$conn=mysql_connect("$serv","$log","$pas") or die("Ошибка при подключении");// устанавливаем
             // соединение
			 mysql_query("SET NAMES $enco");
$db=$_SESSION['db'];
//$tab='people';
mysql_select_db($db); // выбираем базу данных
//$tab= $_SESSION['tab'];
$list_f=mysql_list_fields($db,$tab);
           // получаем список полей в таблице
$n=mysql_num_fields($list_f); // число строк в результате
           // предыдущего запроса (т.е. сколько всего
           // полей в таблице Book)
//echo "n=".$n."<br>";
for($j=0;$j<$n; $j++){

    $names[]=mysql_field_name ($list_f,$j);

}
//echo "order=".$order."<br>";
$sqlshow="SELECT * FROM $tab where treno=$treno  AND (gender = \"m\" OR pair is NULL) order by $order"; // создаем SQL запрос
//echo "sql=".$sqlshow."<br>";
$qshow=mysql_query($sqlshow,$conn) or die(); // отправляем
           // запрос на сервер
$ns=mysql_num_rows($qshow); // получаем число строк результата
//echo "ns=".$ns."<br>";
$ids=mysql_query("select id from $tab where treno=$treno  AND (gender = \"m\" OR pair is NULL) order by $order",$conn) or die(); 
$quanids=mysql_num_rows($ids);
$_SESSION['ids']=$quanids;
$result=mysql_query("SELECT MAX(`id`) FROM $tab");

$findmax=mysql_query("SELECT MAX(`id`) FROM $tab");

$maxx=mysql_result($findmax, 0);
//echo "maxid=".$maxx."<br>";
$_SESSION['maxid']=$maxx;

//echo 'qqq='.$quanids.'<br>';
for ($i=0; $i<$quanids; $i++) {
$valuid=mysql_result($ids,$i,$cuid); 
//echo 'valuid='.$valuid.'<br>';
$va[]=$valuid;
//echo 'table position: '.($i+1).'.  Value: '.$valuid.'<br>';

} //рисуем HTML-таблицу


echo "<table border=0 style=\"margin-top: -85px; margin-left: 210px\" width=70% height=5px><tr> <td class=le><a align=left href=\"index.php\"><b>Изучить другое дерево в лесу</b></a></td> <td> <a href=\"plus.php?treno=$treno\"><b>Добавить родственника</b></a></td></tr></table><br><br><br><br>";
echo "&nbsp;<TABLE BORDER=0 CELLSPACING=5 width=95%
    align=center cellpadding=7 style='margin-top: -100px'>
	
    <tr bgcolor='lightgrey'>";
/* 	foreach ($names as $val){
		if ($val==$key){
		echo "<td >";
		
		
		
		
		echo "</td><th border=1 ALIGN=center BGCOLOR='lightgrey'><font size=3></font></th>";										// имена полей в таблице модуля отображения
		}
		elseif($val=='gender') {
		
		
		}
		
		else{
		if ($val=='story'){
	echo "<th ALIGN=center BGCOLOR='lightgrey' width=15%><font size=3></font></th>";			
		}
			else
			echo "<th ALIGN=center BGCOLOR='lightgrey' ><font size=3></font></th>";										// имена полей в таблице модуля отображения
		}
	}*/
		
		

		
		
		
    // отображаем значения полей
   
    for($i=0;$i<$ns; $i++){ // перебираем все строки в
                // результате запроса на выборку
				$checkname='check'.$i;
        echo "<a href='singleview.php' ><tr bgcolor='#98FB98' >";
        foreach  ($names as $val) { // перебираем все
                // имена полей
        $value=mysql_result($qshow,$i,$val); // получаем
                // значение поля
	if ($val=='id'){
	/*	if ($i<2) {
		echo "<td><input type='checkbox' class=hidden checked name=\"$va[$i]\">";
		} else {*/
		
		if ($_SESSION['in']=='left') {
/*		echo "<td>
		<input type='checkbox' class=check name=\"$va[$i]\">
		</td>";*/
		} 
		else echo '';
		
		//}
		//echo $va[$i];
		echo "";
	}
	
	elseif ($val=='treno'){
	}

	
	elseif ($val=='photo'){
	$value=mysql_result($qshow,$i,'name');
	$image=mysql_result($qshow,$i,'photo');
	
	echo "<td align=center style=\"cursor: pointer;\"><img src=\"smallimages/$image\" onclick=\"viewsingle($va[$i])\" title=\"$value\" height=100px>";	
	}
	
	elseif ($val=='pair'){
	$qupana="select name from $tab where id=$value";
	$qupafo="select photo from $tab where id=$value";
	$pafot=mysql_result((mysql_query($qupafo)), 0);
	$pana=mysql_result((mysql_query($qupana)), 0);
	$qutesh="select mother from people where id=\"$value\"";
	$tesh=mysql_result((mysql_query($qutesh)), 0);
	$qutest="select father from people where id=\"$value\"";
	$test=mysql_result((mysql_query($qutest)), 0);
	if (!$pana) {
	$pana='';
	}
	//echo "<td align=center><font size=3 >$result</font></td>";
	if ($pafot) {
	echo "<img src='smallimages/$pafot' onclick=\"viewsingle($value)\" title=\"супруга: $pana\" height=100px></td>";
	} 
	else {
	echo "";
	}
	}
	
	elseif ($val=='name'){
	echo "<td style=\"cursor: pointer;\" onclick=\"viewsingle($va[$i])\" align=center><font size=3 >&nbsp;$value<br>$pana</font></td>";	
	}
	
	
	
	elseif ($val=='gender')
	{
	
	
/*	if ($value=='m'){
		echo '<td><img src="images/m.png" width="20px"></td>';		
	}
		if ($value=='f'){
		echo '<td><img src="images/f.png" width="20px"></td>';		
	} */
	}
					
	elseif ($val=='birth'){
			
			
	$bir=mysql_result($qshow,$i,'birth');
	$birtoview= date("d.m.Y", strtotime($bir));
		if (($birtoview=='01.01.1970')||($birtoview=='30.11.-0001'))
		{$birtoview=null; }
		$ifyearonly=substr($birtoview, 0, 5);
	if ($ifyearonly=='01.01') {
	$yearonly=substr($birtoview, 6);
	$birtoview=$yearonly;
	}		
	
	echo "<td width=150px style=\"cursor: pointer;\" onclick=\"viewsingle($va[$i])\" align=center ";
		if ($birtoview) {
		echo "
	title=\"Дата рождения\">";
	}
	else {
	echo "title=\"Дата рождения неизвестна\"><a style=\"font-size: 8pt; font-style: italic; color: gray\">ДР не заполнен...</a>";
	}
	echo "$birtoview</td>";	
	}					
	
	elseif ($val=='death'){
			
	$dea=mysql_result($qshow,$i,'death');
		
	$deatoview= date("d.m.Y", strtotime($dea));
		if (($deatoview=='30.11.-0001')||($deatoview=='01.01.1970'))
		{$deatoview=null;}
				$ifyearonly=substr($deatoview, 0, 5);
	if ($ifyearonly=='01.01') {
	$yearonly=substr($deatoview, 6);
	$deatoview=$yearonly;
	}		
	
	echo "<td style=\"cursor: pointer;\" width=150px onclick=\"viewsingle($va[$i])\" align=center";
if ($deatoview) {echo "
	title=\"Дата смерти\"";
	}
	
	echo ">$deatoview</td>";	
	
	}
					
	elseif ($val=='mother')
	{
	$query="select name from $tab where id=$value";
	$queryfot="select photo from $tab where id=$value";
	$resultfot=mysql_result((mysql_query($queryfot)), 0);
	$result=mysql_result((mysql_query($query)), 0);
	$teshnamequ="select name from people where id=\"$tesh\"";
	$teshname=mysql_result((mysql_query($teshnamequ)), 0);
	$teshphotqu="select photo from people where id=\"$tesh\"";
	$teshphot=mysql_result((mysql_query($teshphotqu)), 0);
	if (!$result) {
	$result='неизв.мама';
	}
	//echo "<td align=center><font size=3 >$result</font></td>";
	if ($resultfot) {
	echo "<td style=\"cursor: pointer;\"   align=center><img src='smallimages/$resultfot' onclick=\"viewsingle($value)\" title=\"мама: $result\" height=70px><br>";
	if ($teshname) {
	echo "<img src='smallimages/$teshphot' onclick=\"viewsingle($tesh)\" title=\"теща: $teshname\" height=70px></td>";
	}
	} 
	else {
	echo "<td align=center><img src=\"images/001.jpg\" width=\"60px\" title=\"неизв.мама\"></td>";
	}
	}
	elseif ($val=='father')
	{
	$query="select name from $tab where id=$value";
	$queryfot="select photo from $tab where id=$value";
	$resultfot=mysql_result((mysql_query($queryfot)), 0);
	$result=mysql_result((mysql_query($query)), 0);
	$testnamequ="select name from people where id=\"$test\"";
	$testname=mysql_result((mysql_query($testnamequ)), 0);
	$testphotqu="select photo from people where id=\"$test\"";
	$testphot=mysql_result((mysql_query($testphotqu)), 0);
	if (!$result) {
	$result='неизв.папа';
	}
	//echo "<td align=center><font size=3 >$result</font></td>";	
	if ($resultfot) {
	echo "<td style=\"cursor: pointer;\" align=center><img src='smallimages/$resultfot' onclick=\"viewsingle($value)\" title=\"папа: $result\" height=70px>";
	if ($testname) {
	echo "<br><img src='smallimages/$testphot' onclick=\"viewsingle($test)\" title=\"тесть: $testname\" height=70px></td>";
	} 
	}
	else {
	echo "<td align=center><img src=\"images/000.jpg\" width=\"60px\" title=\"неизв.папа\"></td>";
	}
	}
	
	elseif ($val=='story') {
	$querysto="select story from $tab where id=$va[$i]";
//	echo '1='.$querysto.'<br>';
	$resultsto=mysql_result((mysql_query($querysto)), 0);
	$longchars=iconv_strlen ($resultsto);
	//echo 'allchq='.$longchars.'<br>';
	//internal_encoding("UTF-8");
	//$shortsto=mb_substr($resultsto, 0, 10);
	$shortsto=substr($resultsto, 0, 10);
	$shortchars=iconv_strlen ($shortsto);
	//echo 'chq='.$shortchars.'<br>';
		//else {$shortsto='';}
	//echo "<td align=center><font size=3 >$result</font></td>";	
	if ($shortsto) {
	if ($longchars>$shortchars) {
	echo "<td style=\"cursor: pointer;\" title=\"$resultsto\" onclick=\"viewsingle($va[$i])\" align=center>$shortsto...</td>";
	}
	else {
	echo "<td style=\"cursor: pointer;\"  title=\"$resultsto\" onclick=\"viewsingle($va[$i])\" align=center>$resultsto</td>";
	}
	} 
	else {
	echo "<td style=\"cursor: pointer;\" width=200px title=\"рассказ о человеке отсутствует\" onclick=\"viewsingle($va[$i])\" align=center></td>";
	}
	}
	
	else{
	if ($value=='0000-00-00') {
	$value=null;
	}
		echo "<td align=center><font size=3>&nbsp;$value</font></td>";										// имена полей в таблице модуля отображения
	}

                // выводим значение поля
        }
    echo "</tr></a>";
    }
echo "</table><br>";

$ro=$_GET['treno'];

		if (!isset($_POST['passubmit'])) {
		
		echo "<table cellpadding=\"30\" 
border=\"0\" cellspacing=\"0\" width=100% border=0>
<tr>
<td>
<!--<form method=post action=\"plus.php?treno=$ro\"><input type=submit name=passubmit class=knopka value=\"Добавить родственника\"></form>-->
</td>
</tr>
</table> <br><br>";
		
		}


		
		
		

		
		

		
	
	echo '<br><br><br><br>';
	

	

 echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
 

//echo '<p style="color:blue" href="/tablespecialcell.php">Cделать pdf </p>';
//echo "<a <p style="color:blue" href='/tcpdfstudy.php'><b>TCPDF</b></a>";
//echo "<a <p style='color:blue' href='http://mysqlwith/pdfout.php'><b>Сделать PDF</b></a>	";



//else {
//Header("Location: authorize.php");
//}
 
	
	





?>