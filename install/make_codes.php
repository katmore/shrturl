<?php
use \Katmore\Shrt\CodeGenerator;
if (!isset($config)) {
   $config = require(__DIR__."/../web/config.php");
   header("Content-type: text/plain");
}
$pdo = new \PDO(
   $config["pdo"]["dsn"], 
   $config["pdo"]["user"], 
   $config["pdo"]["pass"],
   $config["pdo"]["options"]
);
$num = 1000;
$col = 8;
$len = null;
if (isset($_GET) && is_array($_GET)) {
   if (!empty($_GET["num"]) && ($_GET["num"]>0)) {
      $num = (int) $_GET["num"];
   }
   if (!empty($_GET["len"]) && ($_GET["len"]>0) && ($_GET["len"]<100)) {
      $len = (int) $_GET["len"];
   }
} elseif (isset($argv) && is_array($argv) && isset($argv[1])) {
   $lenArg = sprintf("%d",$argv[1]);
   if (($lenArg == $argv[1]) && $lenArg < 100) {
      $len = $lenArg;
   }
}
echo "generating $num new short codes $len chars long\n";
$c = 0;
for ($i=0;$i<$num;$i++) {

   echo (new CodeGenerator($pdo,$len))->formatCode();
   $c++;
   if ($c==$col) {
      echo "\n";
      $c=0;
   } else {
      echo "\t";
   }
   
}
