<html><head>
<meta charset="utf-8">
<title>Добавление человека в родовое дерево</title>
<link rel="shortcut icon" href="ftree.ico" type="image/x-icon">
<script src="calendar_kdg.js" type="text/javascript"></script>
<script type="text/javascript">


function viewsingle(record)
{
var viewsingle = encodeURIComponent(record);
window.location.href = 'singleview.php?record='+record;
}
</script>

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
<body bgcolor="lightgrey">

<?php
//$prov=1;
session_start();
error_reporting(0);
require_once("ImageManipulator.php");

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
$pastree= $_SESSION['pastree'];
$treno=$_GET['treno'];
$_SESSION['treno']=$treno;
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
$conn=mysql_connect("$serv","$log","$pas") or die("Ошибка при подключении");// устанавливаем
             // соединение
			 mysql_query("SET NAMES $enco");


		//	  print($mysqlv);


mysql_select_db($db); // выбираем базу данных

$passqu="select pass from names where id=\"$treno\"";
$pass=mysql_result((mysql_query($passqu)), 0);
//echo "treno=".$treno."<br>";	
//echo "pas=/".$pass."/<br>";
//echo "inses=/".$pastree."/<br>";
//echo "indb=/".$pass."/<br>";
if (($pastree!==$pass)&&($pass)) {
die('<br><a href="enterpas.php">пройдите авторизацию');
//echo "no<br>";
}

$list_f = mysql_list_fields($db,$tab);
           // получаем список полей в таблице
$n = mysql_num_fields($list_f); // число строк в результате
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
$treno= $_GET['treno'];
//echo '<b>'.$treno.'</b>';
echo '<form method="post"  enctype=multipart/form-data>';


		

		
		
echo '

