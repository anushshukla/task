<?php

class Response {
  private $request;
  private $headers  = [];
  private $status   = 200;
  private $protocol;
  private $endpoint;
  private $message;
  private $data;
  private $sent;
  private $type;
  private $subtype;
  private $client;
  private $expose_php = true;
  public $headers_list = true;
  public $headers_sent = true;
  private CONST TSV = [
    "!"  => "under construction"
    ,"?" => "dynamic"
    ,"G" => "gateway to multiple parties"
    ,"N" => "not tracking"
    ,"T" => "tracking"
    ,"C" => "tracking with consent"
    ,"P" => "tracking only if consented"
    ,"D" => "disregarding DNT"
    ,"U" => "updated"
  ];
  private CONST VALID_CONTENT_TYPE = [
    "text"=>["html"]
    ,"multipart" => ["mixed"]
    ,"message" => [""]
    ,"image" => [""]
    ,"audio" => [""]
    ,"video" => [""]
    ,"application" => ["json"]
    ,"x-token"
  ];

  function __construct()
  {
    $config = func_get_args()[0];
    ini_set('expose_php', $this->expose_php ? "one" : "off");
    $this->protocol = explode("/",$_SERVER['SERVER_PROTOCOL'])[0];
    $this->endpoint = explode("/",$_SERVER['SERVER_PROTOCOL'])[1];
    if(http_response_code()) {
      $this->status = http_response_code();
    }
    // $this->client = new Client;
    $this->_headers_list = headers_list();
    $this->_headers_sent = headers_sent();
    /*$this->headers["Upgrade"]           = $this->protocol."/".$this->endpoint.", HTTPS/1.3, IRC/6.9, RTA/x11, websocket";*/
    /*$this->headers["Warning"]           = "199 Miscellaneous warning";*/
    /*$this->headers["Via"]               = "1.0 fred, 1.1 example.com (Apache/1.1)";*/
    $this->headers["TSV"]               = array_search('tracking only if consented',Response::TSV);
    $this->headers["X-Frame-Options"]   = "deny";
    $this->headers["WWW-Authenticate"]  = "Basic";
    $this->headers["Pragma"]  = "no-cache";
    $this->headers["Access-Control-Allow-Origin"]  = "*";
    $this->headers["Accept-Patch"]  = "text/example;charset=utf-8";
  }

  public static function init()
  {
    return new Response(func_get_args());
  }

  function redirect($url, $status)
  {
    $this->headers["Location"]  = $url;
  }

  function array_to_xml($array,$xml)
  {
    foreach($array as $key => $value) {
      if(is_array($value)) {
          if(!is_numeric($key)){
              $subnode = $xml->addChild("$key");
              array_to_xml($value, $subnode);
          } else {
              array_to_xml($value, $xml);
          }
      } else {
          $xml->addChild("$key","$value");
      }
    }
    return $xml;
  }

  function file($fileAbsolutePath)
  {
    $this->headers["Content-type"] = mime_content_type($fileAbsolutePath);
  }

  public function allowCORS()
  {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, HEAD, PUT');
    header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
    header('Access-Control-Allow-Credentials: true');
  }

  public function download($path,$name,$extn)
  {
    $file = $path . DIRECTORY_SEPARATOR . $name . '.' . $extn;
    $this->headers["Content-Description"] = "File Transfer";
    $this->headers["Content-Type"] = "application/octet-stream";
    $this->headers["Content-Disposition"] = 'attachment; filename="'.basename($file).'"';
    $this->headers["Cache-Control"] = 'must-revalidate';
    $this->headers["Expires"] = 0;
    $this->headers["Pragma"] = 'public';
    $this->setHeaders();
    readfile($file);
    $this->sent = true;
  }

  function toXml($data)
  {
    $this->headers["Content-type"] = "application/xml";
    $this->setHeaders();
    // create simpleXML object
    $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><Response></Response>");
    $node = $xml->addChild('request');
    $xml = $this->array_to_xml((array)$data,$xml);
    echo $xml->asXML();
  }

  function toJson($data = [])
  {
    $this->headers["Content-type"] = "application/json";
    $this->setHeaders();
    if (is_object($data)) :
      $data = (array)$data;
    endif;
    echo json_encode($data);
  }

  function content($content)
  {
    $content_array = explode("/",(string)$content);
    if(count($content_array) !== 2) {
      throw new Exception("Invalid HTTP Response Content Type String",400);
    }
    $type = $content_array[0];
    if(count($type,Response::VALID_CONTENT_TYPE)) {
      throw new Exception("Invalid HTTP Response Content Type",400);
    }
    $subType = $content_array[1];
    if(!in_array($type,Response::VALID_CONTENT_TYPE)) {
      throw new Exception("Invalid HTTP Response Content Type",400);

    }
  }
  function html($string) {
    echo $string;
  }
  function render($path,$fileName,$fileFormat) {
    $html = include $path . DIRECTORY_SEPARATOR . $fileName . '.' . $fileFormat;
    $this->send($html);
  }
  function send($data = null) {
    if(!isset($this->headers["Content-type"])) {
      $this->headers["Content-type"] = "text/html";
    }
    switch ($this->headers["Content-type"]) :
      case "application/xml":$this->xml($data);break;
      case "application/json":$this->json($data);break;
      case "text/html":$this->html($data);break;
    endswitch;
    $this->sent = true;
  }

  function status($status) {
    if($status != (int)$status) {
      throw new ZException("HTTP Status needs to be a integer (whole number)",400);
    }
    if(strlen($status) !== 3) {
      throw new ZException("HTTP Status must be a 3 digit integer",417);
    }
    $this->status = $status;
    return $this;
  }

  function message($message) {
    if(!is_string($message)) {
      throw new ZException("HTTP Message needs to be a string",400);
    }
    if(strlen($message) > 255) {
      throw new ZException("Just to play safe, we are restricting HTTP Message to 255 characters",400);
    }
    $this->message = $message;
    return $this;
  }

  function setHeaders() {
    foreach ((array)$this->headers as $key => $value) :
      header("$key: $value", true);
    endforeach;
    if($this->status) {
      if($this->message) {
        header($this->protocol."/".$this->endpoint." ".$this->status." ".$this->message, true);
      } else {
        http_response_code($this->status);
      }
    }
    if(!$this->expose_php) {
      header_remove('X-Powered-By');
    }
  }

  function header($key,$value) {
    $this->headers[(string)$key] = (string)$value;
    return $this;
  }

  function headers($headers) {
    foreach ((array)$this->headers as $key => $value) :
      $this->header($key,$value);
    endforeach;
    return $this;
  }

  function __destruct() {
    if($this->sent) :
      /*if(!headers_sent()) :
        foreach (headers_list() as $header) :
          header_remove($header);
        endforeach;
      endif;*/
      # die() closes the connection while exit() does not
      die();
    endif;
  }
}