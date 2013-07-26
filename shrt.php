<?php
/**
 * 
 * Created:
 *    August 15, 2012
 * 
 * Updated:
 *    July, 2013
 * 
 * Copyright:
 *    (c) Paul D Bird II, 2012, All Rights Reserved
 * 
 * Purpose:
 *    shorten a URL
 * 
 * License:
 *    2-Clause FreeBSD Type License 
 *    https://github.com/katmore/shrturl/wiki/License
 * 
 * 
 */

require("shrt-config.php");



if ($shrtconfig["change_error_reporting"]) {
   error_reporting($shrtconfig["error_reporting"]);
}

define('shrt_include_path',$shrtconfig["include_path"]);

define("shrt_my_user",$shrtconfig["my_user"]);
define("shrt_my_pass",$shrtconfig["my_pass"]);
define("shrt_my_dbname",$shrtconfig["my_dbname"]);
define("shrt_my_host",$shrtconfig["my_host"] );
define("shrt_error_path",$shrtconfig["error_path"] );
define("shrt_error_theme",$shrtconfig["error_theme"] );
define("shrt_error_url_base",$shrtconfig["error_url_base"] );
define("shrt_allow_POST",serialize($shrtconfig["allow_POST"]));

if (isset($_GET["url_base"])) {
   if (isset($shrtconfig["url_base"][$_GET["url_base"]])) {
      define('shrt_url_base',$shrtconfig["url_base"][$_GET["url_base"]]);
   }
}

if (!defined('shrt_url_base')) {
   define('shrt_url_base',$shrtconfig["url_base"]["default"]);
}

require_once(shrt_include_path."/shrt.inc.php");
$shrt = new shrt();
if ( ((isset($_REQUEST["POST"])) && (isset($_REQUEST["target"])))  ||
   (isset($_POST["target"])) ) {
   if (!$shrt->allowPOST()) {
      //405 Method not allowed
      $shrt->error(403,"Forbidden");
   }
   $code = $shrt->code($_REQUEST["target"]);
   header("Content-type: ".$shrtconfig["Content-type"]);
   header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
   $code->show();
   exit();
   
}

if ( ((isset($_REQUEST["POST"])) && 
   (isset($_REQUEST["changeTarget"]) && isset($_REQUEST["code"]) ) )  ||
   (isset($_POST["changeTarget"]) && isset($_POST["code"])) ) {
   if (!$shrt->allowPOST()) {
      //405 Method not allowed
      $shrt->error(403,"Forbidden");
   }
   if (! $shrt->changeTarget($_REQUEST["target"],$_REQUEST["code"])) {
      $shrt->error(404,"Not Found");
   }
   header("Content-type: ".$shrtconfig["Content-type"]);
   header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
   $code->show();
   exit();
   
}

if ( ((isset($_REQUEST["POST"])) && (isset($_REQUEST["request"])))  ||
   (isset($_POST["request"])) ) {
   $request = json_decode($_REQUEST["request"],true);
   
   if (isset($request["shrt"]["target"])) {
      $response["shrt"]["shorturl"] = array();
      foreach($request["shrt"]["target"] as $target) {
         $response["shrt"]["target"][] = $target;
         $response["shrt"]["shorturl"][] = $shrt->code($target)->value();
      }
   
   
      header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
      header('Content-type: application/json');
      echo json_encode($response);
      exit();
   
   } else {
      
      $shrt->error(400,"Bad Request");
      exit();
      
   }
   
}

if (isset($_GET["code"])) {
   $target = $shrt->target($_GET["code"]);
   if ($target->value()===false) {
      $shrt->error(404,"Not Found");
      // header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
      // echo "error: no short url found";
      // exit();
   }
   header("Content-type: ".$shrtconfig["Content-type"]);
   header($_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently', true, 301);
   header("Location: ".$target->value());
   $target->show();
   exit();
}

// header($_SERVER['SERVER_PROTOCOL'] . ' 500 Server Error', true, 500);
// echo "error: no request to process";
$shrt->error(400,"Bad Request");

















