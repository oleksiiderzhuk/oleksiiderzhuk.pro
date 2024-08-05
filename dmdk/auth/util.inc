<?php
//-------------------------------------------------------------------------------------------------------------------------------
// ClrArrNull() : удаляет из массива все пустые элементы (могут получиться при разборе exclude(',',$str) если в $str будет ",,,,"
//   $delDouble - параметр, удалять ли дополнительно еще и дубли в массиве
//-------------------------------------------------------------------------------------------------------------------------------
function ClrArrNull($arr,$delDouble=true) {
  $arr2=array();
  for($i=0;$i<sizeof($arr);$i++) {
    if($arr[$i]!='') {
      if($delDouble && in_array($arr[$i],$arr2)) continue;
      $arr2[]=$arr[$i];
    }
  }
return $arr2;  
}
//-----------------------------------------------------------------------
// odbcErrAdm(): Получить ошибку клиента Sybase (комбинация odbc_errormsg+php_errormsg)- только админам
//-----------------------------------------------------------------------
function odbcErrAdm($err='') {
    global $php_errormsg, $sa__, $sv;
    if($err=='') return ((!empty($sa__) && ($sv['login']==$sa__ || (is_array($sa__) && in_array($sv['login'],$sa__))))? (@odbc_errormsg()." :: ".$php_errormsg) : '' );
    else         return ((!empty($sa__) && ($sv['login']==$sa__ || (is_array($sa__) && in_array($sv['login'],$sa__))))? trim($err) : '' );
}
//-----------------------------------------------------------------------
// SyErrAdm(): Получить ошибку клиента Sybase (комбинация sybase+php_errormsg)- только админам
//-----------------------------------------------------------------------
function SyErrAdm($err='') {
  global $php_errormsg, $sa__, $sv;
  if($err=='') return ((!empty($sa__) && ($sv['login']==$sa__ || (is_array($sa__) && in_array($sv['login'],$sa__))))? (@sybase_get_last_message()." :: ".$php_errormsg) : '' );
  else         return ((!empty($sa__) && ($sv['login']==$sa__ || (is_array($sa__) && in_array($sv['login'],$sa__))))? trim($err) : '' );
}
//-----------------------------------------------------------------------
// SyErr(): Получить ошибку клиента Sybase (комбинация sybase+php_errormsg)
//-----------------------------------------------------------------------
function SyErr() {
  global $php_errormsg;
  return sybase_get_last_message()." :: ".$php_errormsg;
}
//-----------------------------------------------------------------------------------------------
// trim_n(): Аналог стандартного trim() + убирает переводы строк (\n,\r)
//-----------------------------------------------------------------------------------------------
function trim_n($str) {
  return trim(str_replace("\n","",str_replace("\r","",$str)));
}
//-----------------------------------------------------------------------------------------------
// urlcode(): надстройка над htmlentities() - кодирование & в &amp; 
// важно при формировании ссылки на статистику (fix бока с &prod и тд) для вставки в презентацию
// $cp_in - кодировка URL-a (default - CP1251)
//-----------------------------------------------------------------------------------------------
function urlcode($url,$cp_in='CP1251') {
  return htmlentities($url,null,$cp_in);
}
//-----------------------------------------------------------------------------------------------
// VarDump1(): var_dump с конвертацией UTF-8 -> CP866
//-----------------------------------------------------------------------------------------------
function VarDump1($var) {
  print GetVarDump($var,"UTF-8","CP866");
}
//-----------------------------------------------------------------------------------------------
// GetVarDump(): сохранение результата var_dump в строковую переменную (с возможностью конвертации)
// Возврат: строка с содержимым var_dump($var)
//-----------------------------------------------------------------------------------------------
function GetVarDump($var,$cp_in='',$cp_out='') {
  ob_start();var_dump($var);$str=ob_get_contents();ob_end_clean();
  return ($cp_in==''?$str:iconv($cp_in,$cp_out."//IGNORE",$str));
}
//-----------------------------------------------------------------------------------------------
// IsAuth(): Проверка, авторизовался ли сотрудник на сайте
//           Если указан массив логинов $arr - то проверка, входит ли авторизованный в этот массив
// Возврат: true - авторизован и допуск есть, false - нет прав
//-----------------------------------------------------------------------------------------------
function IsAuth($arr=-1) {
  if(!isset($_SESSION["sv"]["auth"]) || !$_SESSION["sv"]["auth"] || $_SESSION["sv"]["auth"]=='') return false;
  if(is_array($arr) && !in_array($_SESSION["sv"]["login"],$arr,true)) return false;
  return true;
}
//-----------------------------------------------------------------------------------------------
// ReadFileTxt(): Вычитать простой txt-файл в массив (пустые строки, пробелы в начале/конце строк прибиваются)
//   Если указан idx>=0 - то вернуть только строку с указанным № (с 0, пустые строки не считаются)
//   Если указан idx=-1 - то вычитать весь файл целиком (все строки) в строку возврата
//  Возврат: массив при idx=null, строка при idx=NN,-1
//-----------------------------------------------------------------------------------------------
function ReadFileTxt($fname,$idx=null) {
  if(!is_null($idx) && $idx==-1) return trim(file_get_contents($fname));
  if(($arr=@file($fname))==false) $arr=array();
  $arr2=array();
  for($i=0;$i<sizeof($arr);$i++) if(trim($arr[$i])!='') $arr2[]=trim($arr[$i]);
  if(sizeof($arr2)==0) return (is_null($idx)?array(""):"");
  // Возврат для массива
  if(is_null($idx)) return $arr2;
  // Возврат для строки
  return (sizeof($arr2)>$idx?$arr2[$idx]:"");
}
//---------------------------------------------------------------------------------------------
// GetVerName(): Получить имя файла со свободным № версии файла
//  Версия файла - 3 цифры после имени через ~, пример: BOM_PSS_PBDO_20120521~199.PPT (199 версия)
//  $dir - каталог поиска файлов (''-текущий)
//  $fname - имя файла-шаблона (без версии) пример: BOM_PSS_PBDO_20120521.PPT
//---------------------------------------------------------------------------------------------
function GetVerName($dir='',$fname) {
$etalon=GetFileName($fname);   // Шаблон файла (без расширения)
$ext=GetFileExt($fname);       // Расширение
if($dir=='') $dir='.';
$chak=$ii=0;
if(($dh=@opendir($dir))!==false) {
  while ( $fn = @readdir($dh) ) { 
    if(is_file($dir.'/'.$fn) && strlen($fn)>=strlen($etalon) && GetFileExt($fn)==$ext &&
       substr($fn,0,strlen($etalon))==$etalon) {
      $ii=(int)substr($fn,strlen($etalon)+1,3);
      if($ii>$chak) $chak=$ii;
      //print "etalon=$etalon, fn=$fn, ii=$ii, chak=$chak\n";
    }
  }
  @closedir($dh);
}
$chak++;
return sprintf("$etalon~%03d.$ext",($chak>999?999:$chak));
} 
//-----------------------------------------------------------------------
// copy2(): Аналог copy (копирование файла) с доп.раздачей прав
//  $source - исходный файл
//  $target - файл назначения
//  $mode   - права доступа, если не заданы - то берутся права с файла source
// Возврат: true - скопирован, false - ошибка
//-----------------------------------------------------------------------
function copy2($source,$target,$mode=null) {
global $err__;
  if(!is_file($source)) return false;
  if($mode==null) $mode=octdec(substr(sprintf('%o',@fileperms($source)),-4));
  if(!@copy($source, $target)) { if(isset($err__)) {$err__='Error: '.$php_errormsg;} return false; }
  @chmod($target,$mode);
  return true;
}
//-----------------------------------------------------------------------
// MkDir2(): Аналог mkdir (создание каталога) с доп.раздачей прав
// Возврат: true - каталог создан, false - ошибка
//-----------------------------------------------------------------------
function MkDir2($dir,$mode=0777) {
global $err__;
  if(!@mkdir($dir,$mode)) { if(isset($err__)) {$err__='Error: '.$php_errormsg;} return false; }
  @chmod($dir,$mode);
  return true;
}
//-----------------------------------------------------------------------
// DelSymbol(): Удаление из входящей строки всех не цифровых символов
//   $str    - входящая строка
// Возврат: строка только из цифровых символов или ''
//-----------------------------------------------------------------------
function DelSymbol($str) {
  $str=trim($str); $res='';
  for($i=0;$i<strlen($str);$i++) {
    $ch=substr($str,$i,1);
    if($ch>='0' && $ch<='9') $res.=$ch;
  }
  return $res;
}
//-----------------------------------------------------------------------
// getCurl(): Выполнить GET или POST запрос
//  $aCurl   - массив CURL-параметров
//  $timeout_conn - время ожидания коннекта в секундах (не указан - то дефолт или указанный в $aCurl)
//  $timeout_exec - время выполнения запроса в секундах (не указан - то дефолт или указанный в $aCurl)
// Возврат: array: $error  - ошибка CURL (0 - OK)
//                 $errmsg - сообщение об ошибке
//                 $info   - массив cлуж.инфор.CURLa ($aHr["http_code"],$aHr["header_size"],...)
//                 $data   - возвращенный поток с данными
//-----------------------------------------------------------------------
function getCurl($aCurl, $timeout_conn=null, $timeout_exec=null) {
  // Дописывание массива $aCurl если переданы таймауты
  if(isset($timeout_conn)) $aCurl[CURLOPT_CONNECTTIMEOUT]=$timeout_conn;
  if(isset($timeout_exec)) $aCurl[CURLOPT_TIMEOUT]=$timeout_exec;
  // создание нового ресурса CURL
  $ch = @curl_init();
  // Подстраховка ошибки 
  // curl_setopt_array(): CURLOPT_SSL_VERIFYHOST with value 1 is deprecated and will be removed as of libcurl 7.28.1. It is recommended to use value 2 instead in /opt/www/dpd.test.it.loc/INCLUDE/util.inc on line 166
  if (isset($aCurl[CURLOPT_SSL_VERIFYHOST]) && $aCurl[CURLOPT_SSL_VERIFYHOST]==1){
      $curlVersion = curl_version();
      if (version_compare($curlVersion["version"], '7.19.7') >= 0) {
          $aCurl[CURLOPT_SSL_VERIFYHOST]=2;
      }else{
          try{
              if ($f=@fopen('curl.txt','a+')){
                  @fwrite($f, json_encode($curlVersion));
                  @fclose($f);
              }
          }catch(Exception $ex){
          }
      }
  }
  // Установка параметров CURL  
  curl_setopt_array($ch, $aCurl);
  // Выполнение CURL
  $data = @curl_exec($ch);
  // Получаем результат выполнения
  $curl_errno = curl_errno($ch);
  $curl_error = curl_error($ch);
  $aInfo = @curl_getinfo($ch);
  // Закрываем ресурс
  @curl_close($ch);
  // Возврат результата
  return array("error"=>$curl_errno, "errmsg"=>$curl_error, "info"=>$aInfo, "data"=>$data);
}
//-------------------------------------------------------------------------->
// getFeedCurl(): Получение XML-потока по адресу 
//  $aCurl    - массив параметров запроса
//  $timeout  - время ожидания отклика (в секундах)
//  [rettype] - 'xml' (default) - вернуть объект XML, 'json' - ассоц.массив по JSON
//  [$br]     - формат перевода строки в сообщении об ошибке (default = "\n")
// Возврат: OBJ    - если успешно получен XML-поток
//          String - строка с ошибкой при сбое 
// Пример анализа возврата:
// if(is_object($dom=getFeedCurl($aCurl,$FeedTime))===false) exit("ERROR !".$dom);
//-------------------------------------------------------------------------->
function getFeedCurl($aCurl,$timeout,$rettype="xml",$br="\n") {
  // Запрос на сервис
  $aRes=getCurl($aCurl,$timeout,$timeout);
  // Анализ полученной информации
  if($aRes["error"]!=0) return "getFeedCurl(): CURL Error: ".$aRes["error"].$br.$aRes["errmsg"];
  if($aRes["info"]["http_code"]!='200') return "getFeedCurl(): HTTP Error: ".$aRes["info"]["http_code"].$br.$aRes["data"];
  // Ошибок нет, разбираем полученный XML в объект
  if(strtolower($rettype=="json")) {
    // Вернуть ассоц.массив по JSON-потоку
    $res = json_decode($aRes["data"],true);
    if (isset($res['error'])) return "getFeedCurl(): ".$res['error']; 
  } else {
    if (strpos(substr($aRes["data"],0,128),'<error>')!==false){
      $res = XMLtoArray($aRes["data"]);
      return "getFeedCurl(): ".(isset($res['ERROR'])?$res['ERROR']:"Tag Error found !"); 
    }
    // Вернуть объект по XML-потоку
    $res = simplexml_load_string($aRes["data"]);
  }
  return $res;
}
//-------------------------------------------------------------------------->
// getFeed(): Получение XML-потока по адресу
//  $url     - адрес
//  $timeout - время ожидания отклика (в секундах)
// Возврат: OBJ    - если успешно получен XML-поток
//          String - строка с ошибкой при сбое 
// Пример анализа возврата:
// if(is_object($dom)===false) return ResReturnFab(0,$repcode,$tobo,"Ошибка получения данных (getFeed) !\n\n".$lnk_."\n\n".$dom);
//-------------------------------------------------------------------------->
function getFeed($url,$timeout,$submit='',$rettype="xml",$errview=true,$httpheader='',$iterr=false) {
//function getFeedPromin($url,$timeout,$submit='',$br="\n") {
global $aResFeed__;
global $ErrMsg__;
  $aCurl=array(CURLOPT_URL            => $url,
               CURLOPT_FOLLOWLOCATION => 1,
               CURLOPT_RETURNTRANSFER => 1,
               CURLOPT_HTTPPROXYTUNNEL => FALSE, // исправления 2013-07-17
               CURLOPT_PROXY => FALSE, // исправления 2013-07-17
               CURLOPT_SSL_VERIFYPEER => false,
               CURLOPT_SSL_VERIFYHOST => false);
  // POST-передача             
  if($submit!='') {
    $aCurl[CURLOPT_POST]=1;
    $aCurl[CURLOPT_POSTFIELDS]=$submit;
  }
  // Дополнительные переменные в HTTP-заголовке
  if($httpheader!='') {
    $aCurl[CURLOPT_HTTPHEADER]=$httpheader;
  }
  // GET-запрос на сервис
  $aRes=getCurl($aCurl,$timeout,$timeout);
  // Анализ полученной информации
  // Ошибка CURL
  if($aRes["error"]!=0) {
    if(isset($ErrMsg__)) $ErrMsg__=$aRes["errmsg"];
    return "getFeed(): CURL Error: ".$aRes["error"]."\n".$aRes["errmsg"];
  }
  // Ошибка HTTP Error
  if($aRes["info"]["http_code"]!='200') {
    if($iterr && $aRes["info"]["http_code"]>='230' && $aRes["info"]["http_code"]<='240') {
      // Ошибка сформированная нашим ИТ - при флаге iterr продолжаем обработку xml-а возврата
    } else {
      if(isset($ErrMsg__)) $ErrMsg__=$aRes["data"];
      return "getFeed(): HTTP Error: ".$aRes["info"]["http_code"].($errview?"\n".$aRes["data"]:'');
    }
  }
  // Ошибок нет, разбираем полученный XML в объект
  if(strtolower($rettype=="json")) {
    // Вернуть ассоц.массив по JSON-потоку
    //$res = json_decode(str_replace('</br>','',$aRes["data"]),true);
    $res = json_decode($aRes["data"],true);
    if(isset($res['error']['message'])) {
      if(isset($ErrMsg__)) $ErrMsg__=$res['error']['message'];
      return "getFeed(): "."HTTP: ".$aRes["info"]["http_code"].", ".$res['error']['message']; 
    }
    elseif(isset($res['error'])) {
      if(isset($ErrMsg__)) $ErrMsg__=$res['error'];
      return "getFeed(): "."HTTP: ".$aRes["info"]["http_code"].", ".$res['error']; 
    }
  } else {
    if(($pos=strpos(substr($aRes["data"],0,128),'<error>'))!==false) {
      $aaa=trim(substr($aRes["data"],0,128));
      $res = XMLtoArray($aRes["data"]);
      if(isset($ErrMsg__)) $ErrMsg__=$aaa;
      return "getFeed(): ".(isset($res['ERROR'])?$res['ERROR']:"Tag Error found ! ($aaa)"); 
    }
    // Вернуть объект по XML-потоку
    $res = simplexml_load_string($aRes["data"]);
  }
  if(isset($aResFeed__)) $aResFeed__=$aRes;
  return $res;
}
//-------------------------------------------------------------------------->
// getFeedPromin(): Получение XML-потока по адресу (адаптация под SSL сервисов Проминя)
//  $url      - адрес
//  $timeout  - время ожидания отклика (в секундах)
//  [$submit] - отправка данных POST-ом (default - нет)
//  [$br]     - формат перевода строки в сообщении об ошибке (default = "\n")
// Возврат: OBJ    - если успешно получен XML-поток
//          String - строка с ошибкой при сбое 
// Пример анализа возврата:
// if(is_object($dom=$this->getFeed($this->url,$this->FeedTime,$xml))===false) exit("ERROR !".$dom);
//-------------------------------------------------------------------------->
function getFeedPromin($url,$timeout,$submit='',$br="\n") {
  $aCurl=array(CURLOPT_URL            => $url,
               CURLOPT_FOLLOWLOCATION => 1,
               CURLOPT_RETURNTRANSFER => 1,
               CURLOPT_SSL_VERIFYPEER => false,
               CURLOPT_SSL_VERIFYHOST => 1,
               CURLOPT_HTTPHEADER     => array("Content-Type: text/xml;charset=UTF-8") 
               );
  if($submit!='') {
    $aCurl[CURLOPT_POST]=1;
    $aCurl[CURLOPT_POSTFIELDS]=$submit;
  }
  // GET-запрос на сервис
  $aRes=getCurl($aCurl,$timeout,$timeout);
  // Анализ полученной информации
  if($aRes["error"]!=0) return "getFeedPromin(): CURL Error: ".$aRes["error"].$br.$aRes["errmsg"];
  if($aRes["info"]["http_code"]!='200') return "getFeedPromin(): HTTP Error: ".$aRes["info"]["http_code"].$br.$aRes["data"];
  if(strpos(substr($aRes["data"],0,128),'<error>')!==false) {
    $res = XMLtoArray($aRes["data"]);
    return "getFeedPromin(): ".(isset($res['ERROR'])?$res['ERROR']:"Tag Error found !"); 
  }
  // Ошибок нет, разбираем полученный XML в объект
  $xml = simplexml_load_string($aRes["data"]);
  return $xml;
}
/**
 * Convert XML to an Array
 *
 * @param string  $XML
 * @return array
 */
