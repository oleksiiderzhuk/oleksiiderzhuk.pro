<?php 

session_start();
error_reporting(0); ?>

<html><head>
<link rel="shortcut icon" href="ftree.ico" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="calendar_kdg.js" type="text/javascript"></script>
<script>
function img() {
  var img = document.createElement('img');
  img.setAttribute('src', 'адрес_картинки');
  document.body.appendChild(img);
}

</script>

<script type="text/javascript">

function changeCheck(el)
/* 
	функция смены вида и значения чекбокса
	el - span контейнер дял обычного чекбокса
	input - чекбокс
*/
{
     var el = el,
          input = el.getElementsByTagName("input")[0];
		
     if(input.checked)
     {
	     el.style.backgroundPosition="0 0"; 
		 input.checked=false;
     }
     else
     {
          el.style.backgroundPosition="0 28px"; 
		  input.checked=true;
     }
     return true;
}
function startChangeCheck(el)
/*
	если значение установлено в on, меняем вид чекбокса на включенный
*/
{
	var el = el,
          input = el.getElementsByTagName("input")[0];
     if(input.checked)
     {
          el.style.backgroundPosition="0 -17px";     
      }
     return true;
}

function startCheck()
{
	/*
		 при загрузке страницы заменяем проверяем значение чекбокса в указанном контенере.
		 если чекбоксов несколько, нужно будет несколько раз вызвать функциую с нужными id
	 */
	startChangeCheck(document.getElementById("niceCheckbox1"));
}

window.onload=startCheck;

</script>


<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.checkbox.js"></script>


<style type="text/css">
   body {
   font-family: arial;
   
   }
   
	TABLE {
    background: Gainsboro ; /* Цвет фона таблицы */
	font-size: 12pt;
	
   }
   
    TH {
    background: '#006400'; /* Цвет фона заголовка */
    color: black; /* Цвет текста */
	font-size: 6pt;
   }
   TD, TH {
    padding:10px;
    text-align: center; /* Выравнивание по центру */
	vertical-align: middle;
	cellpadding: 20px;
	
	
	
   }
   TD.le {
	text-align: left; /* Выравнивание по левому краю */
	padding:0px;
	
	  }
	TD.letop {
	text-align: left; /* Выравнивание по левому краю */
	padding:0px;
	vertical-align: top;
	  }
	  
	.hidden {
	visibility: visible;
	margin-top: 55;
	margin-left: -500 ;
	}
	
	textarea {
	width: 200px;
	}
	
	.user {
	width: 100px; /* Ширина поля с учетом padding */
    height: 45px; /* Высота */
    background-color: white ;<!--#dad7c5 url(images/input.png) no-repeat -->/* Фон */
    padding: 0 10px; /* Поля */
    border: none; /* Убираем рамку */
    font-size: 1em; /* Размер текста */
	text-align: center;
	vertical-align: top;
	}
	
	.username {
	width: 150px; /* Ширина поля с учетом padding */
    height: 45px; /* Высота */
	background-color: white ;<!--#dad7c5 url(images/input.png) no-repeat -->/* Фон */
    padding: 0 10px; /* Поля */
    border: none; /* Убираем рамку */
    font-size: 1em; /* Размер текста */
	text-align: center;
	vertical-align: bottom;
	}
	
	.userdate {
	width: 100px; /* Ширина поля с учетом padding */
    height: 50px; /* Высота */
    background-color: white ;<!--#dad7c5 url(images/input.png) no-repeat -->/* Фон */
    padding: 0 10px; /* Поля */
    border: none; /* Убираем рамку */
    font-size: 1em; /* Размер текста */
	text-align: center;
	vertical-align: middle;
	}
	
	.check {
	width: 30px;
	height: 30px;
	}

