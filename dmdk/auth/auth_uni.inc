<?php
if (is_file('INCLUDE/util.inc')) {
  require_once('INCLUDE/util.inc');
} else {
  require_once('util.inc');
}

if (!defined('SERV_AUTH')){
    include_once('connection.inc');
}
if (!defined('SERV_AUTH')){
    include('connection.inc');
}
  
Class Auth_Uni{
  const TIMEOUT=300;
  const RETRY_CNT=3;
  
  const SERV_AUTH='http://10.100.176.36:8893';
  const SCOPE='read';
  private $client_id;
  private $secret;
  private $ldap='';
  private $code;
  private $access_token='';
  private $error='';
  private $redirect_uri;
  private $debug=FALSE;

  public function init(){
    if (is_file(__DIR__.'/session.inc')){
        include(__DIR__.'/session.inc'); 
    }elseif (version_compare(phpversion(), '5.4.0', '<')) {
        if(session_id() == '') {
            session_start();
        }
    }else{
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    if ($this->isRefererCEA()){
      if ($this->debug) printf("%s<br>",$_SERVER['HTTP_REFERER']);
      if ($this->debug) printf("Save redirect URL (init) %s<br>",$_SESSION['Auth_Uni']['redirect_uri']);
      // Реферер совпадает со страницей авторизации и код не пустой
      $this->setCode();
      @$this->setRedirect_uri($_SESSION['Auth_Uni']['redirect_uri']);
      if (isset($_SESSION['Auth_Uni']['REQUEST']) && ($_SESSION['Auth_Uni']['REQUEST']!=NULL)) $_REQUEST=$_SESSION['Auth_Uni']['REQUEST'];
      if (isset($_SESSION['Auth_Uni']['POST']) && ($_SESSION['Auth_Uni']['POST']!=NULL)) $_POST=$_SESSION['Auth_Uni']['POST'];
      if (isset($_SESSION['Auth_Uni']['GET']) && ($_SESSION['Auth_Uni']['GET']!=NULL)) $_GET=$_SESSION['Auth_Uni']['GET'];
      if ($this->debug){
        if ($_REQUEST!=null) var_dump($_REQUEST);print('<br>');
        if ($_SESSION['Auth_Uni']['REQUEST']!=null) var_dump($_SESSION['Auth_Uni']['REQUEST']);print('<br>');
      }
    }else{
      $this->setRedirect_uri();                 
      //unset($_SESSION['Auth_Uni']);
      $_SESSION['Auth_Uni']['redirect_uri']=$this->getRedirect_uri();
      //if ($this->debug) {printf("Save redirect URL  %s<br>",$_SESSION['Auth_Uni']['redirect_uri']);exit;}
      $_SESSION['Auth_Uni']['REQUEST']=$_REQUEST;
      $_SESSION['Auth_Uni']['POST']=$_POST;
      $_SESSION['Auth_Uni']['GET']=$_GET;
    }
  }
  
  public function isRefererCEA(){
      //"http://localhost:8080/file:/X:/TOWER/brw_nsg.php?DBGSESSID=414873796963000001%3Bd%3D1%2Cp%3D0%2Cc%3D1&code=zVU39g&state=enter%20HTTP/1.1"
      //if (!isset($_SERVER['HTTP_REFERER'])) return false;
      //if (!isset($_SESSION['Auth_Uni']['redirectCEA'])) return false;
      if (isHTTPS()){
         if ((strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')===FALSE)/* || (strpos($_SERVER['HTTP_USER_AGENT'],'Windows')===FALSE)*/){
            if (isset($_SERVER['HTTP_REFERER'])){
                if (strpos($_SERVER['HTTP_REFERER'],self::SERV_AUTH.'/ProminShell/')===FALSE) return FALSE;
            }
         }
      }
      if (!isset($_REQUEST['code'])) return FALSE;
      if (!isset($_REQUEST['state'])) return FALSE;
      return (($_REQUEST['state']=='enter') && ($_REQUEST['code']!=''));
  }

  public function destroy(){
    unset($_SESSION['Auth_Uni']);
  }

  public function getDebug(){
    return $this->debug;
  }

  public function setDebug($debug){
    $this->debug=$debug;
  }

  public function getError(){
    return $this->error;
  }

  public function setError($err){
    if ($this->error!='') $this->error.='<br>'.$err;
    else $this->error=$err;
  }

  public function getClient_id(){
    return $this->client_id;
  }

  public function setClient_id($client_id){
    $this->client_id=$client_id;
  }

  public function getSecret(){
    return $this->secret;
  }

  public function setSecret($secret){
    $this->secret=$secret;
  }

  public function getLdap(){
    return $this->ldap;
  }

  public function setLdap(){ 
    for ($i=0;$i<self::RETRY_CNT;$i++){  
        if (!isset($this->access_token) || ($this->access_token==null) || ($this->access_token=='')) {
            $this->setAccess_token();
        }
        if (!isset($this->access_token) || ($this->access_token==null) || ($this->access_token=='')) {
            $this->setError("Error setLdap()! Access_token is empty");
            return;
        }
        //$fOut = fopen('token.txt', "w" );
        $curlVersion = curl_version();
        $aCurl=array(CURLOPT_URL            => self::SERV_AUTH."/ProminShell/user/get?format=json",
                 //CURLOPT_VERBOSE => 1,
                 //CURLOPT_STDERR => $fOut,
                 CURLOPT_FOLLOWLOCATION => 1,
                 CURLOPT_RETURNTRANSFER => 1,
                 CURLOPT_HTTPPROXYTUNNEL => 0,
                 CURLOPT_SSL_VERIFYPEER => false,
                 CURLOPT_SSL_VERIFYHOST => ($curlVersion["version"]>='7.28.1')?2:1,
                 CURLOPT_HTTPHEADER => array( 'Authorization: Bearer ' . $this->access_token ),
                 CURLOPT_SSLVERSION => 6
               );
        if(is_array($dom=getFeedCurl($aCurl,self::TIMEOUT,'json',"<br>"))===false) {
            // Обработчик ситуации с устаревшим access_token
            if ((isset($dom["error_description"])) && ($dom["error_description"]!='')){
                unset($_REQUEST['code']);
                unset($this->code);
                unset($this->access_token);
                unset($_SESSION['sv']['token']);
                //$this->setError($i);
                continue;
            }
            $this->setError("Error setLdap()! $dom");
            if ($this->debug) printf("Error setLdap()! $dom");
        }else{
            $this->ldap=$dom["username"];
            if ($this->debug) var_dump($dom);
            break;
        }
    }
  }

  public function getRedirect_uri(){
    return $this->redirect_uri;
  }

  public function setRedirect_uri($uri=null){
    if ($uri!=''){
      $this->redirect_uri=$uri;
      return;
    }
    $this->redirect_uri=$this->curPageURL();
  }

  private function querystring($strQS, $arRemove, $arAdd = Array()){
    parse_str($strQS, $arQS);
    $arQS = array_diff_key($arQS, array_flip($arRemove));
    $arQS = array_merge($arQS,$arAdd);
    return http_build_query($arQS);
  }

  private function curPageURL($delArray=Array('code','state')) {
    $pageURL = 'http';
    if (isHTTPS()) {$pageURL .= "s";}

    if (isset($_SERVER['QUERY_STRING'])){
        $request = str_replace('?','&',$_SERVER['QUERY_STRING']);
    }else{
        $request = '';
    }
    if ($this->debug) printf('%s<br>',$request);
    $request = $this->querystring($request,$delArray);
    if ($this->debug) printf('%s<br>',$request);
    $pageURL .= '://'.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];
    if ($request!='') $pageURL .= '?'.$request;
    return $pageURL;
  }

  public function getCode(){
    return $this->code;
  }

  public function setCode(){
    //if(isset($_REQUEST['code'])) {
    if ($this->isRefererCEA()){
      $this->code=$_REQUEST['code'];
      unset($_SESSION['Auth_Uni']['redirectCEA']);      
    }else{
      if (!isset($this->client_id) || ($this->client_id==null) || ($this->client_id=='')) {
        $this->setError("Error setCode()! Client_id is empty");
        return;
      }
      if ($this->redirect_uri=='') $this->setRedirect_uri();
      if ($this->debug) {
        printf('<script>alert("Code redirect URL %s")',$this->redirect_uri);
        printf("Code redirect URL %s<br>",$this->redirect_uri);
      }
      // Кинуть GET-запрос на сервис Единой авторизации (CEA)
      $lnk_=$this->getCEAUrl();
      $_SESSION['Auth_Uni']['redirectCEA']=TRUE;
      header("Location: ".$lnk_);
      exit();
    }
  }

  /*
    Получить адрес перехода на сервис авторизации
  */
  public function getCEAUrl(){
    return sprintf("%s/ProminShell/oauth/authorize?client_id=%s&redirect_uri=%s&scope=%s&response_type=code&state=enter",
                self::SERV_AUTH,$this->client_id,rawurlencode($this->redirect_uri),self::SCOPE);
  }

  public function getAccess_token(){
    return $this->access_token;
  }

  public function setAccess_token($token=''){
    if ($token!=''){
      $this->access_token=$token;
      return;
    }
    if (!isset($this->client_id) || ($this->client_id==null) || ($this->client_id=='')) {
      $this->setError("Error setAccess_token()! Client_id is empty");
      return;
    }
    if (!isset($this->secret) || ($this->secret==null) || ($this->secret=='')) {
      $this->setError("Error setAccess_token()! Secret is empty");
      return;
    }
    for ($i=0;$i<self::RETRY_CNT;$i++){
      if (!isset($this->code) || ($this->code==null) || ($this->code=='')) {
         $this->setCode();
      }
      if (!isset($this->code) || ($this->code==null) || ($this->code=='')) {
         $this->setError("Error setAccess_token()! Code is empty");
         return;
      }
      if ($this->debug) printf("Token redirect URL(setAccess_token) %s<br>",$this->redirect_uri);
      if ($this->debug) printf("Save redirect URL(setAccess_token)  %s<br>",$_SESSION['Auth_Uni']['redirect_uri']);
      $submit="grant_type=authorization_code&code=".$this->code."&redirect_uri=".rawurlencode($this->redirect_uri);
      // Получить токен
      $curlVersion = curl_version();
      $aCurl=array(CURLOPT_URL            => self::SERV_AUTH."/ProminShell/oauth/token",
                   CURLOPT_FOLLOWLOCATION => 1,
                   CURLOPT_RETURNTRANSFER => 1,
                   CURLOPT_HTTPPROXYTUNNEL => 0,
                   CURLOPT_SSL_VERIFYPEER => false,
                   CURLOPT_SSL_VERIFYHOST => ($curlVersion["version"]>='7.28.1')?2:1,
                   CURLOPT_POST           => 1,
                   CURLOPT_POSTFIELDS     => $submit,
                   CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
                   CURLOPT_USERPWD        => $this->client_id.":".$this->secret,
                   CURLOPT_SSLVERSION     => 6
                   );
      if(is_array($dom=getFeedCurl($aCurl,self::TIMEOUT,'json',"<br>"))===false) {
        // Обработчик ситуации с устаревшим Code
        // {"error" : "invalid_grant","error_description" : "Invalid authorization code: 150409095321D22jinvd"}
        if (strpos($dom,'invalid_grant')!==FALSE){
          unset($_REQUEST['code']);
          unset($this->code);
          unset($this->access_token);
          unset($_SESSION['sv']['token']);
          //unset($this->redirect_uri);
          //$this->setError($i);
          continue;
        }
        $this->setError("Error setAccess_token()! $dom");
        if ($this->debug) printf("Error setAccess_token()! $dom");
        // Обработчик ситуации с некорректной парой ид secret
        // {\n  \"error\" : \"unauthorized\",\n  \"error_description\" : \"Bad credentials\"\n}"
        if (strpos($dom,'unauthorized')!==FALSE){
            break;
        }
      }else{
        $this->access_token=$dom["access_token"];
        $_SESSION['sv']['token']=$this->access_token;
        if ($this->debug) var_dump($dom);
        break;
      }
    }
  }

  public function logout(){
    if (!isset($this->access_token) || ($this->access_token==null) || ($this->access_token=='')) {
       if ($this->debug) printf("Error logout()! Access_token is empty<br>");
       $this->setError("Error logout()! Access_token is empty");
       return;
    }
    //$fOut = fopen('logout.txt', "a+" );
    $curlVersion = curl_version();
    $aCurl=array(CURLOPT_URL            => self::SERV_AUTH."/ProminShell/user/do.logout",
                 //CURLOPT_VERBOSE => 1,
                 //CURLOPT_STDERR => $fOut,
                 CURLOPT_FOLLOWLOCATION => 1,
                 CURLOPT_RETURNTRANSFER => 1,
                 CURLOPT_HTTPPROXYTUNNEL => 0,
                 CURLOPT_SSL_VERIFYPEER => false,
                 CURLOPT_SSL_VERIFYHOST => ($curlVersion["version"]>='7.19.7')?2:1,
                 CURLOPT_POST           => 1,
                 CURLOPT_POSTFIELDS     => '',
                 CURLOPT_HTTPHEADER => array( 'Authorization: Bearer ' . $this->access_token, 'Accept: application/json'),
                 CURLOPT_SSLVERSION => 6
               );
    if(is_array($dom=getFeedCurl($aCurl,self::TIMEOUT,'json',"<br>"))===false) {
      $this->setError("Error logout()! $dom");
      if ($this->debug) printf("Error logout()! $dom");
    }else{
      if ($this->debug) var_dump($dom);
      unset($_SESSION['Auth_Uni']);
      unset($this->access_token);
      unset($_SESSION['sv']['token']);
    }
  }
  
  private function modQuery($add_to, $rem_from = array(), $clear_all = false){
    if ($clear_all){
        $query_string = array();
    }else{
        parse_str($_SERVER['QUERY_STRING'], $query_string);
    }
    if (!is_array($add_to)){ $add_to = array(); }
    $query_string = array_merge($query_string, $add_to);
    if (!is_array($rem_from)){ $rem_from = array($rem_from); }
    foreach($rem_from as $key){
        unset($query_string[$key]);
    }
    return http_build_query($query_string);
  }
  ////////////////////////////////////////////////////////////////////////////////
  ///ProminShell/enter/isauthorize
  ////////////////////////////////////////////////////////////////////////////////
  public function isAuthorize(){
      //echo $_SERVER['HTTP_REFERER'];
    if (isset($_SESSION['Auth_Uni']['redirect']) && isset($_REQUEST['can_authorize'])){
        if (strtolower($_REQUEST['can_authorize']) === "true") {
            $ret=TRUE;
        }else{
            $ret=FALSE;
        }
        unset($_REQUEST['can_authorize']);
        $_SERVER['QUERY_STRING']=$this->modQuery(array(),array('can_authorize'));
        unset($_SESSION['Auth_Uni']['redirect_uri']);
        return $ret;
    }else{
      if ($this->redirect_uri=='') {
          $this->setRedirect_uri(str_replace('?','&',$this->curPageURL(array('can_authorize'))));
      }
      if ($this->debug) {
        printf('<script>alert("isauthorize redirect URL %s");</script>',$this->redirect_uri);
        printf("isauthorize redirect URL %s<br>",$this->redirect_uri);
      }
      //$this->redirect_uri = $this->querystring($this->redirect_uri,Array('can_authorize'));
      // Кинуть GET-запрос на сервис Единой авторизации (CEA)
      $lnk_=sprintf("%s/ProminShell/enter/isauthorize?redirect_uri=%s",
                self::SERV_AUTH,$this->redirect_uri);
      $_SESSION['Auth_Uni']['redirect']=TRUE;
      header("Location: ".$lnk_);
      exit();
    }
  }
  ////////////////////////////////////////////////////////////////////////////////
}

//-----------------------------------------------------------------------------------------------
// auth_uni_login(): Авторизация сотрудника через ЕСА (Единая страница аутентификации)
//                   При пустом токене - выполняется переход на страницу авторизации 
//  $name     - имя комплекса (client_id)
//  $password - пароль комплекса (secret)
// [$token]   - токен (если пусто - автоматический переход на страницу авторизации)
//  $retArray - массив возврата array('ldap','token','error')
// Возврат: пусто при успехе, строка с сообщением при ошибке
// Пример применения: TOWER/logoff.php 
//-----------------------------------------------------------------------------------------------
function auth_uni_login($name,$password,&$token='',&$retArray,$debug=FALSE){
  $ret='';
  $auth_uni = new Auth_Uni();
  $auth_uni->setDebug($debug);
  $auth_uni->init();
  $auth_uni->setClient_id($name);
  $auth_uni->setSecret($password);
  // Если токен передан - присваиваем его объекту
  if($ret=='' && $token!='') {
    $auth_uni->setAccess_token($token);
    if($auth_uni->getError()!='') $ret = $auth_uni->getError(); 
    else unset($token);
  }    
  // Это мы будем возвращать
  $token=$ldap='';

  // Получить LDAP по токену 
  if($ret=='') {
    $auth_uni->setLdap();
    if($auth_uni->getError()!='') {
      $ret = $auth_uni->getError();
      if($auth_uni->getDebug()) printf("Error : %s<br>",$auth_uni->getError());
    }
  }
  // Вычитать LDAP и проверить, что он не пустой
  if($ret=='') {
    if(($ldap=$auth_uni->getLdap())=='') {
      // Ошибка - пустой ldap
      $ret = "Empty LDAP auth (getLdap()) ".$auth_uni->getError();
      if($auth_uni->getDebug()) printf("Error : %s<br>",$auth_uni->getError());
    } else {
      // Все Ok, авторизация прошла, token есть, ldap есть
      $token=$auth_uni->getAccess_token();
      if($auth_uni->getDebug()) printf("LDAP : %s<br>",$auth_uni->getLdap());
    }
  }
  $retArray['error']=$ret;
  $retArray['ldap' ]=$ldap;
  $retArray['token']=$token;
  $auth_uni->destroy();
  unset($auth_uni);
  return $retArray['error'];
}
//-----------------------------------------------------------------------------------------------
// auth_uni_logout(): Разлогинивание сотрудника
//  $token    - токен
// Возврат: пусто при успехе, строка с сообщением при ошибке
// Пример применения: TOWER/logoff.php 
//-----------------------------------------------------------------------------------------------
function auth_uni_logout(&$token,$debug=FALSE){
  if($token=='') return 'Access_token is empty !';
  $ret='';
  $auth_uni = new Auth_Uni();
  $auth_uni->setDebug($debug);
  $auth_uni->init();
  if($ret=='') {
    $auth_uni->setAccess_token($token);
    if($auth_uni->getError()!='') $ret = $auth_uni->getError();
  }
  if($ret=='') {
    $auth_uni->logout();
    if ($auth_uni->getError()!='') $ret = $auth_uni->getError();
  }
  if($ret=='') unset($token);
  $auth_uni->destroy();
  unset($auth_uni);
  return $ret;
}
//-----------------------------------------------------------------------------------------------
// auth_uni_check(): Проверка по существующему токену, авторизован ли сотрудник (валидный ли токен)
//                   При невалидном токене - НЕ выполняется переход на страницу авторизации (генерируется ошибка)
//  $token    - токен
//  $retArray - массив возврата array('ldap','token','error')
// Возврат: пусто при успехе, строка с сообщением при ошибке
// Пример применения: TOWER/brw_kp_secure.php 
//-----------------------------------------------------------------------------------------------
function auth_uni_check($token,&$retArray,$debug=FALSE){
  if($token=='') return 'Access_token is empty !';
  $ret='';
  $auth_uni = new Auth_Uni();
  $auth_uni->setDebug($debug);
  $auth_uni->init();
  if($ret=='') {
    $auth_uni->setAccess_token($token);
    if($auth_uni->getError()!='') $ret = $auth_uni->getError();
  }
  if($ret=='') {
    $auth_uni->setLdap();
    if($auth_uni->getError()!='') $ret = $auth_uni->getError();
  }
  $retArray['error']=$ret;
  $retArray['ldap']=$auth_uni->getLdap();
  $retArray['token']=$token;
  $auth_uni->destroy();
  unset($auth_uni);
  return $retArray['error'];
}
//-----------------------------------------------------------------------------------------------
// auth_uni_getCEAUrl(): Получение адреса перехода на сервис Единой авторизации (CEA)
//  $name     - имя комплекса (client_id)
// Возврат: строка при успехе, если ошибка пусто
// Пример применения:  TOWER/brw_ns.php
//-----------------------------------------------------------------------------------------------
function auth_uni_getCEAUrl($name,$debug=FALSE){
    $ret='';
    $auth_uni = new Auth_Uni();
    $auth_uni->setDebug($debug);
    $auth_uni->init();
    $auth_uni->setClient_id($name);
    $ret=$auth_uni->getCEAUrl();
    $auth_uni->destroy();
    unset($auth_uni);
    return $ret;
}
//-----------------------------------------------------------------------------------------------
// auth_uni_isRefererCEA(): Проверка перехода с сервиса Единой авторизации (CEA)
// Возврат: TRUE/FALSE
// Пример применения:  TOWER/brw_ns.php
//-----------------------------------------------------------------------------------------------
function auth_uni_isRefererCEA(){
    $ret=FALSE;
    $auth_uni = new Auth_Uni();
    $ret=$auth_uni->isRefererCEA();
    //$auth_uni->destroy();
    unset($auth_uni);
    return $ret;
}
//-----------------------------------------------------------------------------------------------
// auth_uni_isAuthorize(): Проверка авторизован ли пользователь в (CEA)
// Для случая необязательной авторизации
// Возврат: TRUE/FALSE
// Пример применения:  TOWER/brw_ns.php
//-----------------------------------------------------------------------------------------------
function auth_uni_isAuthorize(){
    $ret=FALSE;
    $auth_uni = new Auth_Uni();
    $ret=$auth_uni->isAuthorize();
    //$auth_uni->destroy();
    unset($auth_uni);
    return $ret;
}
?>
