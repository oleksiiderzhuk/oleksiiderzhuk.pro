<html>
	<head>
<meta charset="utf-8">
<title>Уход за родовым деревом</title>
	</head>
<body bgcolor="lightgrey">
	<?php 

session_start();
error_reporting(0);
require_once("ImageManipulator.php");
 ?>

<html><head>
<link rel="shortcut icon" href="edit.ico" type="image/x-icon">
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
          el.style.backgroundPosition="0 164px"; 
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
    padding:5px;
    text-align: center; /* Выравнивание по центру */
	vertical-align: middle;
	cellpadding: 20px;
	
   }
   TD.le {
       padding:0px;

	text-align: left; /* Выравнивание по левому краю */
	  }
	  
	td.le:hover::after {
    content: attr(data-title); /* Выводим текст */
    position: absolute; /* Абсолютное позиционирование */
    left: 20%; top: 30%; /* Положение подсказки */
    z-index: 1; /* Отображаем подсказку поверх других элементов */
    background: rgba(255,255,230,0.9); /* Полупрозрачный цвет фона */
    font-family: Arial, sans-serif; /* Гарнитура шрифта */
    font-size: 11px; /* Размер текста подсказки */
    padding: 5px 10px; /* Поля */
    border: 1px solid #333; /* Параметры рамки */
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
width: 50px;
height: 164px;
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
<?php 
function get_in_translate_to_en($string, $gost=false)
{

	if($gost)
	{
		$replace = array("А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
                "Е"=>"E","е"=>"e","Ё"=>"E","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
                "Й"=>"I","й"=>"i","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n","О"=>"O","о"=>"o",
                "П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t","У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f",
                "Х"=>"Kh","х"=>"kh","Ц"=>"Tc","ц"=>"tc","Ч"=>"Ch","ч"=>"ch","Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch",
                "Ы"=>"Y","ы"=>"y","Э"=>"E","э"=>"e","Ю"=>"Iu","ю"=>"iu","Я"=>"Ia","я"=>"ia","ъ"=>"","ь"=>"");
	}
	else
	{
		$arStrES = array("ае","уе","ое","ые","ие","эе","яе","юе","ёе","ее","ье","ъе","ый","ий");
		$arStrOS = array("аё","уё","оё","ыё","иё","эё","яё","юё","ёё","её","ьё","ъё","ый","ий");        
		$arStrRS = array("а$","у$","о$","ы$","и$","э$","я$","ю$","ё$","е$","ь$","ъ$","@","@");
                    
		$replace = array("-"=>"_","~"=>"_","А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
                "Е"=>"Ye","е"=>"e","Ё"=>"Ye","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
                "Й"=>"Y","й"=>"y","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n",
                "О"=>"O","о"=>"o","П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t",
                "У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f","Х"=>"Kh","х"=>"kh","Ц"=>"Ts","ц"=>"ts","Ч"=>"Ch","ч"=>"ch",
                "Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch","Ъ"=>"","ъ"=>"","Ы"=>"Y","ы"=>"y","Ь"=>"","ь"=>"",
                "Э"=>"E","э"=>"e","Ю"=>"Yu","ю"=>"yu","Я"=>"Ya","я"=>"ya","@"=>"y","$"=>"ye");
                
		$string = str_replace($arStrES, $arStrRS, $string);
		$string = str_replace($arStrOS, $arStrRS, $string);
	}
        
	return iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
}

 ?>
</head>
<body bgcolor="gainsboro">


<?php


$record=$_SESSION['record'];
$serv=$_SESSION['serv'];
$in=$_SESSION['in'];
$record=$_SESSION['record'];
$treno=$_SESSION['treno'];
//echo "treno=".$treno."<br>";
//echo 'in='.$in.'<br>';
if ((!$serv)||($in!=='ok')) {
/*
echo '<script language="JavaScript"> 
location.href = "index.php";
</script>';*/

}
//else {echo '112';	}


$enco=$_SESSION['enco'];
//echo 'enco='.$enco.'<br>';
$log=$_SESSION['log'];
$pas=$_SESSION['pas'];
$tab=$_SESSION['tab'];
$database=$_SESSION['db'];
$ids=$_SESSION['maxid'];
//echo 'serv='.$serv.'<br>';	
//echo 'log='.$log.'<br>';
//echo 'pas='.$pas.'<br>';

//echo 'ids='.$ids.'<br>';

 for ($i=0; $i<=$ids; $i++) {
//echo 'i='.$i.'<br>';
if ( isset( $_POST["$i"] ) ) {
//echo 'checkk: '.$i.'<br>';
$checked[]=$i;

}
}

if (!empty($checked)) {
$record=null;
$checkedquan=count($checked);
$_SESSION['check']=$checkedquan;
$_SESSION['checked']=$checked;
/*/*	if($checkedquan<1){
		echo '<script language="JavaScript"> 
location.href = "index.php";
</script>';
	}*/
	if($checkedquan==1){ 
	$issingle=$checked[0];
	
	if (!$issingle) {$issingle= $_SESSION['issingle'];
	} else {
	$_SESSION['issingle']=$issingle;}
	}
	else {
	$issingle='_';
	}
$_SESSION['checked']=$checked;

//echo 'che='.$checkedquan.'<br>';
//echo 'checked='; print_r($checked);
}

else {
//echo 'ses-rec='.$_SESSION['record'].'<br>';
$checked[]=$_SESSION['record'];
$checkedquan=1;
}
//echo 'recoo='.$record.'<br>';
//echo 'che0='.$checked[0].'<br>';




//print_r($checked);




//echo 'checked='.$checkedquan.'<br>';


//echo 'edid='.$toed.'<br>';

if (!isset($_POST['ed'])) {

$conn=mysql_connect("$serv","$log","$pas") or die("<script type=\"text/javascript\"> window.location.href=\"index.php\" </script>");// устанавливаем
             // соединение
			 mysql_query("SET NAMES $enco");
//if ($conn) echo 'connection successfull<br>';			 
mysql_select_db($database);
$db = $_SESSION['db'];
//$tab = 'people';
mysql_select_db($db); // выбираем базу данных

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

echo "<form method=\"post\" action=\"editing.php\" enctype=multipart/form-data>
<br><a href=\"singleview.php?record=$record\" style=\"margin-left: 50px\"><b><<< Назад</b></a> &nbsp; &nbsp; &nbsp;<a href=\"ftree.php?treno=$treno\" >Изучить всё дерево</a><div align=center><input type=submit class=\"knopka\" name=\"ed\" value=\"Сохранить изменения\"></div>";
echo "&nbsp;<TABLE BORDER=0 CELLSPACING=5 width=95%
    align=center cellpadding=7>
	
    <tr>";
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
//echo 'checkedquan='.$checkedquan.'<br>';
$_SESSION['check']=$checkedquan;
	for ($j=0; $j<$checkedquan; $j++) {
//echo 'tab='.$tab.'<br>';	
//echo 'te='.$checked[$j].'<br>';
$recoo=$checked[$j];
$sqlshow="select * from $tab where id=$checked[$j]";

$qshow = mysql_query($sqlshow) or die(); // отправляем
//echo '<br>'.$sqlshow.'<br>';
echo "<tr bgcolor='#98FB98'>";
        foreach  ($names as $val) { // перебираем все
                // имена полей
				//echo 'jj='.$\checked[$j].'<br>';
				
        $value = mysql_result($qshow,0,$val); // получаем
                // значение поля
				$delnum='d'.$value;
	if ($val==$key){
	echo "<td bgcolor='darkgrey' class=le title='отметьте для удаления'>
	<!--$delnum-->
	
	<span class=\"del\" onclick=\"changeCheck(this)\" id=\"$delnum\"><input type=\"checkbox\" name=\"$delnum\"  /></span>
</td>";
	}

		
	elseif ($val=='treno'){
	$qugen="select gender from $tab where id=$checked[$j]";

	$regen=mysql_result((mysql_query($qugen)), 0);}	
	

	elseif ($val=='photo')
	{
	$queryfo="select photo from $tab where id=$checked[$j]";
//echo 'quefo='.$queryfo.'<br>';
	$resultfo=mysql_result((mysql_query($queryfo)), 0);
	
	//echo '<br><br><br>resfot='.$query.'<br><br>'; echo $resultfo;	
	echo	'<td align=center>';
	echo "
<input type=file id=activebrowsebutton name=\"$j\" ><br><b style='color: red'>Макс. р-р файла: 2Мб</b><br>";
$query="SELECT name FROM $tab WHERE id=$checked[$j]";
//echo 'query='.$query.'<br>';
$sqlqu=mysql_query($query);
$person=mysql_result($sqlqu, 0); 
$resfo[]=$resultfo;
if (is_file("smallimages/".$resultfo)) {
echo "<a href=\"images/$resultfo\" target=\"blank\">";
}
echo "<img src=\"smallimages/$resultfo\" title=\"$person\"  width=150px></a>";

/*	echo "<label class=\"filebutton\">
<img src=\"images/$resultfo\" title=\"$resultfo\" style = \"cursor: pointer;\" width=200px>
<span><input type=\"file\" id=\"myfile\" name=\"myfile\"></span>
</label>";*/


	}
				elseif ($val=='pair')
	{
//	echo "</tr><tr>";
	$querypair="select pair from $tab where id=$checked[0]";
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
if ($regen=='m') {
echo "супруга:";
}
else {
echo "супруг:";
}
echo " $nnn\"  width=110px style=\"margin-top: 10px\"></a>";

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
	
	$query="select name from $tab where id=$checked[$j]";
	



	$result=mysql_result((mysql_query($query)), 0);
	//echo '...='.$result.'<br>';
	if ($result) {
	echo "<td><br><input type='text' class=username name=\"namee[]\" value=\"$result\">";
	} 
	else {
	echo "<td align=center><input type='text' class=username name=\"namee[]\" placeholder=\"Ім'я\">";
	}
	echo "<br><br>";
	
	echo "<b>";
		if ($regen=='m') {
	echo "супруга:<br>";
	}
	else {
		echo "супруг:<br>";

	}
		
	echo "</b>";
	

if ($regen=='m') {
		$pairlist="select name from $tab where ((gender=\"f\" and (treno=2 or treno=$treno)) and pair is null) order by name";
	//echo "mmo=".$mmolist."<br>";
	$pairlistt="<option>Не женат</option>";
	$res = mysql_query($pairlist);
				$nw = mysql_num_rows($res);
				$pairlistt.="<option selected=\"selected\">$nnn</option>";
				for($wcyc=0;$wcyc<$nw; $wcyc++){
					$pairidf = mysql_result($res, $wcyc);
					
					
						
					
					$pairlistt.="<option>$pairidf</option>";
					
				}
				echo "	<br>
									<select class=user name='pair' > 
										$pairlistt
									</select>
						";
						if(!empty($_POST["pair"]))  {
		$npair=$_POST["pair"];
		echo "</td>";
		}
		}
		if ($regen=='f') {
$res = mysql_query("select name from $tab where ((gender=\"m\" and (treno=2 or treno=$treno)) and pair is null) order by name");
	$pairlistt="<option>Не замужем</option>";

				$nw = mysql_num_rows($res);
				
				$pairlistt.="<option selected=\"selected\" >$nnn</option>";		
				
				for($mcyc=0;$mcyc<$nw; $mcyc++){
					$pairidm = mysql_result($res, $mcyc);
					
					
					
					
					$pairlistt.="<option>$pairidm</option>";
						
					
				}
							
				echo "	<br>
									<select name='pair' class=user> 
										$pairlistt
									</select>
						";
			if(!empty($_POST['pair']))  {$npair=$_POST['pair']; 
			}
	echo '</td>';		
		}
	}
	
	elseif ($val=='gender') {


	//echo '...='.$result.'<br>';
	if ($regen) {
	echo '<td>';	
	if ($regen=='m') {
	$gennn='m';
	echo "<input type=radio class=check name=\"gen[$j]\" value=' Male ' checked> Male <br>
<input type=radio class=check name=\"gen[$j]\" value=' Female '> Female ";
}
if ($regen=='f') { 
	$gennn='f';
echo "<input type=radio class=check name=\"gen[$j]\" value=' Male '> Male <br>
<input type=radio class=check name=\"gen[$j]\" value=' Female '  checked> Female ";
echo "</td>";
	}
if ($result=='?') { echo "<input type=radio class=check name=\"gen[$j]\" value=' Male '> Male <br>
<input type=radio class=check name=\"gen[$j]\" value=' Female ' > Female 
<br><input type=radio class=check name=\"gen[$j]\" value=' ? '  checked> ? ";
	}
echo "</td>";
}	
	else {
	echo "<td align=center>error...<br><input type=radio class=check name=\"gen[$j]\" value=' Male '> Male <br>
<input type=radio class=check name=\"gen[$j]\" value=' Female ' > Female </td>";
	}
	}


elseif ($val=='birth') {	
	$query="select birth from $tab where id=$checked[$j]";

	$result=mysql_result((mysql_query($query)), 0);
	
	//echo '...='.$result.'<br>';
	echo '<td>';
	
	

	if ($result) {
	$resultnorm = date("d.m.Y", strtotime($result));
	if (($resultnorm=='01.01.1970')||($resultnorm=='30.11.-0001')||($result=='01-01-2000')) {
	$resultnorm=null;}
	$ifyearonly=substr($resultnorm, 0, 5);
	if ($ifyearonly=='01.01') {
	$yearonly=substr($resultnorm, 6);
	$resultnorm=$yearonly;
	}		
	echo	"<input type=\"text\" class=userdate name=\"bir[]\" value=\"$resultnorm\" onfocus=\"this.select();_Calendar.lcs(this)\"
    onclick=\"event.cancelBubble=true;this.select();_Calendar.lcs(this)\">";}
	else {
	echo	"<input type=\"text\" class=userdate name=\"bir[]\" value=\"\" onfocus=\"this.select();_Calendar.lcs(this)\"
    onclick=\"event.cancelBubble=true;this.select();_Calendar.lcs(this)\">";
	}
	echo '</td>';
	}
	
	elseif ($val=='death') {	
	$query="select death from $tab where id=$checked[$j]";

	$result=mysql_result((mysql_query($query)), 0);
//echo '...='.$result.'<br>';
	echo '<td>';
	if ($result) {
	$resultnorm = date("d.m.Y", strtotime($result));
	if ($resultnorm=='01.01.1970') {
	$resultnorm=null;}
		if ($resultnorm=='30.11.-0001') {
	$resultnorm=null;}
	$ifyearonly=substr($resultnorm, 0, 5);
	if ($ifyearonly=='01.01') {
	$yearonly=substr($resultnorm, 6);
	$resultnorm=$yearonly;
	}		
echo	"<input type=\"text\" class=userdate name=\"end[]\" value=\"$resultnorm\" onfocus=\"this.select();_Calendar.lcs(this)\"
    onclick=\"event.cancelBubble=true;this.select();_Calendar.lcs(this)\">";}
	else {
	echo	"<input type=\"text\" class=userdate name=\"end[]\" value=\"\" onfocus=\"this.select();_Calendar.lcs(this)\"
    onclick=\"event.cancelBubble=true;this.select();_Calendar.lcs(this)\">";
	}
	}
	
	elseif ($val=='mother')
	{
	//echo "val=".$value."<br>";
	$query="select name from $tab where id=$value";
	$queryfot="select photo from $tab where id=$value";
	$resultfot=mysql_result((mysql_query($queryfot)), 0);
	$result=mysql_result((mysql_query($query)), 0);
	//echo "<td align=center><font size=5 >$result</font></td>";
	echo '<td><b>мама</b><br><br>';
	if ($result) {
	echo "<img src='smallimages/$resultfot' title=\"$result\" width=100px>";
	} 
	else {
	echo "<img src=images/001.jpg width=100px>";
	}
	$query="select name from $tab where id=$value";
	$result=mysql_result((mysql_query($query)), 0);
	$mmolist="select name from $tab where (gender=\"f\" and (treno=2 or treno=$treno) ) order by name";
	//echo "mmo=".$mmolist."<br>";
	$res = mysql_query($mmolist);
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
				echo "	<br>
									<select class=user name='mother[]' > 
										$momlist
									</select>
						";
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
	//echo "<td align=center><font size=5 >$result</font></td>";	
	echo '<td><b>папа</b><br><br>';
	if ($result) {
	echo "<img src='smallimages/$resultfot' title=\"$result\" width=100px>";
	
	} 
	else {
	echo "<img src=images/000.jpg width=100px>";
	}
	//echo "treno=".$treno."<br>";
	$res = mysql_query("select name from $tab where (gender=\"m\" and (treno=2 or treno=$treno)) order by name");
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
							
				echo "	<br>
									<select name='father[]' class=user> 
										$paplist
									</select>
						";
			if(!empty($_POST['father']))  {$paval=$_POST['father']; }
	echo '</td>';
	}
	
	
		elseif ($val=='story')
	{
	$query="select story from $tab where id=$checked[$j]";
//	echo 'query='.$query.'<br>';
	$result=mysql_result((mysql_query($query)), 0);
	//echo "<td align=center><font size=5 >$result</font></td>";	
	echo '<td>';
	if ($conn) { 
	//echo 'connOK<br>';
	} else echo 'no connect<br>';
	if ($result) {
	$namsto=$j.'sto';
	echo "<textarea rows='10' cols='45' width=\"100px\" name=\"story[]\">$result</textarea>";
	} 
	else {
	echo "<textarea rows='10' cols='45' name=\"story[]\" placeholder='кратко расскажите про человека'></textarea>";
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
		
	}
echo "</table>";
//echo $issingle;
if($checkedquan==1) {


if ($issingle) {
$dir = 'images/'.$issingle.'/';
}
if ($checked[0]) {
$dir = 'images/'.$checked[0].'/';
}
  
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
	if (!isset($_POST['fil'])) {
	echo	'<form method=post><input type=file name="fooplus">
<br><input type=submit class=knopka name="fil" value="+"></form>';
	}
	else {
	echo	'<form method=post><input type=file name="fooplus">
<br><input type=submit class=knopka name="fil" value="+"></form>';
	$dir=$_SESSION['dir'];
	mkdir($dir);
//	echo 'sing='.$issingle.'<br>';
	//echo 'dir='.$dir.'<br>';

	copy($_FILES[fooplus]["tmp_name"],"$dir".basename($_FILES[fooplus]['name']));
echo '<script language="JavaScript"> 
location.href = "editing.php";
</script>';

	//echo 'copied...<br>';
/*	echo '<script type="text/javascript">'; 
echo 'window.location.href="ftree.php";'; 

echo '</script>'; */
	}

}
else{

echo ' <table cellpadding="30" 
border="0" cellspacing="0"
width=100% height=80% border=0>
<tr valign=middle>
<td align=center><img src="waitt.gif" width=150px ></td>
</tr>
</table> ';
//echo '<a onclick="javascript:history.back();">назад</a><br>
//<a href="index.php">Назад к таблице</a><br>';
$conn=mysql_connect("$serv","$log","$pas") or die("<script type=\"text/javascript\"> window.location.href=\"index.php\" </script>");// устанавливаем соединение
mysql_query("SET NAMES $enco");
//if ($conn) echo 'connection successfull<br>';			 
mysql_select_db($database);
$checkedquan=$_SESSION['check'];
if ($conn) { 
$issingle= $_SESSION['record'];
//echo 'issi='.$issingle.'<br>';
//echo 'connOKK<br><br><br>';} else echo 'no connect<br>';
//echo 'checkedquan='.$checkedquan.'<br>';
for ($i=0; $i<$checkedquan; $i++) {

$checked=$_SESSION['checked'];
$chee=$checked[$i];
if (!$chee) {$chee=$issingle;

}
$delnam='d'.$chee;
//echo 'chee='.$chee.'<br>';
//echo 'delnam='.$delnam.'<br>';
if (isset($_POST[$delnam])) {
$query="delete from $tab where id=$chee";
//echo 'qude='.$query.'<br>';
$delett=mysql_query($query);
if ($delett) {
//echo 'deletok<br>';
}
}
//echo $query.'<br>';

if (isset($_POST['$delnam'])) {
$query.="delete from $tab where id=$i";
//$result=mysql_result((mysql_query($query)), 0);
}
} 
//echo 'delqu='.$query.'<br>';
$checkedquan=$_SESSION['check'];
//echo 'checkedquan='.$checkedquan.'<br>';

$checked=$_SESSION['checked'];
//echo 'checked-[]='.$checked[0].'<br>';
for ($i=0; $i<$checkedquan; $i++) {
$iddd=$checked[$i];
//echo '------------------------<br><br><br>';
//echo 'iddd: '.$iddd.'<br>';
	$sizee=$_FILES[$i]['size'];
	//echo "siz=".$sizee."<br>";
	if ($sizee>1999999) {
	die("<br><br><center><b>Уменьшите, пожалуйста, фотографию до размера не более 2Мб </b><br><a onclick=\"javascript:history.back();\" style=\"text-decoration: underline; cursor: pointer; color: blue;\" > <br><b><<< Назад</b></a></center>");
	}
	
copy($_FILES[$i]['tmp_name'],"smallimages/".basename($_FILES[$i]['name']));
copy($_FILES[$i]['tmp_name'],"images/".basename($_FILES[$i]['name']));
$newfot=$_FILES[$i]['name'];
$validExtensions = array('.jpg', '.jpeg', '.gif', '.png');
//	echo "допустимые расширения: "; print_r($validExtensions);
    // get extension of the uploaded file
    $fileExtension = strrchr($_FILES[$i]['name'], ".");
    // check if file Extension is on the list of allowed ones
    if (in_array($fileExtension, $validExtensions)) {
          $rus=$_FILES[$i]['name'];
  $shoname = substr($rus, 0, 21);
$trans=get_in_translate_to_en($shoname);
//echo "trans=".$trans."<br>";
		$newNamePrefix = time() . '_';
		//echo "newpref=".$newNamePrefix."<br>";
		$finam=$newNamePrefix. $trans;
	//	echo $finam."<br>";
		$newfot=$finam;
		$teim=$_FILES[$i]['tmp_name'];
        $manipulator = new ImageManipulator($teim);
		$manipulator->save('images/'  .$finam);
        $width  = $manipulator->getWidth();
        $height = $manipulator->getHeight();
	
        $centreX = round($width / 2);
        $centreY = round($height / 2);
        // our dimensions will be 200x130
        $x1 = $centreX - 100; // 200 / 2
        $y1 = $centreY - 65; // 130 / 2
 
        $x2 = $centreX + 100; // 200 / 2
        $y2 = $centreY + 65; // 130 / 2
 
        // center cropping to 200x130
			$k=$width/$height;
			if ($k>=1) {
		$nh=200;
		$nw=200*$k;
		}
		else {
		$nw=200;
		$nh=$nw/$k;
		}
        $newImage = $manipulator->resample($nw, $nh);
        // saving file to uploads folder
        $manipulator->save('smallimages/' . $finam);
//        echo 'Done ...';
		$foo=$finam;
    } else {
        echo 'You must upload an image...';
    }
if (!$newfot) {

if ( $_SESSION['record'] ) {
$recoo=$_SESSION['record'];
$query="select photo from $tab where id=$recoo";
}
else {
$query="select photo from $tab where id=$iddd";
}
//echo '1222='.$query.'<br>';
$result=mysql_result((mysql_query($query)), 0);

//echo 'rrre='.$result.'<br>';
	if (!$result) {
	//echo 'nonewfot<br>';
	$query="select gen from $tab where id=$iddd";
	$result=mysql_result((mysql_query($query)), 0);
	if ($result='m') {
	$resultfo='000.jpg';
	}
	else $resultfo='001.jpg';
	}
	else {
	$newfot=$result;
	
	}
	//echo 'fottoinsert='.$newfot.'<br>';
	

//	echo 'resfoooo='.$resultfo.'<br>';

}
//echo '1: newfot='.$newfot.'<br>';	
//-1-1-1111111-----------------------
$ii=$i-2;
$na=$_POST['namee'][$i]; 
//echo '2: nam='.$na.'<br>';
$npair=$_POST['pair']; 
if ($npair) {
$oldpairqu="select pair from people where id=\"$recoo\"";
$oldpair=mysql_result((mysql_query($oldpairqu)), 0);
$clearoldqu="update $tab set pair=null where id=\"$oldpair\"";
$clearold=mysql_result((mysql_query($clearoldqu)), 0);
$query="SELECT id FROM $tab WHERE NAME LIKE \"$npair\"";
//echo 'quuu='.$query.'<br>';
if (($npair=='Не замужем')||($npair=='Не женат')) {
$npai='null';
}
$npai=mysql_result((mysql_query($query)), 0); 
$pairckeckqu="UPDATE people SET pair='$recoo' where id='$npai'";
echo "!!!: ".$recoo."<br>";
//echo "pairckeckqu: ".$pairckeckqu."<br>";
$paircheck=mysql_result((mysql_query($pairckeckqu)), 0);
$pairdeloldchainqu="UPDATE people SET pair=null where id='$oldpair'";
}
//echo "npai: ".$npai."<br>";
if ($_POST['gen']["$i"]==' Male ') {
$ge='m';
}
if ($_POST['gen']["$i"]==' Female ') {
$ge='f';
}
if ($_POST['gen']["$i"]==' ? ') {
$ge='?';
}
if ($_POST['bir']["$i"]) {
$bi=$_POST['bir']["$i"];
if (ereg("^[0-9]{4,4}$",$bi)) {
$bitodb=$bi.'-01-01';
}
else {
$bitodb = date("Y-m-d", strtotime($bi));
}
}
else $bitodb='0000-00-00';

if ($_POST['end']["$i"]) {
$de=$_POST['end']["$i"];
if (ereg("^[0-9]{4,4}$",$de)) {
$detodb=$de.'-01-01';
}
else {
$detodb=date("Y-m-d", strtotime($de));
}
}
else $detodb='00-00-0000';

if ($_POST['mother'][$i]) {
if (($_POST['mother'][$i])=='неизв.мама') {
$mo='1';

//echo '11111='.$monam.'<br>';
}
else {
$monam=$_POST['mother']["$i"];
//echo 'monam='.$monam.'<br>';	
	$query="SELECT id FROM $tab WHERE NAME LIKE \"$monam\"";
//echo 'query='.$query.'<br>';
$sqlqu=mysql_query($query);
if ($sqlqu) { 
//echo 'okkkk<br>';
} else echo 'badqu<br>';
$mo=mysql_result($sqlqu, 0); 
//echo 'mother: '.$mo.'<br>'; 
}}
else $mo='1';
if ($_POST['father'][$i]) {
if (($_POST['father'][$i])=='неизв.папа') {
$fa=2;
//echo '11111='.$fanam.'<br>';
}
$fanam=$_POST['father'][$i];

if ($fa!==2) {
$query="SELECT id FROM $tab WHERE NAME LIKE \"$fanam\"";
//echo 'quuu='.$query.'<br>';
$fa=mysql_result((mysql_query($query)), 0); }
}
else $fa='2';
$fot=$_FILES['fott'][$i];


if ($_POST['story'][$i]) {
$story=$_POST['story'][$i];
//echo 'story: '.$story.'<br>';
}
echo "npair: ".$npair."<br>";

//else echo 'no story';




//echo 'quan='.$checkedquan.'<br>';
$checked=$_SESSION['checked'];
//echo 'checked='; print_r($checked);


if ( $_SESSION['record'] ) {
$reco= $_SESSION['record'];
if ($mo=='') {
$mo='1';
}

if ($fa=='') {
$fa='2';
}
if ($npai==null) {
$edqu="UPDATE $tab SET NAME='$na', gender='$ge', birth='$bitodb', death='$detodb', mother='$mo', father='$fa', photo='$newfot', story='$story', pair = null WHERE id='$reco'";
echo '<br><br><br>'.$edqu.'<br><br><br>';
} 
else{
$edqu="UPDATE $tab SET NAME='$na', gender='$ge', birth='$bitodb', death='$detodb', mother='$mo', father='$fa', photo='$newfot', story='$story', pair='$npai' WHERE id='$reco'";
echo '<br><br><br>'.$edqu.'<br><br><br>';
}
}
else{
if ($npai==null) {
$edqu="UPDATE $tab SET NAME='$na', gender='$ge', birth='$bitodb', death='$detodb', mother='$mo', father='$fa', photo='$newfot', story='$story', pair = null WHERE id='$iddd'";
echo $edqu."<br>";
} 
else {
$edqu="UPDATE $tab SET NAME='$na', gender='$ge', birth='$bitodb', death='$detodb', mother='$mo', father='$fa', photo='$newfot', story='$story', pair='$npai' WHERE id='$iddd'";
echo $edqu."<br>";
}
}
//echo '<br><br><br>'.$edqu.'<br><br><br>';

//if ($conn) echo 'con ok'; else echo 'con bad';
$edco=mysql_query($edqu);
//if ($edco) echo "Edited successfully!<br><br><br>";
//." edqu=$edqu"

//Header("Location: http://localhost/ftree/personstab.php");} 
//else echo "error...<br><br><br>";
}

/*
echo 'foto: '.$newfot.'<br>';
echo 'name='.$na.'<br>';
echo 'gender='.$ge.'<br>';
echo 'birth='.$bi.'<br>';
echo 'dee='.$de.'<br>';
echo 'mother='.$mo.'<br>';
echo 'father='.$fa.'<br>';
echo 'story: '.$story.'<br><br><br>';
*/

echo '<script type="text/javascript">'; 
echo "window.location.href=\"singleview.php?record=$record\";"; 
echo '</script>';
}


	}

echo "<a href='http://famtree.site88.net/free.php>back</a>";

?>