function XMLtoArray($XML)
{
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $XML, $vals);
    xml_parser_free($xml_parser);
    // wyznaczamy tablice z powtarzajacymi sie tagami na tym samym poziomie
    $_tmp='';
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_level!=1 && $x_type == 'close') {
            if (isset($multi_key[$x_tag][$x_level]))
                $multi_key[$x_tag][$x_level]=1;
            else
                $multi_key[$x_tag][$x_level]=0;
        }
        if ($x_level!=1 && $x_type == 'complete') {
            if ($_tmp==$x_tag)
                $multi_key[$x_tag][$x_level]=1;
            $_tmp=$x_tag;
        }
    }
    // jedziemy po tablicy
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_type == 'open')
            $level[$x_level] = $x_tag;
        $start_level = 1;
        $php_stmt = '$xml_array';
        if ($x_type=='close' && $x_level!=1)
            $multi_key[$x_tag][$x_level]++;
        while ($start_level < $x_level) {
            $php_stmt .= '[$level['.$start_level.']]';
            if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
            $start_level++;
        }
        $add='';
        if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
            if (!isset($multi_key2[$x_tag][$x_level]))
                $multi_key2[$x_tag][$x_level]=0;
            else
                $multi_key2[$x_tag][$x_level]++;
            $add='['.$multi_key2[$x_tag][$x_level].']';
        }
        if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
            if ($x_type == 'open')
                $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
            else
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
            eval($php_stmt_main);
        }
        if (array_key_exists('attributes', $xml_elem)) {
            if (isset($xml_elem['value'])) {
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            foreach ($xml_elem['attributes'] as $key=>$value) {
                $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                eval($php_stmt_att);
            }
        }
    }
    return $xml_array;
}
//-----------------------------------------------------------------------
// ParamEncode(): Заворачивание переменных (напр.многомерный массив) 
//                для передачи через GET-строку
//   $param - [mix] - переменная
//   Возврат: строка для подстановки в GET-запрос
//-----------------------------------------------------------------------
function ParamEncode($param) {
  return urlencode(serialize($param));
}
//-----------------------------------------------------------------------
// ParamDecode(): Разворачивание переменных (напр.многомерный массив) 
//                после передачи через GET-строку с помощью ParamEncode()
//   $param - строка GET-запроса
//   Возврат: [mix] - переменная
//-----------------------------------------------------------------------
function ParamDecode($param) {
  return unserialize(str_replace('\\','',urldecode($param)));
}
//-----------------------------------------------------------------------
// Strbr(): Разбивка длинной строки на подстроки (по символам-разделителям)
//          с max строкой в соотв. с frame
//   $str    - начальная строка
//   $frame  - max длина подстроки
//  [$term]  - разделитель на подстроки
//  [direct] - флаг (true - принудительно разбивать длинные блоки, даже если нет разделителей)
//  [$aRazd] - массив символов-разделителей для подстрок
//-----------------------------------------------------------------------
function Strbr($str,$frame=13,$term="\n",$direct=false,$aRazd=array(' ','-','.',',')) {
  // Делаем дробный массив по символам-разделителям
  $str=trim($str); $aStr=array(); $idx=0; 
  for($i=0;$i<strlen($str);$i++) {
    //if(inlist(substr($str,$i,1),' ','-','+','.',',')) {
    if(in_array(substr($str,$i,1),$aRazd)) {
      $s=substr($str,$idx,($i+1-$idx));
      if(trim($s)!='') $aStr[]=$s;
      $idx=($i+1);
    }
  }
  if($idx<strlen($str)) $aStr[]=substr($str,$idx);

  // Крупные блоки разбиваю принудительно
  if($direct) {
    //var_dump($aStr);
    $aStr2=array();
    for($i=0;$i<sizeof($aStr);$i++) {
      if(strlen($aStr[$i])>$frame) {
        // Элемент слишком длинный, надо разбивать на блоки
        $nn=0; $str=$aStr[$i];
        while ( strlen($str)>$frame ) {
          $aStr2[]=substr($aStr[$i],$nn,$frame);
          $nn+=$frame;
          $str=substr($aStr[$i],$nn);
        }
        // Добираю последний элемент
        if(strlen($str)>0) $aStr2[]=$str;
      } else {
        // Элемент помещается в заданный размер, берем как есть
        $aStr2[]=$aStr[$i];
      }
    }
    $aStr=$aStr2;
  }
  //var_dump($aStr);
  // Собираем итоговый массив с указанным frame
  $aRes=array(); //'';
  for($i=0;$i<sizeof($aStr);$i++) {
    if(strlen($aStr[$i])>=$frame) $aRes[]=$aStr[$i];
    else {
      if( (sizeof($aRes)>0) && 
         (strlen($aRes[sizeof($aRes)-1])+
          strlen($aStr[$i])) <= $frame ) {
        //$aRes[sizeof($aRes)-1].=" ".$aStr[$i];
        $aRes[sizeof($aRes)-1].=$aStr[$i];
      } else {
        $aRes[]=$aStr[$i];
      }
    }
  }
  $res='';
  //var_dump($aRes);
  for($i=0;$i<sizeof($aRes);$i++) $res.=($i>0?$term:"").$aRes[$i];
  return $res;
}
//--------------------------------------------------------------------------
// isEmpty(): Проверка на NULL,пусто,пробелы
//--------------------------------------------------------------------------
function isEmpty($str) {
if($str==null || trim($str)=='') return true;
return false;
}
//--------------------------------------------------------------------------
// FioShort(): Получить из полного ФИО укороченный (Ф И.О.)
//--------------------------------------------------------------------------
function FioShort($fio,$term=" ") {
$aFio=explode($term,$fio);
for($i=0;$i<sizeof($aFio);$i++) $aFio[$i]=trim($aFio[$i]);
$ff='';
if(strlen($aFio[0])) $ff.=strtoupper(substr($aFio[0],0,1)).strtolower(substr($aFio[0],1))." ";
if(strlen($aFio[1])) $ff.=strtoupper(substr($aFio[1],0,1)).".";
if(strlen($aFio[2])) $ff.=strtoupper(substr($aFio[2],0,1)).".";
return $ff;
}
//--------------------------------------------------------------------------
// ChkLnk(): Проверка, является ли ссылкой переданная строка 
//--------------------------------------------------------------------------
function ChkLnk($lnk) {
if(substr(strtolower($lnk),0,7)=="http://"  ||
   substr(strtolower($lnk),0,8)=="https://" ||
   substr(strtolower($lnk),0,6)=="ftp://") return true;
return false;
}
//--------------------------------------------------------------------------
// arrSearch(): поиска значения $var в массиве $arr поле $field
//  Возврат: если нашел - то индекс массива, иначе -1
// if(($i=arrSearch($aBnk,$bnk,"CODE"))!=-1) $bnk_name=$aBnk[$i]["NAME"]; else $bnk_name='';
//--------------------------------------------------------------------------
function arrSearch($arr,$var,$field='') {
for($i=0;$i<sizeof($arr);$i++) {
  if(!$field) { if($arr[$i]==$var        ) break; }
  else        { if($arr[$i][$field]==$var) break; }
}
if($i<sizeof($arr)) return $i;
return -1;
}
//-----------------------------------------------------------------------
// ImgButton(): Формирование псевдо-кнопки с картинкой
//  $width  - ширина кнопки (default - на величину контента)
//  $height - высота кнопки (default - на величину контента)
//  $img    - картинка
//  $text   - текст
//  $bgcolor - цвет фона кнопки (default - стандартный серый)
//  $font    - стиль фонта (цвет,шрифт,величина,etc) (default - arial 12px)
//  $RunModule - вызываемый по OnClick обработчик
// Пример использования: brw_pns.php
//-----------------------------------------------------------------------
function ImgButton($width=0,$height=0,$img,$text,$bgcolor="D4D0C8",$font="font:normal 12px Arial,Verdana;",$RunModule) {
if(!$width)   $width=0;
if(!$height)  $height=0;
if(!$bgcolor) $bgcolor="D4D0C8";
if(!$font)    $font="font:normal 12px Arial,Verdana;";
$id=str_replace('.','_',uniqid("tabbtn",true)); 
$div='';
if($RunModule!='@') $div='<Div Style="cursor:pointer" OnMouseDown="document.getElementById(\''.$id.'\').style.border=\'inset 2px\'" OnMouseUp="document.getElementById(\''.$id.'\').style.border=\'outset 2px\'" OnClick="'.$RunModule.'">';
return '
  <table border="0" cellpadding="2" cellspacing="0" style="border: 0px solid; border-color:#999999">
  <Tr><Td>'.
    $div.
    '<Table ID="'.$id.'" CellSpacing=0 CellPadding=0 Style="border: outset 2px;" Border=0>
      <Tr bgcolor="#'.$bgcolor.'" style="height:'.$height.'px;">
        <td style="padding:0 0.1em 0 0.3em"><img src="'.$img.'" align="top"></td>
        <td width="'.$width.'px" nowrap style="'.$font.'">'.AdvStr($text).'</td>
      </Tr>
    </Table>
    </Div>
  </td></tr>
  </table>';
}
//-----------------------------------------------------------------------
// DelFileMsk(): Удаление файлов из указанного каталога по DOS-маске
//  $dir - каталог, $fname - маска (РЕГИСТРОЗАВИСИМАЯ!)
//  Для верности выполняется поиск по заданной $fname, по lower и по upper
//-----------------------------------------------------------------------
function DelFileMsk($dir,$fname) {
  $aFile=array(glob($dir."/".$fname, GLOB_NOSORT),
               glob($dir."/".strtoupper($fname), GLOB_NOSORT),
               glob($dir."/".strtolower($fname), GLOB_NOSORT));
  for($ii=0;$ii<sizeof($aFile);$ii++) {
    for($i=0;$i<sizeof($aFile[$ii]);$i++) {
      if(is_file($aFile[$ii][$i])) DelFile($aFile[$ii][$i]);
    }
  }
  return;
}
//--------------------------------------------------------------------------
// GetFileFromPath(): Получить имя файла из полного пути
//--------------------------------------------------------------------------
function GetFileFromPath($fn) {
  $fnn=str_replace("\\","/",$fn);
  if(($a1=strrpos($fn,'/'))===false) return strtoupper(trim($fn));
  if($a1==(strlen($fn)-1)) return '';
  return substr($fn,($a1+1));
}
//--------------------------------------------------------------------------
// GetFileExt(): Получить расширение файла из его полного имени 
//--------------------------------------------------------------------------
function GetFileExt($fn) {
  if(($a1=strrpos($fn,'.'))===false) return '';
  if($a1==(strlen($fn)-1)) return '';
  return substr($fn,($a1+1));
}
//--------------------------------------------------------------------------
// GetFileName(): Получить имя файла из его полного имени (без пути)
//--------------------------------------------------------------------------
function GetFileName($fn) {
  if(($a1=strrpos($fn,'.'))===false) return strtoupper(trim($fn));
  if($a1==0) return '';
  return substr($fn,0,$a1);
}
//-----------------------------------------------------------------------------
// move(): перенос/переименование файла с перезаписью старого (если такой есть)
// $err_nofile - считать ли ошибкой отсутствие исходного файла (default - да)
// Возврат: true - все OK, false - ошибка
//-----------------------------------------------------------------------------
function move($source,$target,$err_nofile=true,$mode=null) {
global $err__;
if(is_file($source)) {
  // Исходный файл есть - удаляем старый файл назначения (если есть) и переносим
  DelFile($target);
  if(!@rename($source,$target)) { if(isset($err__)) {$err__=$php_errormsg;} return false; }
} else {
  // Исходного файла нет
  if($err_nofile) { if(isset($err__)) {$err__="move(): Source file ".$source." not found !";} return false; }
}
if($mode!=null) @chmod($target,$mode);
return true;
}
//-----------------------------------------------------------------------
// GetPar(): Получить из строки указанный параметр (значение)
//  $par - параметр
//  $str - строка
//  $fl_term - флаг = true - если значение окаймлено кавычками или др.разделителями
// Возврат : строка значения параметра или пусто (если не найден или ошибка)
// Пример  : return 'ERROR : '.GetPar('text=',$xmlres);
//-----------------------------------------------------------------------
function GetPar($par,&$str,$fl_term=true) {
  if(($nn=strpos($str,$par))===false) return '';
  $n1=$nn+strlen($par);
  if($fl_term) {
    // Если параметры идут в кавычках или других окоймляющих символах
    $term=substr($str,$n1,1); // 1-я кавычка (или иной символ-разделитель)
    if(($n2=strpos($str,$term,($n1+1)))===false) return ''; // поиск 2-й кавычки
    return substr($str,$n1+1,$n2-($n1+1)); // искомый параметр
  } else {
    // Если параметры идут без кавычек или других окоймляющих символов
    if(($n2=strpos($str,' ',($n1+1)))===false) return substr($str,$n1);
    else return substr($str,$n1,$n2-$n1);
  }
}
//-------------------------------------------------------------------------->
// GetSortDesc() : Сформировать функцию сортировки массива DESC (для числ.полей)
// $field - поле сортировки
// $desc  - флаг обратной сортировки (true), по умолчанию false
//-------------------------------------------------------------------------->
function GetSortDesc($field,$desc=false) {
  return 'if($a["'.$field.'"]==$b["'.$field.'"]) return 0; return (($a["'.$field.'"]>$b["'.$field.'"])?'.($desc?'-1:1':'1:-1').');';
}
//-----------------------------------------------------------------------
// fstr(): Преобразование числа в строку в формате 
//         отделение дроби - точка
//         разделитель разрядов - пробел
//-----------------------------------------------------------------------
function fstr($num,$dec=0) {
return number_format(round($num,$dec),$dec,'.',' ');
}
//-----------------------------------------------------------------------
// numstr(): Преобразование числа в строку для подстановки в UPDATE
//           (фиксация плавающей ошибки php при формате %99.99f на %s)
// $num       - число
// $dec       - кол-во знаков после запятой (default=0)
//-----------------------------------------------------------------------
function numstr($num,$dec=0) {
return number_format($num,$dec,'.','');
}
//-----------------------------------------------------------------------
// CloseAll(): Аварийный выход - выполнить ROLLBACK тек.транзакции (если есть)
//              закрыть dbf (если открыт), закрыть все ODBC-коннекты
// $db        - коннект, по которому выполняется текущая транзакция
// $tran      - наименование транзакции
// Возврат: пустая строка если успешно, или сообщение при ошибке
// Пример (auto/sa.php) :
// if( strlen($ret=CloseAll($db,"ROLLBACK TRAN Tr_SA",$fn)) ) ErrMsgTxt($ret);
//-----------------------------------------------------------------------
function CloseAll($db='',$tran='',$dbf='') {
$ret='';
if(strlen($dbf)) @dbase_close($dbf);
if(strlen($tran)) if( !(@odbc_exec($db,$tran)) ) $ret="Ошибка транзакции [".$tran."] ! ".$php_errormsg;
@odbc_close_all();
return $ret;
}
//-----------------------------------------------------------------------
// CloseAllSy(): Аварийный выход - выполнить ROLLBACK тек.sybase-транзакции (если есть)
//              закрыть dbf (если открыт), закрыть SYBASE-коннект, закрыть все ODBC-коннекты
// $db        - sybase-коннект, по которому выполняется текущая транзакция
// $tran      - наименование транзакции
// Возврат: пустая строка если успешно, или сообщение при ошибке
// Пример (auto/ab.php) :
// if( strlen($ret=CloseAllSy($db,"ROLLBACK TRAN Tr_AB",$fn)) ) ErrMsgTxt($ret);
//-----------------------------------------------------------------------
function CloseAllSy($db='',$tran='',$dbf='') {
$ret='';
if(strlen($dbf)) @dbase_close($dbf);
if(strlen($tran)) if( !(@sybase_query($tran,$db)) ) $ret="Ошибка транзакции [".$tran."] ! ".$php_errormsg;
@sybase_close($db);
//@odbc_close_all();
return $ret;
}
//-----------------------------------------------------------------------
// ChkDir(): Проверка наличия дерева каталогов, при отсутствии каталогов - их создание
//           Возврат: true         - все OK, 
//                    имя каталога - каталог не найден и невозможно его создать
//-----------------------------------------------------------------------
function ChkDir($dirtree,$mode=0777) {
$arr=explode("/",$dirtree);
$dir=$arr[0];
if(!is_dir($dir)) { if(!mkdir($dir)) return $dir; else @chmod($dir,$mode); }
for($i=1;$i<sizeof($arr);$i++) {
  $dir.="/".$arr[$i];
  if(!is_dir($dir)) { if(!mkdir($dir)) return $dir; else @chmod($dir,$mode); }
}
return true;
}
//-----------------------------------------------------------------------
// AdvStr(): Замена в строке пробелов на &nbsp, дополнение &nbsp с обоих сторон
//           При <br> дополнение &nbsp с обоих сторон <br>
//-----------------------------------------------------------------------
function AdvStr($str,$otstup='&nbsp;') {
  if($str==null || !strlen($str)) return $otstup;
  return $otstup.str_replace(' ','&nbsp;',str_replace('<br>','&nbsp;<br>&nbsp;',$str)).$otstup;
}
//-----------------------------------------------------------------------
// SyStr(): Подготовка строки для записи в Sybase
//          (Убирается \, заменяется ',меняется укр.буква iI)
//-----------------------------------------------------------------------
function SyStr($str,$conv=true,$cp_in="CP1251",$cp_out="CP866") {
  $str2='';
  if(($str=trim($str))=='') return '';
  if($conv && $cp_out!="UTF-8") $str=iconv($cp_in,$cp_out."//IGNORE",$str); 
  for($i=0;$i<strlen($str);$i++) {
    if    ( ord($str[$i])<0x20 && ord($str[$i])!=0x0A && ord($str[$i])!=0x0D) $str2.='';
    elseif( $str[$i]=="'"  ) $str2.='"';
    elseif( $str[$i]=="\\" ) $str2.="";
    elseif( ord($str[$i])==178 ) $str2.="I";
    elseif( ord($str[$i])==179 ) $str2.="i";
    else $str2.=$str[$i];
  }
  if($conv && $cp_out=="UTF-8") $str2=iconv($cp_in,$cp_out."//IGNORE",$str2); 
  return $str2;
}
//-----------------------------------------------------------------------
// SyStr2(): Подготовка строки для записи в Sybase
//          (Убирается \, заменяется ',меняется укр.буква iI)
// Отличие от SyStr(): обработка двойной кавычки как \"
//-----------------------------------------------------------------------
function SyStr2($str,$conv=true) {
  $str2='';
  if(($str=trim($str))=='') return '';
  for($i=0;$i<strlen($str);$i++) {
    if    ( ord($str[$i])<0x20 && ord($str[$i])!=0x0A && ord($str[$i])!=0x0D) $str2.='';
    elseif( $str[$i]=="'"  ) $str2.='"';
    elseif( $str[$i]=="\\" ) $str2.="";
    elseif( ord($str[$i])==178 ) $str2.="I";
    elseif( ord($str[$i])==179 ) $str2.="i";
    else $str2.=$str[$i];
  }
  return ($conv?convert_cyr_string($str2,'w','d'):$str2);
}
//-----------------------------------------------------------------------
// SyStr8(): Подготовка строки для записи в Sybase
//          (Убирается \, заменяется ',меняется укр.буква iI)
// Запись в UTF-8
//-----------------------------------------------------------------------
function SyStr8($str,$conv=true) {
  return SyStr($str,$conv,"CP1251","UTF-8");
}
//-----------------------------------------------------------------------
// SyStr88(): Подготовка строки для записи в Sybase - из UTF-8 в CP866
//          (Убирается \, заменяется ',меняется укр.буква iI)
//-----------------------------------------------------------------------
function SyStr88($str,$conv=true) {
  return SyStr($str,$conv,"UTF-8","CP866");
}
//-----------------------------------------------------------------------
// AjaxStr(): Подготовка строки чтения из базы для отдачи в веб-сервис (json/ajax) CP866 -> UTF-8
//          (Убирается \, заменяется ',меняется укр.буква iI)
//-----------------------------------------------------------------------
function AjaxStr($str,$conv=true) {
  return SyStr($str,$conv,"CP866","UTF-8");
}
//-----------------------------------------------------------------------
// WebStr(): Подготовка строки для работы в PHP (вывод на экран и т.д.)
//          (Убирается \, заменяется ',меняется укр.буква iI)
// $conv - по умолчанию конвертация из 866 в 1251 (чтение из Sybase)
//-----------------------------------------------------------------------
function WebStr($str,$conv=true) {
  return SyStr($str,$conv,"CP866","CP1251");
}
//-----------------------------------------------------------------------
// WebStr2(): Подготовка строки для работы в PHP (вывод на экран и т.д.)
//          (Убирается \, заменяется ',меняется укр.буква iI)
// $conv - по умолчанию конвертация из 866 в 1251 (чтение из Sybase)
// Отличия от WebStr(): обработка двойной кавычки 
//-----------------------------------------------------------------------
function WebStr2($str,$conv=true) {
  $str2='';
  if(($str=trim($str))=='') return '';
  if($conv) $str=convert_cyr_string(trim($str),'d','w'); else $str=trim($str);
  for($i=0;$i<strlen($str);$i++) {
    if    ($str[$i]=="'"  ) $str2.='"';
    elseif( $str[$i]=="\"" ) $str2.='"';
    elseif($str[$i]=="\\" ) $str2.="";
    else $str2.=$str[$i];
  }
  return $str2;
}
//-----------------------------------------------------------------------
// WebStr8(): Подготовка строки для работы в PHP (вывод на экран и т.д.)
// $conv - по умолчанию конвертация из UTF8 в 1251 (чтение из Sybase)
//-----------------------------------------------------------------------
function WebStr8($str,$conv=true) {
  return SyStr($str,$conv,"UTF-8","CP1251");
}
//-----------------------------------------------------------------------
// pdStr(): Вычитка строки UTF-8 (из ПриватДока) с переводом в cp1251 +рихтовка
//          (Убирается \, заменяется ',меняется укр.буква iI)
//-----------------------------------------------------------------------
function pdStr($str) {
  $str2='';
  $str=iconv("UTF-8","CP1251"."//IGNORE",$str);
  if(($str=trim($str))=='') return '';
  for($i=0;$i<strlen($str);$i++) {
    if(   $str[$i]=="'"  ) $str2.='`';
    elseif( $str[$i]=="\\" ) $str2.="";
    elseif( ord($str[$i])==178 ) $str2.="I";
    elseif( ord($str[$i])==179 ) $str2.="i";
    else $str2.=$str[$i];
  }
  return $str2;
}
//-----------------------------------------------------------------------
// WebDat(): Подготовка даты для вывода в редактор
//-----------------------------------------------------------------------
function WebDat($dat) {
  if($dat==null || $dat=='' || $dat=='19000101') return ''; else return DatFormat($dat,1,2);
}
//-----------------------------------------------------------------------
// UniDat(): Вычитка даты как YYYYMMDD с фиксом NULL или пустой даты
//-----------------------------------------------------------------------
function UniDat($dat) {
  if($dat==null || $dat=='') return '19000101'; else return $dat;
}
//-----------------------------------------------------------------------
// Prn(): Замена пустых значений строки $s на '&nbsp;' (и NULL тоже на '&nbsp;')
//        Вместо '&nbsp;' можно указать свой символ-заполнитель (например '?') 
//-----------------------------------------------------------------------
function Prn($s,$pad='&nbsp;') {
  if( strlen(trim($s))>0 ) return $s;
  if( $s==NULL || !strlen($s) ) return $pad;
    else return str_repeat($pad,strlen($s));
}
//-----------------------------------------------------------------------
// Prnbr(): Принимает массив строк и формирует возврат с <br> между строками
//          Если все строки пустые - возвращает &nbsp;
//-----------------------------------------------------------------------
function Prnbr() {
  $argc=func_num_args();
  $s = (func_get_arg(0)==null?'':func_get_arg(0));
  for($i=1;$i<$argc;$i++) { if( strlen(trim(func_get_arg($i)))>0 ) $s.='<br>'.func_get_arg($i); }
  return (strlen($s)>0?$s:'&nbsp;');
}
//-----------------------------------------------------------------------
// inlist(): Эмуляция аналогичной функции FoxPro
//-----------------------------------------------------------------------
function inlist() {
  $argc=func_num_args();
  if( $argc < 2 ) return 1;
  $a = func_get_arg(0);
  for($i=1;$i<$argc;$i++) { if( $a == func_get_arg($i) ) return 1; }
  return 0;
}
//------------------------------------------------------------------------->
// DelZero($num) : Удаление конечного хвоста нулей после точки
//------------------------------------------------------------------------->
function DelZero($num) {
  if( strpos($num,'.') || substr($num,0,1)=='.' ) {
    $t=trim(str_replace('0',' ',$num));
    return ((substr($t,0,1)=='.'?'0':'').str_replace(' ','0',$t).(substr($t,strlen($t)-1,1)=='.'?'0':''));
  }
  return $num;
}
//--------------------------------------------------------------------------
// DelFile($fn): Удаление файла $fn (true-OK, false-Ошибка)
//--------------------------------------------------------------------------
function DelFile($fn) {
  if( is_file($fn) ) @unlink($fn);
  if( is_file($fn) ) return false; else return true;   
}
//--------------------------------------------------------------------------
// DelDir($dir): Рекурсивное удаление каталога с подкаталогами и файлами $dir (true-OK, false-Ошибка)
//--------------------------------------------------------------------------
function DelDir($dir) { 
    if (!file_exists($dir)) return true; 
    if (!is_dir($dir) || is_link($dir)) return unlink($dir); 
        foreach (scandir($dir) as $item) { 
            if ($item == '.' || $item == '..') continue; 
            if (!DelDir($dir . "/" . $item)) { 
                chmod($dir . "/" . $item, 0777); 
                if (!DelDir($dir . "/" . $item)) return false; 
            }; 
        } 
        return rmdir($dir); 
} 
//-----------------------------------------------------------------------
// DelAllFile(): Удаление всех файлов из указанного каталога (true-OK, false-Ошибка)
//-----------------------------------------------------------------------
function DelAllFile($dir,$wait=0) {
  // Ожидание в секундах (чтоб отлипли другие процессы)
  if($wait>0) sleep($wait);
  if(!is_dir($dir)) return true;
  $dh = opendir($dir);
  while ( $fn = readdir($dh) ) {
    if(!DelFile($dir.'/'.$fn)) { closedir($dh); return false; }
  }
  closedir($dh);
  return true;
}
//--------------------------------------------------------------------------
// PadC() : дополнение пробелами и центровка строки $st в области $len
//--------------------------------------------------------------------------
function padC($st,$len) {
  $lwork=($len-strlen(trim($st))); // останется пустых символов
  if( $lwork <= 0 ) return $st;
  $first_space = ($lwork >> 1); $last_space  = ($lwork - $first_space);
  return (str_repeat("&nbsp;",$first_space).$st.str_repeat("&nbsp;",$last_space));
}
//--------------------------------------------------------------------------
// strlimit() : проверка на недопустимость превышения max длины строки
//--------------------------------------------------------------------------
function strlimit($st,$len) {
  if( $st!=Null && strlen($st)>$len ) return substr($st,0,$len); else return $st;
}
//-------------------------------------------------------------------------->
// FindInList(): Поиск значения в символьной строке, разделенной $term
//        $cur - что ищем, $list - список поиска, $term - разделитель
//  Поиск IP в строке раздел.запятая: FindInList($REMOTE_ADDR,$Ip,',')
//-------------------------------------------------------------------------->
function FindInList($cur,$list,$term) {
  $aIpStr=explode($term,trim($list));
  for($i=0;$i<sizeof($aIpStr);$i++) if( $cur == trim($aIpStr[$i]) ) return 1;
  return 0;
}
//-------------------------------------------------------------------------->
// FindInListMsk(): Поиск значения в символьной строке, разделенной $term
//        $cur - что ищем, $list - список поиска, $term - разделитель
//  Поиск IP в строке раздел.запятая: FindInList($REMOTE_ADDR,$Ip,',')
// !!! на конце маски учитывается '*' как 'любые символы' !!! 
// !!! т.е. строка масок может содержать $Ip='10.1.111.60,10.8.*' !!!
//-------------------------------------------------------------------------->
function FindInListMsk($cur,$list,$term) {
  $aIpStr=explode($term,trim($list)); 
  for($i=0;$i<sizeof($aIpStr);$i++) { 
    $msk=trim($aIpStr[$i]);
    if( strlen($msk)>2 && substr($msk,strlen($msk)-1,1)=='*' ) {
      $msk=substr($msk,0,strlen($msk)-1);
      if( strlen($cur)>strlen($msk) ) $cur_=substr($cur,0,strlen($msk));
    } else $cur_=$cur;
    //var_dump($cur_);print '<br>';var_dump($msk);
    if( $cur_ == $msk ) return 1;
  }
  return 0;
}
//-----------------------------------------------------------------------
// DelTmpFile(): Удаление временных файлов (php*.tmp) из временного каталога
// $all - необязательный параметр - true : удалить все файлы из каталога
//-----------------------------------------------------------------------
function DelTmpFile($temp,$wait,$all=false) {
  // Ожидание в секундах (чтоб отлипли другие процессы)
  sleep($wait);
  $dh = opendir($temp);
  while ( $fn = readdir($dh) ) {
    if($all) {
      if(is_file($temp.'/'.$fn)) @unlink($temp.'/'.$fn);
    } else {
      if( strtolower(substr($fn,0,3))=='php' && strtolower(substr($fn,-4,4))=='.tmp' ) @unlink($temp.'/'.$fn);
    }
  }
  closedir($dh);
}
//-----------------------------------------------------------------------
// download_pretending($url,$user_agent): 
// Загрузка файла по Http и Ftp протоколу при помощи curl
// Требуется включение extension=php_curl.dll в Php.ini
// $url - путь и имя загружаемого файла
// $user_agent - наименование броузера клиента опционально
// Возвращает строку с содержимым файла
//-----------------------------------------------------------------------
function download_pretending($url,$proxy='',$userpwd='',$user_agent='Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)') {
   global $php_errormsg;
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Добавлено
   curl_setopt ($ch, CURLOPT_URL, $url);
   curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
   curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt ($ch, CURLOPT_TIMEOUT, 1000);
   curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1000);
   curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
   if($proxy  !='') curl_setopt ($ch, CURLOPT_PROXY, $proxy);
   if($userpwd!='') curl_setopt ($ch, CURLOPT_PROXYUSERPWD, $userpwd);

   $result = curl_exec ($ch);
   if (curl_errno($ch)) {
    $php_errormsg=curl_error($ch);
   }
   curl_close ($ch);
   return $result;
}
//-----------------------------------------------------------------------
// CurlRead(): Вычитка файла с сайта (без POST-обращения )
// $adr     - адрес сайта
// $PostRes - строка-результат запроса (передается по ссылке)
// Возврат: пустая строка если успешно, или сообщение при ошибке
// Пример:
// $PostRes='';
// if( strlen($ret=CurlPost($addr,&$PostRes))) die("Ошибка POST-запроса к ПриватДоку ! ".$ret);
//-----------------------------------------------------------------------
function CurlRead($addr,&$PostRes,$proxy='',$userpwd='') {
$ch=curl_init();
curl_setopt($ch, CURLOPT_URL, $addr);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
if($proxy  !='') {
  curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
  curl_setopt ($ch, CURLOPT_PROXY, $proxy);
}else{
  curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, FALSE);
}
if($userpwd!='') curl_setopt ($ch, CURLOPT_PROXYUSERPWD, $userpwd);
// Выполняем запрос файла
$PostRes = curl_exec($ch);
if (curl_errno($ch)) { curl_close($ch); return sprintf("Error Curl_Exec [%d]: %s",curl_errno($ch), curl_error($ch)); }
curl_close($ch);
return '';
}
//-----------------------------------------------------------------------
// CurlPost(): POST-запрос к сайту
// $adr     - адрес сайта
// $submit  - передаваемые поля POST-запроса (или XML-запрос)
// $PostRes - строка-результат запроса (передается по ссылке)
// Возврат: пустая строка если успешно, или сообщение при ошибке
// Пример:
// $PostRes='';
// if( strlen($ret=CurlPost($addr,"LIST_DATE=".DatFormat($date,1,3),&$PostRes))) die("Ошибка POST-запроса к ПриватДоку ! ".$ret);
//-----------------------------------------------------------------------
function CurlPost($addr,$submit,&$PostRes,$proxy='',$userpwd='') {
$ch=curl_init();
curl_setopt($ch, CURLOPT_URL, $addr);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $submit);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
if($proxy  !='') curl_setopt ($ch, CURLOPT_PROXY, $proxy);
if($userpwd!='') curl_setopt ($ch, CURLOPT_PROXYUSERPWD, $userpwd);
// Выполняем POST-запрос
$PostRes = curl_exec ($ch);
if (curl_errno($ch)) return sprintf("Error Curl_Exec [%d]: %s",curl_errno($ch), curl_error($ch));
curl_close ($ch);
return '';
}
//-----------------------------------------------------------------------
// Post2xml(): Отправить POST-запрос к веб-форме и выполнить станд.парсинг XML-результата
// $addr - страница-обработчик post-a
// $submit - параметры post-a (формат как в GET-форме)
// $values - пустой массив для приема результата парсера
// $tags   - пустой массив для приема результата парсера
// $cp - кодовая страница (юникод="UTF-8")
// Возврат: пустая строка если успешно, или сообщение при ошибке
// Пример:
// $values=array();$tags=array();
// $addr="https://10.1.99.40:8083/poffice/poffice194.jsp";
// $submit="sdDay=21&sdMonth=01&sdYear=2008&fdDay=22&fdMonth=01&fdYear=2008&contract=1&status=0";
// if( strlen($ret=Post2xml($addr,$submit,$values,$tags,"UTF-8"))) die("Ошибка запроса данных ! ".$ret);
//-----------------------------------------------------------------------
function Post2xml($addr,$submit,&$values,&$tags,$cp="UTF-8") {
$ch=curl_init();
curl_setopt($ch, CURLOPT_URL, $addr);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $submit);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
// Выполняем POST-запрос
$data = curl_exec ($ch);
if (curl_errno($ch)) return sprintf("Error Curl_Exec [%d]: %s",curl_errno($ch), curl_error($ch));
curl_close ($ch);

