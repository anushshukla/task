<?php
function my_error_handler()
{
  $last_error = error_get_last();
  if ($last_error && $last_error['type']==E_ERROR) :
    header("HTTP/1.1 500 Internal Server Error");
    echo 'Whoops, something went wrong!';//html for 500 page
  endif;
}
register_shutdown_function('my_error_handler');


$config = @file_get_contents('config/config.json');

if(!function_exists("response")) :
  function request() {
    return Request::init();
  }
endif;

if(!function_exists("response")) :
  function response() {
    return Response::init();
  }
endif;

if(!function_exists("xmlTOcsv")) :
  function xmlTOcsv($f,$xml) {
    foreach ($xml as $children) :
      if(count( $children->children())) :
        xmlTOcsv($f, $children);
      else :
        $put_arr = array($children->getName(),$children); 
        fputcsv($f, $put_arr ,',','"');
      endif;
    endforeach;
  }
endif;

spl_autoload_register(function($class) {
  require_once __DIR__.'/vendor/'.$class.'.php';
});

if(request()->uri === 'xml-to-csv') :
  if(request()->isGet()) :
    response()->render('mvc/views','xml-to-csv','html');
  elseif(request()->isPost()) :
    $filexml = request()->data['file']['tmp_name'];
    if (file_exists($filexml)) :
      $xml = simplexml_load_file($filexml);
      $file = 'tmp/output.csv';
      $f = fopen($file, 'w');
      xmlTOcsv($f, $xml);
      fclose($f);
    endif;
    response()->download('tmp','output','csv');
    exit;
  endif;
endif;

// REST API
if(request()->uri === 'product' && request()->isGet()) :
    "SELECT * `product`";
endif;
if(request()->uri === 'product' && request()->isPost() && request()->isAjax) :
    "INSERT INTO `product` VALUES ($price,$discount,$name)";
endif;
if(preg_match('/^product+\/[\d]+$/', request()->uri) && request()->isGet() && request()->isAjax) :
endif;
if(preg_match('/^product+\/[\d]+$/', request()->uri) && request()->isPut() && request()->isAjax) :
  "UPDATE `product` SET price = $price ,discount = $discount,name = $name WHERE id = $id";
endif;
if(preg_match('/^product+\/[\d]+$/', request()->uri) && request()->isDelete() && request()->isAjax) :
endif;
if(request()->uri === 'product') :
  if(request()->isGet()) :
    response()->render('mvc/views','product','html');
  elseif(request()->isAjax && request()->isPost()) :
  elseif(request()->isAjax && request()->isPut()) :
  elseif(request()->isAjax && request()->isDelete()) :
    'INSERT INTO `product`';
  endif;
endif;
if(request()->isAjax && request()->isGet()) :
  echo file_get_contents('mvc/views/'.request()->uri);
  die;
endif;

response()->html('<h1>PAGE NOT FOUND</h1>');
response()->status(404)->message('Not Found')->send();