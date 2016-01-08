<?php
use \Katmore\Shrt;

header("Content-type: text/plain");

if (!is_readable("config.php")) {
   die("(ERROR) missing config.php. hint: copy config-sample.php");
}

echo "(OK) found config.php\n";

$loader =  __DIR__.'/../app/autoload.php';
if (!is_readable($loader)) {
   die("(ERROR) cannot find the autoloader. did you run composer?");
}

echo "(OK) found autoloader\n";

if (!class_exists("\\Katmore\\Shrt\\Factory")) {
   die("(ERROR) cannot find Factory class. hint: check composer.json and output from composer for any configuration issues.");
}

echo "(OK) found Shrt Factory\n";