// Создаем стандартный парсер XML-потока
$parser = @xml_parser_create();
if ($parser == null) return "Error xml_parser_create ! ".$php_errormsg;

//@xml_parser_set_option($parser,@xml_OPTION_CASE_FOLDING,0);
//@xml_parser_set_option($parser,@xml_OPTION_SKIP_WHITE,1);
if(@xml_parse_into_struct($parser,$data,$values,$tags) == false) {
  $ret=sprintf("Error XML_PARSE_INTO_STRUCT: %s at line %d",
               @xml_error_string(@xml_get_error_code($parser)),
               @xml_get_current_line_number($parser));
  @xml_parser_free($parser);
  return $ret;
}
@xml_parser_free($parser);
// Все ok, результаты парсера - в передаваемых $values & $tags
for($i=0;$i<sizeof($values);$i++) if($values[$i]["value"]!=null) $values[$i]["value"]=iconv("UTF-8","CP1251"."//IGNORE",$values[$i]["value"]);
//var_dump($values);
return '';
}
//-----------------------------------------------------------------------
// parseArrXml(): Допарсивание массива после стандартного xml_parse php
// $values - массив от стандартного xml_parse_into_struct
// $aData -  массив-результат (передается по ссылке)
// $row - тег выделяющий блок информации (uppercase)
// $aField - массив с интересующими значениями (элементами) из блока инфо (uppercase)
// Пример: parseArrXml($values,$aData,"CONTRACT",array("AGENT_LDAP","AGENT_FIO","BRANCH","PLACE"));
//-----------------------------------------------------------------------
function parseArrXml($values,&$aData,$row,$aField) {
    $arr=array();
    foreach ($values as $key=>$val) {
      if ((strtoupper($val['tag']) == $row) && (strtolower($val['type']) == "open")) {
        if(sizeof($arr)) $aData[]=$arr;
        $arr=array();
      }
      else {
        for($i=0;$i<sizeof($aField);$i++) if($aField[$i]==strtoupper($val['tag'])) break;
        if($i<sizeof($aField)) $arr[strtoupper($val['tag'])]=trim($val['value']);
      }
    }
    // Записывем последний элемент
    if(sizeof($arr)) $aData[]=$arr;
}
//-----------------------------------------------------------------------
// GetProminXML(): Получить данные с Проминя методом post'a с XML-запросом
//  (описание - см.данное Сергеем Кузьминовым (боец Гаврилова), 57-12)
// $addr    - страница-обработчик post-a
// $cntr    - страна (UA/RU)
// $type    - тип запроса к данным (INF - информация о клиенте (по ID или ИНН))
// $aSearch - массив с ключами поиска типа array('OKPO="3234415758"','OKPO="3234415758"')
// $aResult - массив с перечнем полей, кот.нужно получить в результат выборки
// $xmlres  - XML-результат выборки
// Возврат: пустая строка если успешно, или сообщение при ошибке
// Пример:
// $addr="http://10.1.205.37:3200/CIS4";
// $aSearch=array('OKPO="3234415758"','OKPO="3234415758"');
// $aResult=array("DType","uaFName","DWhere","DWho");
// $resxml='';
// if( strlen($ret=GetProminXML($addr,'UA','INF',$aSearch,$aResult,&$resxml))) die("Ошибка запроса данных ! ".$ret);
//-----------------------------------------------------------------------
function GetProminXML($addr,$cntr,$type,$aSearch,$aResult,&$xmlres) {

// Формируем xml-запрос
$xml='<?xml version="1.0" encoding="UTF-8" ?><doc>';
for($i=0;$i<sizeof($aSearch);$i++) {
  $xml.='<r cntr="'.$cntr.'" t="'.$type.'" key="id'.$i.'">';
  $xml.='<o>';
  for($j=0;$j<sizeof($aResult);$j++) $xml.='<'.$aResult[$j].'/>';
  $xml.='</o>';
  $xml.='<i '.$aSearch[$i].'/>';
  $xml.='</r>';
}
$xml.='</doc>';

// Обращаемся на сайт
$submit=$xml;
$ch=curl_init();
curl_setopt($ch, CURLOPT_URL, $addr);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $submit);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
// Выполняем POST-запрос
$data = curl_exec ($ch);

