<?php

use Katmore\Shrt\Factory;
header("Content-type: text/plain");

if (!is_readable("config.php")) {
   die("(ERROR) missing config.php: copy from config-sample.php and edit appropriately");
}

echo "(OK) found config.php\n";

$loader =  __DIR__.'/autoload.php';
if (!is_readable($loader)) {
   die("(ERROR) cannot find the autoloader. did you run composer?");
}

echo "(OK) found autoloader\n";

if (!class_exists("\\Katmore\\Shrt\\Factory")) {
   die("(ERROR) cannot find Factory class. hint: check composer.json and output from composer for any issues.");
}

echo "(OK) found Shrt\\Factory class\n";

try {
   $pdo = Factory::loadPDO($config["pdo"]);
} catch (\Exception $e) {
   echo "(ERROR) Error when loading PDO class. check the database settings in config.php.\n";
   throw $e;
}



















