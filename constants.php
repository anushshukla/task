<?php

$CONSTANTS = [];

foreach ($CONSTANTS as $key => $value) :
  if(!defined($key)) :
    define($key, $value);
  endif;
endforeach;

if(!defined('ERROR'))  define('ERROR','ALL');//ACCEPTABLE VALUES:- ALL, NONE, ALL-EXCEPT-NOTICE OR RUNTIME
if(!defined('REQUEST_METHOD'))  define("REQUEST_METHOD",$_SERVER['REQUEST_METHOD']);
// const INPUTS = array_merge($_REQUEST,$_FILES);