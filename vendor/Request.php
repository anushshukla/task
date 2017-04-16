<?php 

class Request
{

  private $_method;
  private $_host;
  private $_scheme;
  private $_port;
  private $_domain;
  private $_uri;
  private $_url;
  private $_get;
  private $_post;
  private $_put;
  private $_data;
  private $_headers;
  private $_isAjax;
  private $_ip;
  private CONST METHOD_GET = 'GET';
  private CONST METHOD_HEAD = 'HEAD';
  private CONST METHOD_POST = 'POST';
  private CONST METHOD_PUT = 'PUT';
  private CONST METHOD_DELETE = 'DELETE';
  private CONST METHOD_OPTIONS = 'OPTIONS';
  private CONST METHOD_TRACE = 'TRACE';
  private CONST METHODS = [
    Request::METHOD_GET
    ,Request::METHOD_HEAD
    ,Request::METHOD_POST
    ,Request::METHOD_PUT
    ,Request::METHOD_DELETE
    ,Request::METHOD_OPTIONS
    ,Request::METHOD_TRACE
  ];

  private function __construct()
  {
    $this->_method  = @$_SERVER['REQUEST_METHOD'];
    $this->_scheme  = $this->isSecure() ? 'https' : 'http';
    $this->_host    = @$_SERVER['HTTP_HOST'];
    $this->_port    = @$_SERVER['SERVER_PORT'];
    $this->_domain  = @$_SERVER['SERVER_NAME'];
    $this->_uri     = substr(strtok(@$_SERVER["REQUEST_URI"],'?'), 1);
    $this->_url     = $this->_scheme . ':' . '//' . $this->_host . '/' . $this->uri;
    $this->_get     = $_GET;
    $this->_post    = $_POST;
    $this->_files   = $_FILES;
    $this->_put     = json_decode(file_get_contents("php://input"), true) ?? [];
    $this->_data    = $_REQUEST + $this->_put + $this->_files;
    $this->_headers = getallheaders();
    $this->_isAjax  = @$_SERVER['HTTP_X_REQUESTED_WITH'] && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    $this->_ip      = @$_SERVER['HTTP_X_FORWARDED_FOR'] ?? @$_SERVER['REMOTE_ADDR'];
  }

  public static function init()
  {
    return new Request(func_get_args());
  }

  public function isSecure() {
    return
        ( !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' && $_SERVER['HTTPS'] == 'on')
        || ( strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https')
        || ( !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
        || ( !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
        || (443 == $this->_port)
        || (isset($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443)
        || (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https');
  }

  public function isMethod($method)
  {
    return $this->method == $method;
  }

  public function __call($method, $arguments){
    $method = strtoupper(str_replace('is', '', $method));
    if(in_array($method, Request::METHODS)) {
      return $this->isMethod($method);
    }
    throw new Exception(get_class($this) ." does not have a method, $method.");
  }

  function getHeader($headerName = "") {
    return isset($this->_headers[(string)$headerName])
    ? $this->_headers[(string)$headerName]
    : null;
  }

  public function __get($property) {
    $pvtProp = '_'.$property;
    if (property_exists($this,$pvtProp)) {
      return $this->$pvtProp;
    }
    $getterFuncName = 'get'.ucfirst($property);
    if (method_exists($this, $getterFuncName)) {
      return $this->$getterFuncName;
    }
    throw new Exception(get_class($this) ." does not have a attribute, $property.");
  }

  public function isSubDomain()
  {
    $hostArr = explode(".",$host);
    $count = count($hostArr);
    /*$hostArr = array_slice($a,1,-1);*/
    if($count > 2) :
      array_shift($hostArr);
      if($count > 3) :
          if($hostArr[0] === "demo") : array_shift($hostArr); endif;
          /*if($hostArr[$count-1] === "wwww") : array_pop($hostArr); endif;*/
      endif;
    endif;
    $domain = implode(".",$hostArr);
    $arr["email"] = "info@".$domain;
  }

  function __destruct() {
    // unset($this);
  }
}