<?php
/**
 * 
 * Created:
 *    August 15, 2012
 * 
 * Copyright:
 *    (c) Paul D Bird II, 2012, All Rights Reserved
 * 
 * Purpose:
 *    shorten a URL
 * 
 * License:
 *    GNU General Public License, version 2
 *    http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * 
 */



require("shrt-config.php");

header("Content-type: ".$shrtconfig["Content-type"]);

if ($shrtconfig["change_error_reporting"]) {
   error_reporting($shrtconfig["error_reporting"]);
}

define('shrt_url_base',$shrtconfig["url_base"]);
define('shrt_include_path',$shrtconfig["include_path"]);

define("shrt_my_user",$shrtconfig["my_user"]);
define("shrt_my_pass",$shrtconfig["my_pass"]);
define("shrt_my_dbname",$shrtconfig["my_dbname"]);
define("shrt_my_host",$shrtconfig["my_host"] );

require_once(shrt_include_path."/shrt.inc.php");

if ( ((isset($_REQUEST["POST"])) && (isset($_REQUEST["target"])))  ||
   (isset($_POST["target"])) ) {
   
   $shrt = new shrt();
   $code = $shrt->code($_REQUEST["target"]);
   header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
   $code->show();
   exit();
   
}

if (isset($_GET["code"])) {
   $shrt = new shrt();
   $target = $shrt->target($_GET["code"]);
   if ($target->value()===false) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
      echo "error: no short url found";
      exit();
   }
   
   header($_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently', true, 301);
   header("Location: ".$target->value());
   $target->show();
   exit();
}

header($_SERVER['SERVER_PROTOCOL'] . ' 500 Server Error', true, 500);
echo "error: no request to process";


