if (curl_errno($ch)) return sprintf("Error Curl_Exec [%d]: %s",curl_errno($ch), curl_error($ch));
curl_close ($ch);
// Необходимость конвертации из юникода, Рихтуем спецсимволы
//$xmlres=str_replace('&','-',iconv("UTF-8","CP1251"."//IGNORE",$data));
$xmlres=str_replace('&','-',$data);
return '';
}
//-----------------------------------------------------------------------
// GetFotoEKB() : Получить фото клиента из ЕКБ по ИНН
//  global : $errmes - сообщение об ошибке при сбое
//           $foto - двоичный поток с фото при успехе
// Возврат : 1-OK, 0-ИНН не найден, отрицательный результат - ошибка
//-----------------------------------------------------------------------
function GetFotoEKB($inn) {
global $errmes,$foto_ekb;

// Адрес фото ЕКБ
$addr="https://10.1.101.208:11701/attweb";  // Для получения фото
// Формируем XML-запрос к ЕКБ
$xml='<?xml version="1.0" encoding="UTF-8"?><doc><r t="ATT_GET_INFO_PHOTO"><o><id/><URL/><ext/></o><i srvCust="CIS"><Rec id="20" data="'.$inn.'"/></i></r></doc>';

  // Делаем POST-запрос к Гаврилову с передачей XML-запроса
  $ch=curl_init();
  curl_setopt($ch, CURLOPT_URL, $addr);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  // Выполняем POST-запрос
  $data = curl_exec ($ch);
  if (curl_errno($ch)) {
    $errmes=sprintf("Ошибка обращения к ЕКБ да получения URL фото : Error Curl_Exec [%d]: %s",curl_errno($ch), curl_error($ch));
    curl_close ($ch);
    return -1;
  }
  curl_close ($ch);
  // Необходимость конвертации из юникода
//  WriteDump($data);
  $xmlres=iconv("UTF-8","CP1251"."//IGNORE",$data);

  // Получаем URL для нахождения фотографии
  if( ($pos=strpos($xmlres,"URL='"))===false ) {
    $errmes="Поле URL фото в XML-результате из ЕКБ не найден !";
    return 0;
  }
  $pos1=$pos+5;
  if( ($pos2=strpos($xmlres,"'",$pos1))===false ) {
    $errmes="В поле URL фото в XML-результате из ЕКБ не найдено замыкание строки адреса (кавычка) !";
    return -3;
  }
  if(($pos2-$pos1)<5) {
    $errmes="Некорректный URL фото в XML-результате из ЕКБ (длина менее 5 символов) !";
    return -4;
  }
  $url=substr($xmlres,$pos1,($pos2-$pos1));

  // Пытаемся забрать фото по URL
  $ch=curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $foto_ekb = curl_exec ($ch);
  if (curl_errno($ch)) {
    $errmes=sprintf("Ошибка получения фото по URL из XML-результата ЕКБ : Error Curl_Exec [%d]: %s",curl_errno($ch), curl_error($ch));
    curl_close ($ch);
    return -5;
  }
  curl_close ($ch);
  return 1;
}
//------------------------------------------------------------------------->
// IsFilial($brnm)
// По черырехбуквенному бранчу определяем является ли подразделение филиалом
// Для Черновицкого филиала, Закарпатского РУ
//-------------------------------------------------------------------------->
function IsFilial($brnm){
    return in_array($brnm,Array('CVH0','MKZ7'));
}
//------------------------------------------------------------------------->
// isHTTPS()
// Определяет протокол по которому идет запрос к серверу HTTPS или HTTP
// возврат HTTPS - TRUE
//         HTTP  - FALSE
//-------------------------------------------------------------------------->
function isHTTPS(){
    if (isset($_SERVER['HTTP_HTTPS'])){
        return TRUE;
    }elseif ((isset($_SERVER["HTTPS"])) && (strtolower($_SERVER["HTTPS"]) == "on")){
        return TRUE;
    }elseif ((isset($_SERVER["HTTP_X_FORWARDED_PROTO"])) && (strtolower($_SERVER["HTTP_X_FORWARDED_PROTO"]) == "https")){
        return TRUE;
    }else{
        return FALSE;
    }
}

//------------------------------------------------------------------------->
// build_querystring($strQS, $arRemove, $arAdd = Array())
// Добавляет или удаляет параметры к строке запроса
// возврат измененная строке запроса
// $strQS - входная строка запроса
// $arRemove - массив параметров на удаление
// $arAdd - массив дополняемых параметров         
//-------------------------------------------------------------------------->
function build_querystring($strQS, $arRemove, $arAdd = Array()){
    parse_str($strQS, $arQS);
    $arQS = array_diff_key($arQS, array_flip($arRemove));
    $arQS = array_merge($arQS,$arAdd);
    return http_build_query($arQS);
}
/*
 * Защита от инъекций в скриптах
 */
function ms_escape_string($data) {
    if ( !isset($data) or empty($data) ) return '';
    if ( is_numeric($data) ) return $data;

    $non_displayables = array(
        '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
        '/%1[0-9a-f]/',             // url encoded 16-31
        '/[\x00-\x08]/',            // 00-08
        '/\x0b/',                   // 11
        '/\x0c/',                   // 12
        '/[\x0e-\x1f]/'             // 14-31
    );
    foreach ( $non_displayables as $regex )
        $data = preg_replace( $regex, '', $data );
    $data = str_replace("'", "''", $data );
    return $data;
}
?>