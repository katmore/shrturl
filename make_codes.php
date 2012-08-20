<?php
require("shrt-config.php");
if ($shrtconfig["change_error_reporting"]) {
   error_reporting($shrtconfig["error_reporting"]);
}
header("Content-type: ".$shrtconfig["Content-type"]);
define("shrt_my_user",$shrtconfig["my_user"]);
define("shrt_my_pass",$shrtconfig["my_pass"]);
define("shrt_my_dbname",$shrtconfig["my_dbname"]);
define("shrt_my_host",$shrtconfig["my_host"] );

function getRandom( $min, $max) {
   return mt_rand($min,$max);
}
function addCode($code,$myi) {
   $sql = "
   INSERT INTO
      url
   SET
      code='$code'
   ";
   $result = $myi->query($sql);
   if (!$result) {
      echo "\nmysql error ".$myi->errno." ".$myi->error."\n";
      return false;
   }
   return true;
}
$my_user=shrt_my_user;$my_pass=shrt_my_pass;$my_dbname=shrt_my_dbname;$my_host=shrt_my_host;
$myi = new mysqli($my_host,$my_user,$my_pass,$my_dbname);
$num = 1000;
$len = 3;
$col = 1;
$allvalid = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
$validmax = strlen($allvalid) - 1;
if (($_GET["len"]>0) && ($_GET["len"]<100)) {
   $len = $_GET["len"];
}
$c = 0;
for ($i=0;$i<$num;$i++) {

   for($a=0;$a<100;$a++) {   
      $str ="";
      
      for ($l=0;$l<$len;$l++) {
         $str .=$allvalid[getRandom(0, $validmax) ];
      }
      echo $str;
      if (addCode($str,$myi)) break 1;
   }
   
   $c++;
   if ($c==$col) {
      echo "\n";
      $c=0;
   } else {
      echo "\t";
   }
   
}