';		 
		
		

		
		
		 if (!isset($_POST['add'])) {
		 
	echo "<table border=0	 bordercolor='white' CELLSPACING=1 align=center width=90%>";
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
	}

            // запоминаем имя автоинкремента
			// запоминаем позицию автоинкремента
    /* для каждого поля, не являющегося автоинкрементом, в
	зависимости от его типа выводим подходящий элемент формы */
	
				
	
	if ($key <> $name_f){
	
	
		
		if ($name_f=='name'){
				echo "	<tr><td><a href=\"ftree.php\" ><b><<< Назад</b></a><br></td><td colspan=3 align=right style=\"margin-left: 50px\"><input type=text class='username' rows=1 cols=30% name=name placeholder=\"Имя\" >
						";
		}
		
		
		if ($name_f=='gender'){
				echo "	<input type=radio class=check name=gender value=' Male '> <img src='images/m.png' width=20px> 
																		<input type=radio class=check name=gender value=' Female '> <img src='images/f.png' height=39px> 
								</td><td colspan=2><a align=left href=\"index.php\"><b>Изучить другое дерево в лесу</b></a></td></tr><tr>";
						
				if ($_POST['gender']==' Male ') {$gende='m';} 
				if ($_POST['gender']==' Female ') {$gende='f';} 
				if ($_POST['gender']==null) {$gende='?';} 
		}
		
		
		

		if ($name_f=='birth'){
				echo "<td><i>День рождения</i><br><input type=\"text\" class=userdate name=\"bir\" value=\"\" onfocus=\"this.select();_Calendar.lcs(this)\"
    onclick=\"event.cancelBubble=true;this.select();_Calendar.lcs(this)\"></td>";

		}
		

		if ($name_f=='death'){
		
				echo "<td> <i>День смерти</i><br><input type=\"text\" class=userdate name=\"end\" value=\"\" onfocus=\"this.select();_Calendar.lcs(this)\"
    onclick=\"event.cancelBubble=true;this.select();_Calendar.lcs(this)\"></td>";

		}
				
		
		if ($name_f=='mother'){
			$today=date("Y-m-d");
			$momage=new DateTime($today);
			$momage->modify("-13 year");
			$momag=$momage->format("Y-m-d");
		//	echo $momag;
			
				$res = mysql_query("select name from $tab where (gender=\"f\" AND birth<\"$momag\") and ((treno=$treno) or (treno=2)) order by name");
				$nw = mysql_num_rows($res);
				for($wcyc=0;$wcyc<$nw; $wcyc++){
					$momsid = mysql_result($res, $wcyc);
					$momlist.="<option>$momsid</option>";
					
				}
							
				echo "	<td><i>Мама</i><br>
									<select class=user name=\"moth\" > 
										<option></option>$momlist
									</select>
								</td>
						";
						
		if(!empty($_POST["moth"]))  {
		$maval=$_POST["moth"];
		
		echo 'maval='.$maval.'<br>'; }

	//YESYESYES		
			//echo mysql_result((mysql_query("select id from $tab where name='girl'")), 0);
	//YESYESYES		
	
			//echo 'id ma: '.$whatma.'<br>';
					
		
				
		}
		

		if ($name_f=='father'){
		
				$res = mysql_query("select name from $tab where (gender=\"m\" AND birth<\"$momag\") and ((treno=$treno) or (treno=2)) order by name");
				$nw = mysql_num_rows($res);
				
							
				
				for($mcyc=0;$mcyc<$nw; $mcyc++){
					$papsid = mysql_result($res, $mcyc);
					$paplist.="<option>$papsid</option>";
					
				}
							
				echo "	<td> <i>Папа</i> <br>
									<select name=\"fath\" class=user> 
										<option></option>$paplist
									</select>
								</td>
						";
			if(!empty($_POST['fath']))  {$paval=$_POST['fath'];}
			
			echo '<td width=100px>
			<input type=file name="foo" value="Добавить фото"><br><b style="color: gray; font-size: 16px">Макс. р-р файла: 2Мб</b>
</td> <td><br>


<input type=submit class=knopka name="add" value="+"></td>
</tr></table></form>';	

//$_SESSION['fott']=$_FILES['fott'];

//echo 'fotna='.$_FILES[foo]['name'].'<br>';				

			
		
		
		
		
	}
	}
	}
	}
	
 else {
 
//echo 'исходное имя: '.$_FILES[foo]['name'].'<br>'; 
$ext = pathinfo($_FILES[foo]['name'], PATHINFO_EXTENSION);
//echo 'ext='.$ext.'<br>';
	/*  	СОЗДАЕМ НЕПОСРЕДСТВЕННО ЗАПРОС, ДОБАВЛЕНИЕ ДАННЫХ В БАЗУ */
/*echo 'log='.$log.'<br>';
echo 'pas='.$pas.'<br>';
echo 'tab='.$tab.'<br>';
echo 'db='.$db.'<br>';
if ($conn) {echo 'con ok<br>';}*/
		$newname=$_POST['name'];
		//echo 'newname='.$newname.'<br>';
		//echo 'table='.$tab.'<br>';
		$sqlex="select * from $tab where name like \"$newname\" and treno=$treno";
		//echo $sqlex.'<br>';
		$proverka=mysql_query($sqlex);
		//if ($proverka) { echo 'provok';} else {echo 'no prov<br>';}
		$numres=mysql_num_rows($proverka);
		//echo 'numres='.$numres.'<br>';
		//echo "Количество строк с именем ".$newname." в базе: ".$numres."<br>";
		
		
//		$_FILES["fott"]=$_SESSION['fott'];
		//echo 'printr: ' ; print_r($_FILES);
//echo 'numre='.$numres.'<br>';		
if ($numres>0)
{
	echo '<br>---------------------<br><br><b>Запись с таким именем уже присутствует в базе</b><br><br>
	<a href="#" OnClick="history.back();"><< Назад</a><br><br>---------------------<br><br>';
	}
else {



//echo 'withoutext: '.$filename;

/*while (file_exists("images/".basename($_FILES[foo]["name"]))) {
	$filename.=$l;
	$_FILES[foo]["name"]=$filename.".".$info['extension'];
} */
$validExtensions = array('.jpg', '.jpeg', '.gif', '.png');
//	echo "допустимые расширения: "; print_r($validExtensions);
    // get extension of the uploaded file
    $fileExtension = strrchr($_FILES['foo']['name'], ".");
    // check if file Extension is on the list of allowed ones
    if (in_array($fileExtension, $validExtensions)) {
          $rus=$_FILES['foo']['name'];
  $shoname = substr($rus, 0, 21);
$trans=get_in_translate_to_en($shoname);
//echo "trans=".$trans."<br>";
$nam=$_FILES[foo]['name']; 
 $siz=$_FILES['foo']['size']; 
 //echo "si=".$siz."<br>";
 //echo "nam=".$nam."<br>";
 if ($siz==0) {
die("<br><br><center><b>Уменьшите, пожалуйста, фотографию до размера не более 2Мб </b><br><a onclick=\"javascript:history.back();\" style=\"text-decoration: underline; cursor: pointer; color: blue;\" > <br><b><<< Назад</b></a></center>"); }
		$newNamePrefix = time() . '_';
		$finam=$newNamePrefix. $trans;
		echo $finam."<br>";
		$teim=$_FILES['foo']['tmp_name'];
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
//echo 'foo='.$foo.'<br>';
//echo '$files:'; print_r($_FILES); echo '<br>';
if (!$fano) {

$fano= $_SESSION['fano'] ;
//echo "fano=".$fano."<br>";
}




$na=$_POST['name'];

$bi=$_POST['bir'];

if (!$bi) {
$sttodb='0000-00-00';
}
else{
if (ereg("^[0-9]{4}$",$bi)) {
$sttodb=$bi.'-01-01';
}
else {
$sttodb = date("Y-m-d", strtotime($bi));
}
}
$de=$_POST['end'];
if (!$de) {
$endtodb='0000-00-00';
}

else {

if (ereg("^[0-9]{1,4}$",$de)) {
$endtodb=$de.'-01-01';
}
else {
$endtodb = date("Y-m-d", strtotime($de));
	if ($endtodb=='1969-12-31'){
		$endtodb=null;
	}
}
}
$db = $_SESSION['db'];
	//echo $na.'<br>'.$bi.'<br>'.$de.'<br>';
	echo '<br>';

$maval=$_POST["moth"];
	if ($maval==null){$maval=1;}
$paval=$_POST["fath"];
	if ($paval==null){$paval=2;}
if ($_POST['gender']==' Male ') {$gende='m';} 
				if ($_POST['gender']==' Female ') {$gende='f';} 
				if ($_POST['gender']==null) {$gende='?';} 
				//echo "gen=".$gende."<br>";
if (!$foo) {
//echo ' : '.$_FILES[foo]["error"].'<br>';
//echo '$files_witherr:'; print_r($_FILES); echo '<br>';
//echo "gee=".$gende."<br>";
if ($gende=='f') {
$foo='001.jpg';
} 
else {$foo='000.jpg';}
}
	 // начинаем создавать запрос, перебираем все поля таблицы
	//	echo 'ma:'.$maval.'<br>';
	//	echo 'pa:'.$paval.'<br>';
		$mavalid=mysql_result(mysql_query("select id from $tab where name=\"$maval\""),0);
		if (!$mavalid) {
		$mavalid='1';
		}
		$pavalid=mysql_result(mysql_query("select id from $tab where name=\"$paval\""), 0);
		if (!$pavalid) {
		$pavalid='2';
		}
	//	echo 'mavalid='.$mavalid.'<br>';
	//	echo 'pavalid='.$pavalid.'<br>';
		if ($na==null) {
		exit("<br><br>----------------<br><br><b>Заполните имя</b><br><br><br><br><a href=\"#\" OnClick=\"history.back();\"><< Назад</a><br><br>----------------<br>");
		}
		if (($_POST['end']!=='')&&($de<$bi))
		{
		exit("<br><br>----------------<br><br><b>Дата смерти не может быть раньше даты рождения</b><br><br><a href=\"#\" OnClick=\"history.back();\"><< Назад</a><br><br>----------------<br>");
		}
		
$addd="INSERT INTO $tab VALUES (null, \"$treno\", \"$foo\", null, \"$na\",\"$gende\",\"$sttodb\",\"$endtodb\",\"$mavalid\",\"$pavalid\", \"\")";
		

//	echo $addd."<br>";
//		echo "Отправляем запрос...<br>";
/*echo ' <table cellpadding="30" 
border="0" cellspacing="0"
width=10% height=10% border=0>
<tr valign=middle>
<td align=center><img src="waitt.gif" width=150px ></td>
</tr>
</table> ';*/
		$addnewpers=mysql_query($addd,$conn);
		if ($addnewpers) {
		echo '<center>Родственник добавлен...<br></center>';
/*echo '<script type="text/javascript">'; 
echo "window.location.href=\"plus.php?treno=$treno\";"; 
echo '</script>';	*/	
echo '<script type="text/javascript">'; 
echo "window.location.href=\"plus.php?treno=$treno\";"; 
echo '</script>';
		} 
		else {echo 'не удалось добавить...';}
	 
	}

	

}


//}

	echo "<br><br><br><br>";

/* МОДУЛЬ ОТОБРАЖЕНИЯ ТАБЛИЦЫ MYSQL
	сначала делаем то же, что и раньше: устанавливаем
соединение, выбираем базу и получаем список и число полей в таблице Book */

$conn=mysql_connect("$serv","$log","$pas") or die("Ошибка при подключении");// устанавливаем
             // соединение
			 mysql_query("SET NAMES $enco");
$db = $_SESSION['db'];
//$tab = 'people';
mysql_select_db($db); // выбираем базу данных

$list_f = mysql_list_fields($db,$tab);
           // получаем список полей в таблице
$n = mysql_num_fields($list_f); // число строк в результате
           // предыдущего запроса (т.е. сколько всего
           // полей в таблице Book)

for($j=0;$j<$n; $j++){

    $names[] = mysql_field_name ($list_f,$j);

}

$sqlshow = "SELECT * FROM $tab where treno=$treno order by $order"; // создаем SQL запрос
//echo $sqlshow."<br>";
$qshow = mysql_query($sqlshow,$conn) or die(); // отправляем
           // запрос на сервер
$ns = mysql_num_rows($qshow); // получаем число строк результата
$ids=mysql_query("select id from $tab  where treno=$treno order by $order",$conn) or die(); 
$quanids = mysql_num_rows($ids);
$_SESSION['ids']=$quanids;
$result = mysql_query("SELECT MAX(`id`) FROM $tab");

$findmax=mysql_query("SELECT MAX(`id`) FROM $tab");

$maxx=mysql_result($findmax, 0);
$_SESSION['maxid']=$maxx;

//echo 'qqq='.$quanids.'<br>';
for ($i=0; $i<$quanids; $i++) {
$valuid = mysql_result($ids,$i); 
//echo 'valuid='.$valuid.'<br>';
$va[]=$valuid;
//echo 'table position: '.($i+1).'.  Value: '.$valuid.'<br>';

} //рисуем HTML-таблицу
//print_r($va); echo "<br><br><br><br><br>";

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

/*echo 'INstatus='.$_SESSION['in'].'<br>';
if (isset($_POST['enter'])) {
echo 'entered<br>';
}

elseif (isset($_POST['exitt'])) {
echo 'exit<br>';
}
else {
echo 'nothing<br>';
}
*/


	
		
		

		
		

		
	
	echo '<br><br><br><br>';
	

	

 echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
 

//echo '<p style="color:blue" href="/tablespecialcell.php">Cделать pdf </p>';
//echo "<a <p style="color:blue" href='/tcpdfstudy.php'><b>TCPDF</b></a>";
//echo "<a <p style='color:blue' href='http://mysqlwith/pdfout.php'><b>Сделать PDF</b></a>	";



//else {
//Header("Location: authorize.php");
//}
 
	
	





?>