.knopka {
  color: black; /* цвет текста */
  text-decoration: bold; /* убирать подчёркивание у ссылок */
  user-select: none; /* убирать выделение текста */
  background: #F8F8F8; /* фон кнопки */
  padding: .5em 2em; /* отступ от текста */
  outline: none; /* убирать контур в Mozilla */
} 
.knopka:hover { background: #FFFFCC; } /* при наведении курсора мышки */
.knopka:active { background: gray; } /* при нажатии */



.del {
width: 25px;
height: 30px;
display: inline-block;
cursor: pointer;
background: url(del.png);
}
.del input {
display: none;
}
		
  
	
	
  </style>
  
 <SCRIPT language=javascript>  
  function openpict(pict) 
{ 
window.open(pict,"none","top=100, left=100, width=100, height=100, resizable=0","replace=yes") 
} 
</script>
</head>
<body bgcolor="gainsboro">


<?php



$serv=$_SESSION['serv'];
	if (!$serv) {
	echo '<script type="text/javascript">'; 
	echo 'window.location.href="index.php";'; 
	echo '</script>';}

//else {echo '112';	}


$enco=$_SESSION['enco'];
//echo 'enco='.$enco.'<br>';
$log=$_SESSION['log'];
$pas=$_SESSION['pas'];
$tab=$_SESSION['tab'];
$database=$_SESSION['db'];
$treno= $_SESSION['treno'];
$record=$_GET["record"];
$_SESSION['record']=$record;
$_SESSION['checked']=1;
//echo 'record='.$record.'<br>';


//echo 'checked='.$checkedquan.'<br>';


//echo 'edid='.$toed.'<br>';



$conn=mysql_connect("$serv","$log","$pas") or die("Ошибка при подключении");// устанавливаем
             // соединение
			
			 mysql_query("SET NAMES $enco");
//if ($conn) echo 'connection successfull<br>';			 
mysql_select_db($database);
$db = $_SESSION['db'];
//$tab = 'people';
mysql_select_db($db); // выбираем базу данных
$pastree= $_SESSION['pastree'];
if ($pastree=='') {
//$pastree='111';
}
$passqu="select pass from names where id=\"$treno\"";
$pass=mysql_result((mysql_query($passqu)), 0);
//echo "treno=".$treno."<br>";
//echo "pas=".$pass."<br>";
//echo "inses=".$pastree."<br>";
//echo "indb=".$pass."<br>";
if ($pass) { 
//echo "пароль есть<br>";
}
if (($pastree!==$pass)&&($pass)) {
die('<br><a href="enterpas.php">пройдите авторизацию');
	}
$list_f = mysql_list_fields($db,$tab);
           // получаем список полей в таблице
$n = mysql_num_fields($list_f); // число строк в результате
           // предыдущего запроса (т.е. сколько всего
           // полей в таблице Book)





for($i=0;$i<$n; $i++){
    $type = mysql_field_type($list_f, $i);
	$name_f = mysql_field_name ($list_f,$i);
    $len = mysql_field_len($list_f, $i);
	$flags_str = mysql_field_flags ($list_f, $i);
    // из строки флагов делаем массив,
    // где каждый элемент массива - флаг поля
    $flags = explode(" ", $flags_str);
  
	foreach ($flags as $f){
        
		
	if ($f == 'auto_increment') {
			$key = $name_f;
			$keypos=$i+1;
		}
	}}
		   
		   
for($j=0;$j<$n; $j++){

    $names[] = mysql_field_name ($list_f,$j);

}
/*
echo '<form method="post" action="editing.php" enctype=multipart/form-data>
<br><table><tr><td><a href="ftree.php" > <<< Назад&nbsp</a></td><td></td></tr></table><div align=center><input type=submit class="knopka" name="ed" value="Сохранить изменения"></div>';*/
echo "<br><table width=60% border=0><tr><td><a onclick=\"javascript:history.back();\" style=\"text-decoration: underline; cursor: pointer; color: blue;\" > <b><<< Назад</b></a></td><td><a href=\"ftree.php?treno=$treno\" ><b> Изучить всё дерево </b></a></td><td><a align=left href=\"index.php\"><b>Изучить другое дерево в лесу</b></a></td></tr></table>
<table cellpadding=\"30\" 
border=\"0\" cellspacing=\"5\" 
width=95% height=35% align=center>
<tr>
<td colspan=8>
&nbsp;<TABLE BORDER=0 CELLSPACING=5 width=95%
    align=center cellpadding=25px>";
	
	
	if (!isset($_POST['edi'])) {
		
		echo '<table cellpadding="30" 
border="0" cellspacing="0" width=100% border=0>
<tr>
<td><form method=post><input type=submit name=edi class=knopka value="Редактировать"></form></td>
</tr>
</table> ';
		
		}
		else {
			if (isset($_POST['edi'])) {
			$_SESSION['edi']='edi';
			}
			if (!isset($_POST['edi'])) {
			echo "<table cellpadding=\"30\" 
border=\"0\" cellspacing=\"0\" width=100% border=0>
<tr>
<td><form method=POST action='editing.php'><input type=submit class=knopka name='edi' value='Редактировать'></form>
			<!--
			<form method=POST action='editing.php'><input type=password class=userpas placeholder='Откройте замОк ->' style='margin-left:10px;'> &nbsp; <input type=\"image\" src=\"ent.png\" alt=\"Submit Form\" name=\"pass\"  class=knopk width=\"30px\"></form>
			-->
			
			</td>
</tr>
</table> <br>";
			}
			else {
			echo '<script type="text/javascript">'; 
	echo 'window.location.href="editing.php";'; 
	echo '</script>';
			}
			}
	
	
	
	
	
	
	
	
 
 /*	foreach ($names as $val){
		if ($val==$key){
			echo "<th style='font-size: 12pt'>X</th><th border=1 ALIGN=center BGCOLOR='#528B8B'><font size=5>$val</font></th>";										// имена полей в таблице модуля отображения
		}
		
		else{
		echo "<th ALIGN=center BGCOLOR='#2E8B57' ><font size=5>$val</font></th>";										// имена полей в таблице модуля отображения
		}
	}

    // отображаем значения полей
    echo "</tr>";*/
//echo 'tab='.$tab.'<br>';	
//echo 'te='.$checked[$j].'<br>';
//echo "rec: ".$record."<br>";
$sqlshow="select * from $tab where id=$record";
//echo 'sqlshow='.$sqlshow.'<br>';
$qshow = mysql_query($sqlshow) or die(); // отправляем
//echo '<br>'.$sqlshow.'<br>';
echo "<tr bgcolor='#98FB98'>";
        foreach  ($names as $val) { // перебираем все
                // имена полей
				//echo 'jj='.$\checked[$j].'<br>';
				
        $value = mysql_result($qshow,0,$val); // получаем
                // значение поля
	
	if ($val==$key){
	echo "";
	}

	elseif ($val=='treno'){ 
	}
	
	
	elseif ($val=='photo')
	{
	$queryfo="select photo from $tab where id=$record";
	$recoo=$record;
//echo 'quefo='.$queryfo.'<br>';
	$resultfo=mysql_result((mysql_query($queryfo)), 0);
	//echo '<br><br><br>resfot='.$query.'<br><br>'; echo $resultfo;	
	echo	'<td align=center>';
$query="SELECT name FROM $tab WHERE id=$record";
//echo 'query='.$query.'<br>';
$sqlqu=mysql_query($query);
$person=mysql_result($sqlqu, 0); 
$resfo[]=$resultfo;
if (is_file("smallimages/".$resultfo)) {
echo "<a href=\"images/$resultfo\" target=\"_blank\">";
}
echo "<img src=\"smallimages/$resultfo\" title=\"$person\"  width=200px></a>";
/*	echo "<label class=\"filebutton\">
<img src=\"images/$resultfo\" title=\"$resultfo\" style = \"cursor: pointer;\" width=200px>
<span><input type=\"file\" id=\"myfile\" name=\"myfile\"></span>
</label>";*/

//	echo '</td>';
	}
	
			elseif ($val=='pair')
	{
//	echo "</tr><tr>";
	$querypair="select pair from $tab where id=$recoo";
//echo 'quepair='.$querypair.'<br>';
	$resultpair=mysql_result((mysql_query($querypair)), 0);
	
	//--------
	$sql = "SELECT *
        FROM   people
        WHERE  id=$resultpair";

$respair = mysql_query($sql);

if (!$respair) {
    //echo "Could not successfully run query ($sql) from DB: " . mysql_error();
  //  exit;
}

if (mysql_num_rows($respair) == 0) {
    //echo "Не замужем";
    //exit;
}

// До тех пор, пока в результате содержатся ряды, помещаем их в ассоциативный массив.
// Замечание: если запрос возвращает только один ряд - нет нужды в цикле.
// Замечание: если вы добавите extract($row); в начало цикла, вы сделаете
//            доступными переменные $userid, $fullname и $userstatus
while ($row = mysql_fetch_assoc($respair)) {
 //   echo '<td>'.$row["photo"] .'</td>';
	
	if (is_file("smallimages/".$row["photo"])) {
	$phh=$row['photo'];
	$nnn=$row['name'];
	$idpair=$row['id'];
echo "<a href=\"/singleview.php?record=$idpair\">";

echo "<img src=\"smallimages/$phh\" title=\"";
if ($gennn=='m') {
echo "супруг:";
}
else {
echo "супруга:";
}
echo " $nnn\"  width=110px style=\"margin-top: 50px\"></a></td>";

}

/*	echo "<label class=\"filebutton\">
<img src=\"images/$resultfo\" title=\"$resultfo\" style = \"cursor: pointer;\" width=200px>
<span><input type=\"file\" id=\"myfile\" name=\"myfile\"></span>
</label>";*/

//	echo '</td>';
/*	echo '<td>'.$row["name"].'</td>';

	echo '<td>';
	
	if ($row["gender"]=='m') {
	echo "<img src=images/m.png height=50px title='муж.'>";
	$genn='m';
}
if ($row["gender"]=='f') { 	
echo "<img src=images/f.png height=50px title='жен.'>";
$genn='f';
}

if ($row["gender"]=='?') { 	
echo "?";
$genn='?';
}
	
	echo '</td>';
		echo "<td title=\"день рождения\">";
	echo $row["birth"];
	echo "</td>";
	
	echo "<td >";
	echo $row["death"];
	echo "</td>";	
	
	echo "<td>";
	
	$querypairmother="select * from $tab where id=$row[mother]";
	
	$respairmother = mysql_query($querypairmother);

	while ($rowpair = mysql_fetch_assoc($respairmother)) {
	echo "<a href=\"/singleview.php?record=$rowpair[id]\"><img src='smallimages/$rowpair[photo]' title=\"$rowpair[name]\" width=60px></a><br>";
	
	echo '<br>'.$rowpair["name"];
	}
	//echo $row["mother"];
	echo "</td>";	
	
	echo "<td >";
	$querypairfather="select * from $tab where id=$row[father]";
	
	$respairfather = mysql_query($querypairfather);

	while ($rowpairfa = mysql_fetch_assoc($respairfather)) {
	echo "<a href=\"/singleview.php?record=$rowpairfa[id]\"><img src='smallimages/$rowpairfa[photo]' title=\"$rowpairfa[name]\" width=60px></a><br>";
	
	echo '<br>'.$rowpairfa["name"];
	}
	echo "</td>";	
	
	echo "<td >";
	echo $row["story"];
	echo "</td>";
	*/
	
	}
	

	

    
	
	
	///-----------------
	//echo '<br><br><br>resfot='.$query.'<br><br>'; echo $resultfo;	
	
	}
	
	elseif ($val=='folder'){}
	
	elseif ($val=='name')
	{
	
	$query="select name from $tab where id=$record";
	



	$result=mysql_result((mysql_query($query)), 0);
	//echo '...='.$result.'<br>';
	if ($result) {
	echo "<td>$result</td>";
	} 
	else {
	echo "<td align=center></td>";
	}
	}
	
	elseif ($val=='gender') {

	$query="select gender from $tab where id=$record";

	$result=mysql_result((mysql_query($query)), 0);
	//echo '...='.$result.'<br>';
	if ($result) {
	echo '<td>';	
	if ($result=='m') {
	$gennn=m;
	echo "<img src=images/m.png height=50px title='муж.'>";
	$genn='m';
}
if ($result=='f') { 
$gennn='f';	
echo "<img src=images/f.png height=50px title='жен.'>";
$genn='f';
}
if ($result=='?') { 
	echo "<b>?</b>";
	}
echo "</td>";
}	
	else {
	echo "<td align=center>error...</td>";
	}
	}


elseif ($val=='birth') {	
	$query="select birth from $tab where id=$record";

	$result=mysql_result((mysql_query($query)), 0);
	//echo '...='.$result.'<br>';
	echo '<td title="день рождения">';
	
	

	if ($result) {
	$yb=date("Y", strtotime($result));
	$resultnorm = date("d.m.Y", strtotime($result));
	if (($resultnorm=='01.01.1970')||($resultnorm=='30.11.-0001')) {
	$resultnorm=null;
	echo '<a style="font-size: 10pt; margin-top: 10px"><i>ДР не заполнен...</i></a>';
	}
	else {
	$ifyearonly=substr($resultnorm, 0, 5);
	if ($ifyearonly=='01.01') {
	$yearonly=substr($resultnorm, 6);
	$resultnorm=$yearonly;
	}
	//echo "$ifyearonly<br>";
	echo $resultnorm;
	}
	}
	else {
	echo "<td></td>";
	}
	echo '</td>';
	}
	
	elseif ($val=='death') {	
	$query="select death from $tab where id=$record";

	$result=mysql_result((mysql_query($query)), 0);
//echo '...='.$result.'<br>';
	
	if ($result) {
	$resultnorm = date("d.m.Y", strtotime($result));
	if (($resultnorm=='01.01.1970')||($resultnorm=='30.11.-0001')) {
	$resultnorm=null;
	}
	else {
		$ifyearonly=substr($resultnorm, 0, 5);
	if ($ifyearonly=='01.01') {
	$yearonly=substr($resultnorm, 6);
	$resultnorm=$yearonly;
	}		
echo '<td>'.$resultnorm;}
	}
	else {
	//echo	"01.01.2000";
	}
	}
	
	elseif ($val=='mother')
	{
	$query="select name from $tab where id=$value";
	$queryfot="select photo from $tab where id=$value";
	$resultfot=mysql_result((mysql_query($queryfot)), 0);
	$result=mysql_result((mysql_query($query)), 0);
		$query="SELECT NAME FROM $tab WHERE id=(SELECT mother FROM $tab WHERE id=$record)";
$resnamo=mysql_result((mysql_query($query)), 0);
$idmoqu="select id from $tab where name='$resnamo'";
//echo 'idmoqu='.$idmoqu.'<br>';
$idmo=mysql_result((mysql_query($idmoqu)), 0);
if ($idmo=='1') {
$idmo=null;
}
	
//echo 'мамаid: '.$idmo.'<br>';

	//echo "<td align=center><font size=5 >$result</font></td>";
	echo '<td><b>мама</b><br><br>';
	if ($result) {
	if ($idmo==null) {
		echo "<a href=\"/singleview.php?record=1\"><img src='smallimages/$resultfot' title=\"$result\" width=150px></a>";
	}
	else {
	echo "<a href=\"/singleview.php?record=$idmo\"><img src='smallimages/$resultfot' title=\"$result\" width=150px></a>";
	}
	} 
	else {
	echo "<img src=images/001.jpg width=100px title=\"неизв.мама\">";
	}
	$query="select name from $tab where id=$value";
	$result=mysql_result((mysql_query($query)), 0);
	$res = mysql_query('select name from $tab where gender="f"');
				$nw = mysql_num_rows($res);
				for($wcyc=0;$wcyc<$nw; $wcyc++){
					$momsid = mysql_result($res, $wcyc);
					if ($momsid==$result) {
					$momlist.="<option selected=\"selected\">$momsid</option>";
						}
					else {
					$momlist.="<option>$momsid</option>";
					}
				}
				echo '<br><br>'.$result;
						if(!empty($_POST["mother"]))  {
		$maval=$_POST["mother"];
	echo '</td>';
	}}
	elseif ($val=='father')
	{
	$query="select name from $tab where id=$value";
	$queryfot="select photo from $tab where id=$value";
	$resultfot=mysql_result((mysql_query($queryfot)), 0);
	$result=mysql_result((mysql_query($query)), 0);
		
$query="SELECT NAME FROM $tab WHERE id=(SELECT father FROM $tab WHERE id=$record)";
$resnafa=mysql_result((mysql_query($query)), 0);
$idfaqu="select id from $tab where name='$resnafa'";
//echo 'idmoqu='.$idmoqu.'<br>';
$idfa=mysql_result((mysql_query($idfaqu)), 0);
if ($idfa=='2') {
$idfa=null;
}
	//echo "<td align=center><font size=5 >$result</font></td>";	
	echo '<td><b>папа</b><br><br>';
	if ($result) {
	if ($idfa==null) {
	echo "<a href=\"/singleview.php?record=2\"><img src='smallimages/$resultfot' title=\"$result\" width=150px></a>";
	}
	else
	{
	echo "<a href=\"/singleview.php?record=$idfa\"><img src='smallimages/$resultfot' title=\"$result\" width=150px></a>";
	} 
	}
	else {
	echo "<img src=images/000.jpg width=150px title=\"неизв.папа\">";
	}
	$res = mysql_query("select name from $tab where gender='m'");
				$nw = mysql_num_rows($res);
				
							
				
				for($mcyc=0;$mcyc<$nw; $mcyc++){
					$papsid = mysql_result($res, $mcyc);
					
					if ($papsid==$result) {
					$paplist.="<option selected=\"selected\" >$papsid</option>";
					}
					else {
					$paplist.="<option>$papsid</option>";
					}
					
				}
							
				echo '<br><br>'.$result;
			if(!empty($_POST['father']))  {$paval=$_POST['father']; }
	echo '</td>';
	}
	
	
		elseif ($val=='story')
	{
	$query="select story from $tab where id=$record";
//	echo 'query='.$query.'<br>';
	$result=mysql_result((mysql_query($query)), 0);
	//echo "<td align=center><font size=5 >$result</font></td>";	
	echo '<td width=30%>';
	if ($conn) { 
	//echo 'connOK<br>';
	} else echo 'no connect<br>';
	if ($result) {
	$namsto=$j.'sto';
	echo $result;
	} 
	else {
	echo "no story...";
	}
	}

	else{
	if (($result=='0000-00-00')|($result=='0001-11-30')) {
	$result=null;
	}
		echo "<td align=center><font size=5 >&nbsp;$value</font></td>";										// имена полей в таблице модуля отображения
	}

                // выводим значение поля
        }
		


echo '</td>
</tr> 
</table>';


//echo 'mom='.$residmom.'<br>';
echo ' <br><table border="0" cellspacing="0" bordercolor="white" style="margin-left:3%">
<tr><td class=letop width=7%>
';
$query="SELECT NAME FROM $tab WHERE id=(SELECT mother FROM $tab WHERE id=(SELECT mother FROM $tab WHERE id=$record))";
$resbamo=mysql_result((mysql_query($query)), 0);
$query="SELECT id FROM $tab WHERE id=(SELECT mother FROM $tab WHERE id=(SELECT mother FROM $tab WHERE id=$record))";
$resbamoid=mysql_result((mysql_query($query)), 0);
$query="SELECT NAME FROM $tab WHERE id=(SELECT mother FROM $tab WHERE id=(SELECT father FROM $tab WHERE id=$record))";
$resbapa=mysql_result((mysql_query($query)), 0);
$query="SELECT id FROM $tab WHERE id=(SELECT mother FROM $tab WHERE id=(SELECT father FROM $tab WHERE id=$record))";
$resbapaid=mysql_result((mysql_query($query)), 0);
$query="SELECT NAME FROM $tab WHERE id=(SELECT father FROM $tab WHERE id=(SELECT mother FROM $tab WHERE id=$record))";
$resdemo=mysql_result((mysql_query($query)), 0);
$query="SELECT id FROM $tab WHERE id=(SELECT father FROM $tab WHERE id=(SELECT mother FROM $tab WHERE id=$record))";
$resdemoid=mysql_result((mysql_query($query)), 0);
$query="SELECT NAME FROM $tab WHERE id=(SELECT father FROM $tab WHERE id=(SELECT father FROM $tab WHERE id=$record))";
$resdepa=mysql_result((mysql_query($query)), 0);
$query="SELECT id FROM $tab WHERE id=(SELECT father FROM $tab WHERE id=(SELECT father FROM $tab WHERE id=$record))";
$resdepaid=mysql_result((mysql_query($query)), 0);
echo "
<b>мама: </b><br><br>
<b>папа: </b><br><br><br>
<b>бабушки:&nbsp; </b><br><br><br>
<b>дедушки: </b><br><br><br>
</td>
<td class=letop width=25%>";
if ($idmo==null) {
echo "
<a href=\"/singleview.php?record=1\" title=\"Мама\">".$resnamo."</a><br><br>";
}
else {
echo "
<a href=\"/singleview.php?record=$idmo\" title=\"Мама\">".$resnamo."</a><br><br>";
}
if ($idfa==null) {
echo "<a href=\"/singleview.php?record=2\" title=\"Папа\">".$resnafa."</a><br><br><br>";
}
else {
echo "<a href=\"/singleview.php?record=$idfa\" title=\"Папа\">".$resnafa."</a><br><br><br>";
}
echo "
<a href=\"/singleview.php?record=$resbamoid\" title=\"Мамина мама\">".$resbamo."</a><br>
<a href=\"/singleview.php?record=$resbapaid\" title=\"Папина мама\">".$resbapa."</a><br><br>
<a href=\"/singleview.php?record=$resdemoid\" title=\"Мамин папа\">".$resdemo."</a>
<a href=\"/singleview.php?record=$resdepaid\" title=\"Папин папа\"><br>".$resdepa."<br><br>

";






echo "<br><br><br><br>


 
</td>";	



//----братья-сестры--------------

echo '<td class=letop width=33%>';

$broidqu="SELECT id FROM $tab WHERE mother='$idmo' || father='$idfa' order by birth";
//echo 'broidqu='.$broidqu.'<br>';
$quebro = mysql_query($broidqu,$conn) or die(); // отправляем
           // запрос на сервер
$nsbro = mysql_num_rows($quebro);
	for ($i=0; $i<$nsbro; $i++) {
$broid=mysql_result(mysql_query($broidqu),$i);
if ($broid=='2') {
$broid=null;
}
		if (($broid)&&($broid!==$record)) {$broids[]=$broid;
		}

		
		$brogenqu="SELECT gender FROM $tab WHERE id=$broid";
$brogen=mysql_result((mysql_query($brogenqu)), 0);
if ($brogen&&($broid!==$record)) {
$queryname="SELECT name FROM $tab WHERE id=$broid";
$broname=mysql_result((mysql_query($queryname)), 0);
if ($brogen=='f') {
$siid[]=$broid;
$sisnames[]=$broname;
	//echo "сестра: <a href=\"/singleview.php?record=$broid\">".$broname.'</a><br>';
}
if ($brogen=='m') {
$brid[]=$broid;
$bronames[]=$broname;
//	echo "брат: <a href=\"/singleview.php?record=$broid\">".$broname.'</a><br>';

}} 
} 
$brosqu=count($broids);
//echo "brosqu=".$brosqu."<br>";
for ($i=0; $i<$brosqu; $i++) {
$query="select id from $tab where mother=\"$broids[$i]\" or father=\"$broids[$i]\"";
//echo "nepqu=".$query."<br>";
$ns = mysql_num_rows(mysql_query($query));
for ($k=0; $k<$ns; $k++) {
$result=mysql_result((mysql_query($query)), $k);
if ($result) {
$nepid[]=$result;
}
}

} 

//print_r($nepid);

	$sisquant=count($sisnames);
//	echo "sisquant=".$sisquant."<br>";
	$brosquant=count($bronames);
//		echo "brosaquant=".$brosquant."<br>";
	$brons=count($broids);
	for ($i=0; $i<$brons; $i++) {
	$quege="select gender from people where id=\"$broids[$i]\"";
	$resge=mysql_result((mysql_query($quege)), 0);
	if ($resge=='m') {
	$bro=$i;
	}
	else {
	$si=$i;
	}
	}
	if ($brosquant==1) {
		echo "<table border=0><tr><td class=letop ><b>брат:&nbsp;</b></td><td class=letop><a href=\"/singleview.php?record=$broids[$bro]\">".$bronames[0].'</a></td></tr></table><br>';
	}
	if ($brosquant>1){
		echo "<table border=0 ><tr><td class=letop width=60px><b>братья:</b>&nbsp; </td>
		<td class=letop>";
		for ($i=0; $i<$brosquant; $i++)
		{
					echo "<a href=\"/singleview.php?record=$brid[$i]\">".$bronames[$i].'</a><br>';
		}
		echo '</td></tr></table><br>';
	}
//	echo '<br>';
	if ($sisquant==1) {
		if ($brosquant) {
		echo "<br><b>сестра:</b>&nbsp; <a href=\"/singleview.php?record=$broids[$si]\">".$sisnames[0].'</a><br><br>';
		} 
		else {
		echo "<b>сестра:</b>&nbsp; <a href=\"/singleview.php?record=$broids[0]\">".$sisnames[0].'</a><br><br>';
		}
	}
	if ($sisquant>1){
		echo "<table border=0 ><tr><td class=letop width=60px><b>сестры:</b>&nbsp; </td>";
		echo '<td class=letop>';
		for ($i=0; $i<$sisquant; $i++)
		{
					echo "<a href=\"/singleview.php?record=$siid[$i]\">".$sisnames[$i].'</a><br>';
		}
		echo '</td></tr></table>';
	}

//echo '</td><td class=letop>';

//----братья-сестры (окончание)--------------

//----тети-дяди--------------
/*echo 'мамина мама id: '.$resbamoid.'<br>';
echo 'мамин папа id: '.$resdemoid.'<br>';
echo 'папина мама id: '.$resbapaid.'<br>';
echo 'папин папа id: '.$resdepaid.'<br>';*/

//$query="select NAME from $tab where MOTHER='$resbamoid' or father='$resdemoid'";
//мамина мама


if (($resbamoid!=='1')&&($resdemoid!=='2')&&($resbapaid!=='2')&&($resdepaid!=='2')) {
$query="select id from $tab where mother='$resbamoid' or mother='$resbapaid' or father='$resdemoid' or father='$resdepaid'";
}

else {
if (($resbamoid!=='1')&&($resdemoid=='2')) {
$query="select id from $tab where mother='$resbamoid'";
}
if (($resbamoid=='1')&&($resdemoid!=='2')) {
$query="select id from $tab where father='$resdemoid'";
}
if (($resbamoid=='1')&&($resdemoid=='2')) {
$query=null;
}
}
//echo 'запрос: '.$query.'<br>';

	
$ns = mysql_num_rows(mysql_query($query));
for ($i=0; $i<$ns; $i++) {
$result=mysql_result((mysql_query($query)), $i);
if ($result!==$idmo) {
//echo $result.'<br>';	
$queryname="select name from $tab where id='$result'";
//echo 'queryname='.$queryname.'<br>';
$resultname=mysql_result((mysql_query($queryname)), 0);
//echo 'тет='.$resultname.'<br>';
$querygen="select gender from $tab where id='$result'";
//echo 'querygen='.$querygen.'<br>';
$resultgen=mysql_result((mysql_query($querygen)), 0);
if ($resultgen=='m') {
if ($result!==$idfa) {
//$uncleid[]=$result;
if ($resultname!==' неизв.папа') {
$unclename[]=$resultname;
}

$qupairid="select mother from $tab where father='$result'";
$respair=mysql_result((mysql_query($qupairid)), 0);
$qupairname="select name from $tab where id='$respair'";
$respairname=mysql_result((mysql_query($qupairname)), 0);
//$auntid[]=$respair;
if ($respairname) {
if ($respairname!==' неизв.мама') {
$auntname[]=$respairname;
}
}

}
}
if ($resultgen=='f') {
if ($result!==$idmo) {
$qupairid="select father from $tab where mother='$result'";
$qupairname="select name from $tab where id=(select father from $tab where mother='$result')";
//echo 'qu2='.$qupairid.'<br>';
//echo 'qu2name='.$qupairname.'<br>';
$respairname=mysql_result((mysql_query($qupairname)), 0);
//echo 'respairname: '.$respairname.'<br>';
$respair=mysql_result((mysql_query($qupairid)), 0);
//$uncleid[]=$respair;
if ($respairname) {
if ($respairname!==' неизв.папа') {
$unclename[]=$respairname;
}

}
//echo 'respair: '.$respair.'<br>';
//$auntid[]=$result;
if ($resultname!==' неизв.мама') {
$auntname[]=$resultname;
}
}
}
}
}

$nsuncle=count($unclename);
$nsaunt=count($auntname);
sort($unclename);
for ($i=0; $i<$nsuncle; $i++) {
$query="select id from $tab where name='$unclename[$i]'";
$result=mysql_result((mysql_query($query)), 0);
$uncleid[]=$result;
} 
sort($auntname);
for ($i=0; $i<$nsaunt; $i++) {
$query="select id from $tab where name='$auntname[$i]'";
$result=mysql_result((mysql_query($query)), 0);
$auntid[]=$result;
} 

//print_r($auntname);
//echo 'nsuncle='.$nsuncle.'<br>';
//echo 'nsaunt='.$nsaunt.'<br>';
if ($nsuncle>0) {
echo ' <table border="0" cellspacing="0" bordercolor="white" 
width=100% >
<tr>
<td class=letop width=50px><b>дяди:</td> <td class=letop>';
}
for ($i=0; $i<$nsuncle; $i++) {
echo "<a href=\"/singleview.php?record=$uncleid[$i]\">".$unclename[$i].'</a><br>';
$query="select id from $tab where father='$uncleid[$i]'";	
$ns = mysql_num_rows(mysql_query($query));
for ($k=0; $k<$ns; $k++) {
$result=mysql_result((mysql_query($query)), $k);
$cousinid[]=$result;
}
}
//print_r($cousinid); 
if ($nsuncle>0) {
echo '</tr></td></table><br>';
}


if ($nsaunt>0) {
echo ' <table border="0" cellspacing="0" bordercolor="white" 
width=100% >
<tr>
<td class=letop width=50px><b>тети:</td> <td class=letop>';
}
for ($i=0; $i<$nsaunt; $i++) {
echo "<a href=\"/singleview.php?record=$auntid[$i]\" >".$auntname[$i].'</a><br>';
$query="select id from $tab where mother='$auntid[$i]'";	
$ns = mysql_num_rows(mysql_query($query));
for ($k=0; $k<$ns; $k++) {
$result=mysql_result((mysql_query($query)), $k);
//echo 'plusres='.$result.'<br>';
if (in_array($cousinid, $result)==false) {}
else {

$cousinid[]=$result;
} 


//else echo 'уже есть';
}
} 
//else echo 'уже есть';


 
if ($nsaunt>0) {
echo '</td></tr></table>';
}

//----тети-дяди (окончание)--------------

//----кузены-
$cousquan=count($cousinid);
if ($cousquan>0) {


echo ' <br><table border="0" cellspacing="0" bordercolor="white" 
width=100% >
<tr>
<td class=letop width=70px><b>двоюродные братья/сестры: </b><br>';
//print_r($cousinid);

//echo 'ns='.$cousquan.'<br>';
for ($i=0; $i<$cousquan; $i++) {
$query="select NAME from $tab where id='$cousinid[$i]'";
//echo 'quu='.$query.'<br>';	
$result=mysql_result((mysql_query($query)), 0);
echo "<a href=\"/singleview.php?record=$cousinid[$i]\" >".$result.'</a><br>';

//echo $cousin[$i].'<br>';
} 
echo '</td></tr></table><br>';
}
//----кузены (окончание)

//----племянники 
$nepquan=count($nepid);
if ($nepquan) {
echo '<br><b>племянники/племянницы:</b><br>';
} 

for ($i=0; $i<$nepquan; $i++) {
$query="select name from $tab where id=\"$nepid[$i]\"";
$result=mysql_result((mysql_query($query)), 0);
echo "<a href=\"/singleview.php?record=$nepid[$i]\" >".$result.'</a><br>';
} 
//----племянники (окончание)


$query="select NAME from $tab where MOTHER='$resbamoid' or MOTHER='$resbapaid' or father='$resdemoid' or father='$resdepaid'";
//echo "quee=".$query."<br>";
$ns = mysql_num_rows(mysql_query($query));
//echo 'ns='.$ns.'<br>';
for ($i=0; $i<$ns; $i++) {
$resultaumomo=mysql_result((mysql_query($query)), $i);
if ($resultaumomo!==$resnamo) {
//echo 'тет='.$resultaumomo.'<br>';
}
} 



//--------------дети---
if (($record=='1')||($record=='2')) {
$sql=null;
}
else {
if ($genn=='m') {
$sql = "SELECT * FROM $tab WHERE father=$record";
//echo 'sqlchild='.$sql.'<br>'; 
}
if ($genn=='f') {
$sql = "SELECT * FROM $tab WHERE mother=$record"; 
//echo $sql.'<br>'; 
}
}
$que = mysql_query($sql,$conn) or die(); // отправляем
           // запрос на сервер
$ns = mysql_num_rows($que);
//echo '<br>';
echo '</td><td class=letop width=50px><table border="0" cellspacing="0" bordercolor="white" 
width=100% ><tr><td class=letop width=55px>';

	//echo $ns.'<br>';
	if ($ns>1) {
echo '<b>дети:</b> ';
}

for ($i=0; $i<$ns; $i++) {
$childid = mysql_result($que,$i); 



$querygs="select id from $tab where mother=\"$childid\" or father=\"$childid\"";

$nsgs = mysql_num_rows(mysql_query($querygs));
for ($k=0; $k<$nsgs; $k++) {
$resgs=mysql_result((mysql_query($querygs)), $k);
$grandchi[]=$resgs;
}

$grandchina[]=$resultgsna;

//echo 'внук='.$resultgs.'<br>';

if ($resultgsna) {
$grandchina[]=$resultgsna;
}
$childnamequ="SELECT name FROM $tab WHERE id=$childid";
//echo 'chilID: '.$childid."<br>";
$childnameq = mysql_query($childnamequ); 
if ($ns==1) {
//echo '<br>';
//echo '1 ребенок<br>';
$query="select gender from $tab where id=$childid";
//echo 'gee='.$query.'<br>';

$result=mysql_result((mysql_query($query)), 0);
if ($result=='m') {
echo '<b>сын:</b> ';
}
if ($result=='f') {
echo '<b>дочь:</b> ';
}
if ($result=='?') {
echo '<b>ребенок:</b>&nbsp';
}
}
echo '</td><td class=letop>';
echo "<a href=\"/singleview.php?record=$childid\">".mysql_result($childnameq, 0)."</a><br></td></tr><td>";
//echo 'valuid='.$valuid.'<br>';
//echo 'table position: '.($i+1).'.  Value: '.$valuid.'<br>';

} 
echo '</td></tr>';

//echo '</td></tr></table>';
if ($genn=='m') {
$sql = "SELECT * FROM $tab WHERE father=$record";
//echo 'sqlchild='.$sql.'<br>'; 
}
if ($genn=='f') {
$sql = "SELECT * FROM $tab WHERE mother=$record"; 
//echo $sql.'<br>'; 
}
$que = mysql_query($sql,$conn) or die(); // отправляем
           // запрос на сервер
$ns = mysql_num_rows($que);
echo '</table>';

//--------внуки
$nsgrchi=count($grandchi);
if ($nsgrchi) {


echo '<table border="0" cellspacing="0" bordercolor="white" 
width=100% ><tr><td width=60px  class=letop><b>внуки:</b><br></td><td class=letop>';

for ($i=0; $i<$nsgrchi; $i++) {
//echo $grandchi[$i].'<br>';
$query="select name from $tab where id=\"$grandchi[$i]\"";
$result=mysql_result((mysql_query($query)), 0);
echo "<a href=\"/singleview.php?record=$grandchi[$i]\" >".$result.'</a><br>';
} 
echo '</td></tr></table>';
}
//--------внуки (окончание)




//--------------дети (окончание)
	//echo $ns.'<br>';





//echo 'мамаid: '.$idmo.'<br>';



echo '</td></tr></table>';


if ($idmo!=='1') {



	
	
//echo 'brogen: '.$brogen.'<br>';


}



//echo $issingle;
if($checkedquan==1) {



  $dir = 'images/'.$issingle.'/';
  $_SESSION['dir']=$dir;
//echo '<br>'.$dir.' - Папка с изображениями<br>';  // Папка с изображениями
  $cols = 7; // Количество столбцов в будущей таблице с картинками
  $files = scandir($dir); // Берём всё содержимое директории
  echo "<table align=center width=90%>"; // Начинаем таблицу
  $k = 0; // Вспомогательный счётчик для перехода на новые строки
  for ($i = 0; $i < count($files); $i++) { // Перебираем все файлы
    if (($files[$i] != ".") && ($files[$i] != "..")) { // Текущий каталог и родительский пропускаем
      if ($k % $cols == 0) echo "<tr>"; // Добавляем новую строку
      echo "<td>"; // Начинаем столбец
      $path = $dir.$files[$i]; // Получаем путь к картинке
	  if ($files[$i]) {
	  
	  
      echo "<a href='$path' target='_blank'>"; // Делаем ссылку на картинку
      echo "<img src='$path' alt='' width='100' />"; // Вывод превью картинки
      }
	  echo "</a>"; // Закрываем ссылку
      echo "</td>"; // Закрываем столбец
      //Закрываем строку, если необходимое количество было выведено, либо данная итерация последняя
      if ((($k + 1) % $cols == 0) || (($i + 1) == count($files))) echo "</tr>";
      $k++; // Увеличиваем вспомогательный счётчик
    }
  }
  echo "</table>"; // Закрываем таблицу

	}

 $dir = 'images/'.$record.'/';
  $_SESSION['dir']=$dir;
//echo '<br>'.$dir.' - Папка с изображениями<br>';  // Папка с изображениями
  $cols = 7; // Количество столбцов в будущей таблице с картинками
  $files = scandir($dir); // Берём всё содержимое директории
  echo "<table align=center width=90%>"; // Начинаем таблицу
  $k = 0; // Вспомогательный счётчик для перехода на новые строки
  for ($i = 0; $i < count($files); $i++) { // Перебираем все файлы
    if (($files[$i] != ".") && ($files[$i] != "..")) { // Текущий каталог и родительский пропускаем
      if ($k % $cols == 0) echo "<tr>"; // Добавляем новую строку
      echo "<td>"; // Начинаем столбец
      $path = $dir.$files[$i]; // Получаем путь к картинке
	  if ($files[$i]) {
	  
	  
      echo "<a href='$path' target='_blank'>"; // Делаем ссылку на картинку
	  $smallname = substr($files[$i], 0, 4);
	  $yo=$smallname-$yb;
	  if ($yo==0) {
	  $yo='меньше года';
	  }
	  elseif ($yo==1) {
	  $yo='1 год';
	  }
	  elseif (($yo>1)&&($yo<5)) {
	  $yo.=' года';
	  }
	  else {
	  $yo.=' лет';
	  }
      echo "<img src='$path' title=\"$yo\" alt='' width='100' />"; // Вывод превью картинки
      }
	  echo "</a>"; // Закрываем ссылку
      echo "</td>"; // Закрываем столбец
      //Закрываем строку, если необходимое количество было выведено, либо данная итерация последняя
      if ((($k + 1) % $cols == 0) || (($i + 1) == count($files))) echo "</tr>";
      $k++; // Увеличиваем вспомогательный счётчик
    }
  }
  echo "</table>"; // Закрываем таблицу

	





echo "<a href='http://famtree.site88.net/free.php>back</a>";

?>