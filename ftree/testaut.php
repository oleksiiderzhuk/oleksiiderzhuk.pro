<html>
<head>
<meta charset="utf-8">
<title>Выбор родового дерева</title>
	
<script type="text/javascript">


function viewtree(treno)
{
var viewtree = encodeURIComponent(treno);
window.location.href = 'ftree.php?treno='+treno;
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
<?php
session_start();
error_reporting(0);
	 unset($_SESSION['pastree']);


//	echo "Авторизуемся...";
echo '<table bgcolor="antiquewhite" cellpadding="0" 
border="0" cellspacing="0" bordercolor="white" 
width=100% height=100% border=0>
<tr>
<td> <a href=index.php > 

<<< Выйти из леса родовых деревьев</a>

<br><br></td></tr><tr><td>';	
if (isset($_SESSION['record'])) {
unset($_SESSION['record']);
}
$_SESSION['serv']='mysql1.000webhost.com';
$_SESSION['db']='a3552103_ftree';
$_SESSION['enco']='utf8';
$_SESSION['log']='a3552103_root';
$_SESSION['pas']='Qq1221';
$_SESSION['tab']='people';
if ($_POST['fakey']) {
$_SESSION['fakey']= $_POST['fakey'] ;
}

$fakey=$_SESSION['fakey'];
//echo "key=".$fakey."<br>";
if ($fakey==null) {
Header("Location: index.php");
}
else {


$enco=$_SESSION['enco'];

		//$_SESSION['tab']='people';
		//$_SESSION['isdouble']=$_POST['isdouble'];
		$serv=$_SESSION['serv'];
		$log=$_SESSION['log'];
		$pas=$_SESSION['pas'];
		$db=$_SESSION['db'];
		$tab=$_SESSION['tab'];
		


		$con = @mysql_connect("$serv", "$log","$pas");
			if($con) {
				mysql_query("SET NAMES $enco");
				//echo 'connok<br>';
		//		$_SESSION['aut']=1;
				//echo "$db<br>";
				$uschdb = "use $db";
				//echo $uschdb.'<br>';
				//$_SESSION['uschdb']=$uschdb;
				$uschdbq = mysql_query($uschdb,$con);
	
				}

				$query="select * from `names` where `keyname` like \"%$fakey%\"";
			//	echo "searchqu=".$query."<br>";
				$mysqq=mysql_query($query);
				if ($mysqq) {
					
				
				
				$result=mysql_fetch_array($mysqq);
		//			if ($result){echo '112q';} else {echo 'no112';}
				}
				
				if ($result) {
		//			echo 'result<br>';
				echo "<form method=\"post\" action=\"authorize.php\"><input name=\"fakey\" class=user type=\"text\" size=\"30\" placeholder=\"$fakey\">&nbsp;&nbsp;&nbsp;<input class=knopka type=\"submit\" name=\"usesub\" value=\"Найти родовое дерево\"></form>";
				echo "<a style=\"color: black\"><i>Найдены деревья:</i></a><br>";
				$resassoc=mysql_query($query);
				$iii=0;
				$keyn=0;
				while ($row = mysql_fetch_assoc($resassoc)) {
// 	echo "===".$row['id']."<br>";
	$ro[]=$row['id'];
	$rona[]=$row['keyname'];
    echo "<a style=\"color: blue; text-decoration: underline; cursor: pointer;\" onclick=\"viewtree($ro[$iii])\"><b>".$row['keyname'].'</b></a><br>';
	$iii+=1;
	$keyn+=1;
    
}
				}
				else {
			//		echo 'noresult';
				$keyn=0;
				}
//echo "счетчик: ".$keyn."<br>";
				$andtreno="";
for ($i=0; $i<count($ro); $i++) {
$andtreno.=" AND `treno` NOT LIKE $ro[$i]";
}
$quer="select * from `people` where lower(`name`) like lower(\"%$fakey%\") $andtreno";
//echo $quer;
$resss=mysql_result((mysql_query($quer)), 0);
				if ($resss) {
	
//echo "Найдены еще фамилии:<br>";
echo "<br><br>";
$querr=mysql_query($quer,$con) or die();
$nss=mysql_num_rows($querr); 
//echo "nss=".$nss."<br>";
$resasso=mysql_query($quer);
echo "<br> <table bgcolor=\"antiquewhite\" cellpadding=\"3\" 
   border=\"0\" cellspacing=\"0\" bordercolor=\"white\" 
   width=40% height=5% align=center>
   	";		
$roww = mysql_fetch_assoc($resasso);

//print_r($resasso); echo "<br>";
$nsoth=mysql_num_rows($resasso); 
//echo $nsoth."<br>";
if ($nsoth>1) {
echo "<a style=\"color: black\"><H3>Похожие фамилии найдены на листьях деревьев:</H3></a>";


$tr=0;
//$nam=array();
//$nam[][]=array();
$trenoo=0;
for ($i=0; $i<$nsoth; $i++) {
//	echo $i."<br>";
	$row = mysql_fetch_assoc($resasso);
	$treno=$row['treno'];
	//echo "$treno<br>";
	if ($treno==$trenoo) {
	
	echo ', ';
	}

	if ($treno!==$trenoo) {
	$tr+=1;
	echo "<br>";
	$query="select keyname from names where id=\"$treno\"";
	$result=mysql_result((mysql_query($query)), 0);
	echo "<a style=\"color: blue; text-decoration: underline; text-align: center; cursor: pointer;\" onclick=\"viewtree($treno)\"><b>".$result."</b></a>";
	
	//echo $result.': ';
	}
	
	echo '&nbsp;'.$row['name'];
	

	
	
	$trenoo=$row['treno'];
	

}

}
 

 echo "
   </table>";  



   }

//echo "счетчик: ".$keyn."<br>";
   if ($keyn==0) {
echo "Дерева <a style=\"color: blue\"><b>$fakey</b></a> не найдено в лесу родовых дереьев...<br>";
echo "<br><form method=\"post\" action=\"authorize.php\"><input name=\"fakey\" class=user type=\"text\" size=\"30\" placeholder=\"Фамилия\">&nbsp;&nbsp;&nbsp;<input class=knopka type=\"submit\" name=\"usesub\" value=\"Найти родовое дерево\"></form>";
$plhold=$fakey;
		 $new=1;
}
else {
//echo "что-то не так...<br>";
}

}
$plhold= $_SESSION['new']; 
if (!isset($_POST['create'])) {
echo "<br><br><br><br><br><H3>Вырастите новое семейное дерево<H3>";
}
else {
if ( $_POST['create']=='' ) {
$newtree=$fakey;
}
else {
$newtree= $_POST['create'];
}
$sho=0;
$istherequ="SELECT id FROM names WHERE keyname='$newtree'";
$isthere=mysql_result((mysql_query($istherequ)), 0);
if ($isthere) {
echo "<br><br>Дерево <a style=\"color: blue; text-decoration: underline; text-align: center; cursor: pointer;\" onclick=\"viewtree($isthere)\"><b>".$newtree."</b></a> уже растет в лесу<br>";

}
else {
$iswind=0;
/*echo "<br><br>Садим дерево <a style=\"color: blue; text-decoration: underline; text-align: center; cursor: pointer;\" onclick=\"viewtree($isthere)\"><b>".$newtree."</b></a> ...<br>";*/
$querr="INSERT INTO `names` VALUES (NULL, $newtree, '')";
//echo "addqu=".$querr."<br>";
$result = mysql_query ( $querr );
if ($result) {
$whatnewidqu="SELECT id FROM `names` WHERE `keyname`=$newtree";
//	echo $whatnewidqu.'<br>';
$newid=mysql_result((mysql_query($whatnewidqu)), 0);
//	echo 'qqq: '.$newid."<br>";
echo "<br><br>Новое дерево
<a style=\"color: blue; text-decoration: underline; text-align: center; cursor: pointer;\" onclick=\"viewtree($newid)\"><b>".$newtree."</b></a>
 только что было посажено в лесу родовых деревьев :)<br>";
}
}
}
//<?php
$iswind=1;
if ($iswind==1) {
	if ($new==1){
		echo "<br><form method=\"post\" ><input class=\"user\" name=\"create\" type=\"text\" size=\"30\" 
placeholder=\"$fakey\">&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=knopka name=\"cresub\" 
value=\"Посадить дерево\"></form>";
	}
	else {
echo "<br><br><form method=\"post\" ><input class=\"user\" name=\"create\" type=\"text\" size=\"30\" 
placeholder=\"Новое дерево\">&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=knopka name=\"cresub\" 
value=\"Посадить дерево\"></form>";
		}
}


echo "</td>
</tr>
</table>";
Header("Location: plus.php?treno=4");
	?>